<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Entity;

use Doctrine\Common\Persistence\ManagerRegistry;
use JMS\Serializer\SerializerBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Loevgaard\DandomainAltapayBundle\Entity\Event;
use Loevgaard\DandomainAltapayBundle\Entity\EventRepository;
use Loevgaard\DandomainAltapayBundle\Entity\Payment;
use Loevgaard\DandomainAltapayBundle\Event\PaymentCreated;
use PHPUnit\Framework\TestCase;

class EventRepositoryTest extends TestCase
{
    public function testCreateFromDomainEvent()
    {
        $serializer = SerializerBuilder::create()->build();
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $managerRegistry->method('getManagerForClass')->willReturn(null);
        $paginator = $this->createMock(PaginatorInterface::class);

        $domainEvent = new PaymentCreated(new Payment());

        $expectedEvent = new Event($domainEvent->getName(), $serializer->serialize($domainEvent, 'json'));

        $eventRepository = new EventRepository($managerRegistry, $paginator, 'class', $serializer);

        $this->assertEquals($expectedEvent, $eventRepository->createFromDomainEvent($domainEvent));
    }
}
