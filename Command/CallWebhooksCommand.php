<?php

namespace Loevgaard\DandomainAltapayBundle\Command;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\RequestOptions;
use JMS\Serializer\SerializerInterface;
use Loevgaard\DandomainAltapayBundle\Entity\Event;
use Loevgaard\DandomainAltapayBundle\Entity\EventRepository;
use Loevgaard\DandomainAltapayBundle\Entity\WebhookExchange;
use Loevgaard\DandomainAltapayBundle\Entity\WebhookExchangeRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CallWebhooksCommand extends ContainerAwareCommand
{
    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var WebhookExchangeRepository
     */
    private $webhookExchangeRepository;

    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Client
     */
    private $httpClient;

    public function __construct(
        WebhookExchangeRepository $webhookExchangeRepository,
        EventRepository $eventRepository,
        SerializerInterface $serializer,
        LoggerInterface $logger
    ) {
        $this->webhookExchangeRepository = $webhookExchangeRepository;
        $this->eventRepository = $eventRepository;
        $this->serializer = $serializer;
        $this->logger = $logger;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('loevgaard:dandomain:altapay:call-webhooks')
            ->setDescription('Will call all the respective webhook URLs')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $maxCallsPerRun = 50;

        $webhookUrls = $this->getContainer()->getParameter('loevgaard_dandomain_altapay.webhook_urls');
        if (empty($webhookUrls)) {
            $output->writeln('No webhook URLs defined');

            return 0;
        }

        /** @var WebhookExchange[] $webhookExchanges */
        $webhookExchanges = [];

        foreach ($webhookUrls as $webhookUrl) {
            $webhookExchange = $this->webhookExchangeRepository->findByUrl($webhookUrl);
            if (!$webhookExchange) {
                $output->writeln('Webhook exchange with URL `'.$webhookUrl.'` is not created. Will be created now', OutputInterface::VERBOSITY_VERBOSE);
                $webhookExchange = new WebhookExchange($webhookUrl);
                $this->webhookExchangeRepository->save($webhookExchange);
            }

            $webhookExchanges[] = $webhookExchange;
        }

        $eventsPerExchange = ceil($maxCallsPerRun / count($webhookExchanges));

        foreach ($webhookExchanges as $webhookExchange) {
            $events = $this->eventRepository->findRecentEvents($webhookExchange->getLastEventId(), $eventsPerExchange);

            foreach ($events as $event) {
                if (!$this->callWebhook($webhookExchange->getUrl(), $event)) {
                    break;
                }

                $webhookExchange->setLastEventId($event->getId());
                $this->webhookExchangeRepository->flush();
            }
        }

        return 0;
    }

    /**
     * @param string $url
     * @param Event  $event
     *
     * @return bool
     */
    private function callWebhook(string $url, Event $event): bool
    {
        if (!$this->httpClient) {
            $this->httpClient = new Client([
                RequestOptions::ALLOW_REDIRECTS => false,
                RequestOptions::CONNECT_TIMEOUT => 15,
                RequestOptions::TIMEOUT => 30,
                RequestOptions::HTTP_ERRORS => false,
                RequestOptions::HEADERS => [
                    'Content-Type' => 'application/json',
                ],
            ]);
        }

        $serializedEvent = $this->serializer->serialize($event, 'json');

        try {
            $response = $this->httpClient->post($url, [
                'body' => $serializedEvent,
            ]);
        } catch (TransferException $e) {
            $this->output->writeln('[Event: '.$event->getId().'] Webhook URL ('.$url.') returned an error: '.$e->getMessage(), OutputInterface::VERBOSITY_VERBOSE);

            $this->logger->error('[Event: '.$event->getId().'] Webhook URL ('.$url.') returned an error: '.$e->getMessage(), [
                'event' => $serializedEvent,
            ]);

            return false;
        }

        if (200 !== $response->getStatusCode()) {
            $this->output->writeln('[Event: '.$event->getId().'] Webhook URL ('.$url.') returned status code: '.$response->getStatusCode(), OutputInterface::VERBOSITY_VERBOSE);

            $this->logger->error('[Event: '.$event->getId().'] Webhook URL ('.$url.') returned status code: '.$response->getStatusCode(), [
                'event' => $serializedEvent,
            ]);

            return false;
        }

        return true;
    }
}
