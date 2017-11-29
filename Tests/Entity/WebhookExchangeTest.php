<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Loevgaard\DandomainAltapayBundle\Entity\WebhookExchange;
use Loevgaard\DandomainAltapayBundle\Entity\WebhookQueueItem;
use PHPUnit\Framework\TestCase;

class WebhookExchangeTest extends TestCase
{
    public function testGettersSetters()
    {
        $url = 'https://www.example.com';

        $entity = new WebhookExchange($url);

        $this->assertSame(0, $entity->getId());
        $this->assertSame($url, $entity->getUrl());
        $this->assertSame(0, $entity->getLastEventId());

        $webhookQueueItem = new WebhookQueueItem('content', new WebhookExchange('https://www.example.com'));
        $entity
            ->setId(10)
            ->setLastEventId(1)
            ->setUrl('https://www.example.dk')
            ->addWebhookQueueItem($webhookQueueItem)
        ;

        $this->assertSame(10, $entity->getId());
        $this->assertSame('https://www.example.dk', $entity->getUrl());
        $this->assertSame(1, $entity->getLastEventId());
        $this->assertCount(1, $entity->getWebhookQueueItems());
        $this->assertSame($webhookQueueItem, $entity->getWebhookQueueItems()->first());

        $webhookQueueItems = new ArrayCollection([
            new WebhookQueueItem('content', new WebhookExchange('https://www.example.com')),
            new WebhookQueueItem('content', new WebhookExchange('https://www.example.com')),
        ]);

        $entity
            ->setId(10)
            ->setLastEventId(1)
            ->setUrl('https://www.example.dk')
            ->setWebhookQueueItems($webhookQueueItems)
        ;

        $this->assertCount(2, $entity->getWebhookQueueItems());
        $this->assertSame($webhookQueueItems, $entity->getWebhookQueueItems());
    }
}
