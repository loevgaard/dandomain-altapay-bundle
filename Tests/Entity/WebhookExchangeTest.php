<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Entity;

use Loevgaard\DandomainAltapayBundle\Entity\WebhookExchange;
use PHPUnit\Framework\TestCase;

class WebhookExchangeTest extends TestCase
{
    public function testGettersSetters()
    {
        $url = 'https://www.example.com';

        $entity = new WebhookExchange($url);

        $this->assertSame(null, $entity->getId());
        $this->assertSame($url, $entity->getUrl());
        $this->assertSame(0, $entity->getLastEventId());

        $entity
            ->setId(10)
            ->setLastEventId(1)
            ->setUrl('https://www.example.dk')
        ;

        $this->assertSame(10, $entity->getId());
        $this->assertSame('https://www.example.dk', $entity->getUrl());
        $this->assertSame(1, $entity->getLastEventId());
    }
}
