<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Command;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\TransferException;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Loevgaard\DandomainAltapayBundle\Command\ConsumeWebhookQueueCommand;
use Loevgaard\DandomainAltapayBundle\Entity\WebhookExchange;
use Loevgaard\DandomainAltapayBundle\Entity\WebhookExchangeRepository;
use Loevgaard\DandomainAltapayBundle\Entity\WebhookQueueItem;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ConsumeWebhookQueueCommandTest extends TestCase
{
    public function testExecute1()
    {
        $webhookUrl = 'https://www.example.com';

        $webhookExchange = new WebhookExchange($webhookUrl);

        $webhookQueueItemToConsume = new WebhookQueueItem('content', $webhookExchange);

        $webhookQueueItemToNotConsume1 = new WebhookQueueItem('content', $webhookExchange);
        $webhookQueueItemToNotConsume1->setStatus(WebhookQueueItem::STATUS_SUCCESS);

        $webhookQueueItemToNotConsume2 = new WebhookQueueItem('content', $webhookExchange);
        $webhookQueueItemToNotConsume2->setNextTry((new \DateTimeImmutable())->add(new \DateInterval('P1D')));

        $webhookExchange
            ->addWebhookQueueItem($webhookQueueItemToConsume)
            ->addWebhookQueueItem($webhookQueueItemToNotConsume1)
            ->addWebhookQueueItem($webhookQueueItemToNotConsume2);

        $webhookExchangeRepository = $this->getWebhookExchangeRepository(['findAll', 'flush']);
        $webhookExchangeRepository
            ->method('findAll')
            ->willReturn([$webhookExchange]);

        $command = $this->getCommand($webhookExchangeRepository);

        $commandTester = $this->execute($command);

        $this->assertTrue($webhookQueueItemToConsume->isStatus(WebhookQueueItem::STATUS_SUCCESS));
        $this->assertSame(0, $webhookQueueItemToNotConsume1->getTries());
        $this->assertSame(0, $webhookQueueItemToNotConsume2->getTries());
        $this->assertSame(0, $commandTester->getStatusCode(), 'Returns 0 on success');
    }

    public function testThrowGeneralException()
    {
        $webhookExchange = new WebhookExchange('https://www.example.com');

        $webhookQueueItem = new WebhookQueueItem('content', $webhookExchange);

        $webhookExchange
            ->addWebhookQueueItem($webhookQueueItem);

        $webhookExchangeRepository = $this->getWebhookExchangeRepository(['findAll', 'flush']);
        $webhookExchangeRepository
            ->method('findAll')
            ->willReturn([$webhookExchange]);

        $command = $this->getCommand($webhookExchangeRepository);

        $client = $this->createMock(ClientInterface::class);
        $client->method('send')->willThrowException(new \Exception());

        $command->setHttpClient($client);

        $commandTester = $this->execute($command);

        $this->assertTrue($webhookQueueItem->isStatus(WebhookQueueItem::STATUS_ERROR));
        $this->assertSame(0, $commandTester->getStatusCode(), 'Returns 0 on success');
    }

    public function testThrowTransferException()
    {
        $webhookExchange = new WebhookExchange('https://www.example.com');

        $webhookQueueItem = new WebhookQueueItem('content', $webhookExchange);

        $webhookExchange
            ->addWebhookQueueItem($webhookQueueItem);

        $webhookExchangeRepository = $this->getWebhookExchangeRepository(['findAll', 'flush']);
        $webhookExchangeRepository
            ->method('findAll')
            ->willReturn([$webhookExchange]);

        $command = $this->getCommand($webhookExchangeRepository);

        $client = $this->createMock(ClientInterface::class);
        $client->method('send')->willThrowException(new TransferException());

        $command->setHttpClient($client);

        $commandTester = $this->execute($command);

        $this->assertTrue($webhookQueueItem->isStatus(WebhookQueueItem::STATUS_ERROR));
        $this->assertSame(0, $commandTester->getStatusCode(), 'Returns 0 on success');
    }

    public function testResponseNot200()
    {
        $webhookExchange = new WebhookExchange('https://www.example.com');

        $webhookQueueItem = new WebhookQueueItem('content', $webhookExchange);

        $webhookExchange
            ->addWebhookQueueItem($webhookQueueItem);

        $webhookExchangeRepository = $this->getWebhookExchangeRepository(['findAll', 'flush']);
        $webhookExchangeRepository
            ->method('findAll')
            ->willReturn([$webhookExchange]);

        $command = $this->getCommand($webhookExchangeRepository);

        $response = $this->createMock(ResponseInterface::class);
        $response
            ->method('getStatusCode')
            ->willReturn(400);

        $response
            ->method('getHeaders')
            ->willReturn([]);

        $client = $this->createMock(ClientInterface::class);
        $client->method('send')->willReturn($response);

        $command->setHttpClient($client);

        $commandTester = $this->execute($command);

        $this->assertTrue($webhookQueueItem->isStatus(WebhookQueueItem::STATUS_ERROR));
        $this->assertSame(0, $commandTester->getStatusCode(), 'Returns 0 on success');
    }

    private function execute(ConsumeWebhookQueueCommand $command)
    {
        $application = new Application();
        $application->setAutoExit(false);
        $application->add($command);

        $command = $application->find('loevgaard:dandomain:altapay:consume-webhook-queue');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
        ]);

        return $commandTester;
    }

    /**
     * @param null $webhookExchangeRepository
     * @param null $serializer
     * @param null $container
     *
     * @return ConsumeWebhookQueueCommand
     */
    private function getCommand($webhookExchangeRepository = null, $serializer = null, $container = null): ConsumeWebhookQueueCommand
    {
        if (!$webhookExchangeRepository) {
            $webhookExchangeRepository = $this->getWebhookExchangeRepository();
        }

        if (!$serializer) {
            $serializer = $this->getSerializer();
        }

        if (!$container) {
            $container = $this->getContainer();
        }

        $command = new ConsumeWebhookQueueCommand($webhookExchangeRepository, $serializer);
        $command->setContainer($container);

        return $command;
    }

    /**
     * @param array $methods
     *
     * @return WebhookExchangeRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getWebhookExchangeRepository(array $methods = [])
    {
        $mockBuilder = $this->getMockBuilder(WebhookExchangeRepository::class)->disableOriginalConstructor();

        if (!empty($methods)) {
            $mockBuilder->setMethods($methods);
        }

        return $mockBuilder->getMock();
    }

    private function getSerializer(): SerializerInterface
    {
        return SerializerBuilder::create()->build();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ContainerInterface
     */
    private function getContainer()
    {
        return $this->createMock(ContainerInterface::class);
    }
}
