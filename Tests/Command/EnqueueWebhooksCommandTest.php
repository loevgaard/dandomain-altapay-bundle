<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Command;

use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Loevgaard\DandomainAltapayBundle\Command\EnqueueWebhooksCommand;
use Loevgaard\DandomainAltapayBundle\Entity\Event;
use Loevgaard\DandomainAltapayBundle\Entity\EventRepository;
use Loevgaard\DandomainAltapayBundle\Entity\WebhookExchange;
use Loevgaard\DandomainAltapayBundle\Entity\WebhookExchangeRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EnqueueWebhooksCommandTest extends TestCase
{
    public function testNoWebhookUrls()
    {
        $command = $this->getCommand();

        $application = new Application();
        $application->setAutoExit(false);
        $application->add($command);

        $command = $application->find('loevgaard:dandomain:altapay:enqueue-webhooks');
        $commandTester = new CommandTester($command);
        $exitCode = $commandTester->execute([
            'command' => $command->getName(),
        ]);

        $this->assertSame('No webhook URLs defined', trim($commandTester->getDisplay()));
        $this->assertSame(0, $exitCode, 'Returns 0 if there are not webhook URLs');
    }

    public function testExecute()
    {
        $webhookUrl = 'https://www.example.com';

        $container = $this->getContainer();
        $container
            ->method('getParameter')
            ->with('loevgaard_dandomain_altapay.webhook_urls')
            ->willReturn([$webhookUrl]);

        $webhookExchangeRepository = $this->getWebhookExchangeRepository();
        $webhookExchangeRepository
            ->method('findByUrlOrCreate')
            ->with($webhookUrl)
            ->willReturn(new WebhookExchange($webhookUrl));

        $event = new Event('event', 'event_body');
        $event->setId(1);
        $eventRepository = $this->getEventRepository();
        $eventRepository
            ->method('findRecentEvents')
            ->willReturn([$event]);

        $command = $this->getCommand($webhookExchangeRepository, $eventRepository, null, $container);

        $application = new Application();
        $application->setAutoExit(false);
        $application->add($command);

        $command = $application->find('loevgaard:dandomain:altapay:enqueue-webhooks');
        $commandTester = new CommandTester($command);
        $exitCode = $commandTester->execute([
            'command' => $command->getName(),
        ]);

        $this->assertSame(0, $exitCode, 'Returns 0 on success');
    }

    /**
     * @param null $webhookExchangeRepository
     * @param null $eventRepository
     * @param null $serializer
     * @param null $container
     *
     * @return EnqueueWebhooksCommand
     */
    private function getCommand($webhookExchangeRepository = null, $eventRepository = null, $serializer = null, $container = null)
    {
        if (!$webhookExchangeRepository) {
            $webhookExchangeRepository = $this->getWebhookExchangeRepository();
        }

        if (!$eventRepository) {
            $eventRepository = $this->getEventRepository();
        }

        if (!$serializer) {
            $serializer = $this->getSerializer();
        }

        if (!$container) {
            $container = $this->getContainer();
        }

        $command = new EnqueueWebhooksCommand($webhookExchangeRepository, $eventRepository, $serializer);
        $command->setContainer($container);

        return $command;
    }

    /**
     * @return WebhookExchangeRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getWebhookExchangeRepository()
    {
        return $this->createMock(WebhookExchangeRepository::class);
    }

    /**
     * @return EventRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getEventRepository()
    {
        return $this->createMock(EventRepository::class);
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
