<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Entity;

use Loevgaard\DandomainAltapayBundle\Entity\Event;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testGettersSetters()
    {
        $event = new Event('name', 'body');

        $event
            ->setId(1)
        ;

        $this->assertSame(1, $event->getId());
        $this->assertSame('name', $event->getName());
        $this->assertSame('body', $event->getBody());
    }
}
