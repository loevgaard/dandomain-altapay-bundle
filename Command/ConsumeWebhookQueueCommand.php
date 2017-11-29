<?php

namespace Loevgaard\DandomainAltapayBundle\Command;

use Doctrine\Common\Collections\Criteria;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use JMS\Serializer\SerializerInterface;
use Loevgaard\DandomainAltapayBundle\Entity\WebhookExchange;
use Loevgaard\DandomainAltapayBundle\Entity\WebhookExchangeRepository;
use Loevgaard\DandomainAltapayBundle\Entity\WebhookQueueItem;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsumeWebhookQueueCommand extends ContainerAwareCommand
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
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var Client
     */
    private $httpClient;

    public function __construct(WebhookExchangeRepository $webhookExchangeRepository, SerializerInterface $serializer)
    {
        $this->webhookExchangeRepository = $webhookExchangeRepository;
        $this->serializer = $serializer;

        parent::__construct();
    }

    public function setHttpClient(ClientInterface $client)
    {
        $this->httpClient = $client;
    }

    protected function configure()
    {
        $this->setName('loevgaard:dandomain:altapay:consume-webhook-queue')
            ->setDescription('Will consume the webhook queue and do the HTTP requests')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!($this->lock())) {
            $output->writeln('The command is already running in another process.');

            return 0;
        }

        $this->output = $output;

        /** @var WebhookExchange[] $webhookExchanges */
        $webhookExchanges = $this->webhookExchangeRepository->findAll();

        foreach ($webhookExchanges as $webhookExchange) {
            $now = new \DateTimeImmutable();

            $criteria = Criteria::create()
                ->where(
                    Criteria::expr()->orX(
                        Criteria::expr()->eq('status', WebhookQueueItem::STATUS_PENDING),
                        Criteria::expr()->eq('status', WebhookQueueItem::STATUS_ERROR)
                    )
                )->andWhere(Criteria::expr()->lte('nextTry', $now))
                ->setMaxResults(50)
            ;

            /** @var WebhookQueueItem[] $webhookQueueItems */
            $webhookQueueItems = $webhookExchange->getWebhookQueueItems()->matching($criteria);

            foreach ($webhookQueueItems as $webhookQueueItem) {
                try {
                    if ($this->doRequest($webhookQueueItem)) {
                        $webhookQueueItem->markAsSuccess();
                    }
                } catch (\Exception $e) {
                    $webhookQueueItem->markAsError($e->getMessage());
                }

                $this->webhookExchangeRepository->flush();
            }
        }

        $this->release();

        return 0;
    }

    /**
     * @param WebhookQueueItem $webhookQueueItem
     *
     * @return bool
     */
    private function doRequest(WebhookQueueItem $webhookQueueItem): bool
    {
        if (!$this->httpClient) {
            $this->httpClient = new Client([
                RequestOptions::ALLOW_REDIRECTS => false,
                RequestOptions::CONNECT_TIMEOUT => 15,
                RequestOptions::TIMEOUT => 30,
                RequestOptions::HTTP_ERRORS => false,
            ]);
        }

        $request = new Request('post', $webhookQueueItem->getWebhookExchange()->getUrl(), ['Content-Type' => 'application/json'], $webhookQueueItem->getContent());
        $webhookQueueItem->setRequest($request);

        try {
            $response = $this->httpClient->send($request);
            $webhookQueueItem->setResponse($response);
        } catch (TransferException $e) {
            $this->output->writeln('[Webhook Queue Item: '.$webhookQueueItem->getId().'] Webhook URL ('.$webhookQueueItem->getWebhookExchange()->getUrl().') returned an error: '.$e->getMessage(), OutputInterface::VERBOSITY_VERBOSE);
            $webhookQueueItem->markAsError($e->getMessage());

            return false;
        }

        if (200 !== $response->getStatusCode()) {
            $this->output->writeln('[Webhook Queue Item: '.$webhookQueueItem->getId().'] Webhook URL ('.$webhookQueueItem->getWebhookExchange()->getUrl().') returned status code: '.$response->getStatusCode(), OutputInterface::VERBOSITY_VERBOSE);
            $webhookQueueItem->markAsError('Status code was '.$response->getStatusCode());

            return false;
        }

        return true;
    }
}
