<?php

namespace Loevgaard\DandomainAltapayBundle\Command;

use JMS\Serializer\SerializerInterface;
use Loevgaard\DandomainAltapayBundle\Entity\EventRepository;
use Loevgaard\DandomainAltapayBundle\Entity\WebhookExchange;
use Loevgaard\DandomainAltapayBundle\Entity\WebhookExchangeRepository;
use Loevgaard\DandomainAltapayBundle\Entity\WebhookQueueItem;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EnqueueWebhooksCommand extends ContainerAwareCommand
{
    use LockableTrait;

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

    public function __construct(
        WebhookExchangeRepository $webhookExchangeRepository,
        EventRepository $eventRepository,
        SerializerInterface $serializer
    ) {
        $this->webhookExchangeRepository = $webhookExchangeRepository;
        $this->eventRepository = $eventRepository;
        $this->serializer = $serializer;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('loevgaard:dandomain:altapay:enqueue-webhooks')
            ->setDescription('Will enqueue events to be sent to webhooks')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!($this->lock())) {
            $output->writeln('The command is already running in another process.');

            return 0;
        }

        $this->output = $output;

        $maxEventsToEnqueue = 50;

        $webhookUrls = $this->getContainer()->getParameter('loevgaard_dandomain_altapay.webhook_urls');
        if (empty($webhookUrls)) {
            $output->writeln('No webhook URLs defined');

            return 0;
        }

        /** @var WebhookExchange[] $webhookExchanges */
        $webhookExchanges = [];

        foreach ($webhookUrls as $webhookUrl) {
            $webhookExchange = $this->webhookExchangeRepository->findByUrlOrCreate($webhookUrl);
            $webhookExchanges[] = $webhookExchange;
        }

        $eventsPerExchange = ceil($maxEventsToEnqueue / count($webhookExchanges));

        foreach ($webhookExchanges as $webhookExchange) {
            $events = $this->eventRepository->findRecentEvents($webhookExchange->getLastEventId(), $eventsPerExchange);

            foreach ($events as $event) {
                $webhookQueueItem = new WebhookQueueItem($this->serializer->serialize($event, 'json'), $webhookExchange);

                $webhookExchange
                    ->addWebhookQueueItem($webhookQueueItem)
                    ->setLastEventId($event->getId())
                ;

                $this->webhookExchangeRepository->flush();
            }
        }

        return 0;
    }
}
