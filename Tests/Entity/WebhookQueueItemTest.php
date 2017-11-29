<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Entity;

use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Loevgaard\DandomainAltapayBundle\Entity\WebhookExchange;
use Loevgaard\DandomainAltapayBundle\Entity\WebhookQueueItem;
use PHPUnit\Framework\TestCase;

class WebhookQueueItemTest extends TestCase
{
    private $webhookExchange;

    public function setUp()
    {
        parent::setUp();

        $this->webhookExchange = new WebhookExchange('https://www.example.com');
    }

    public function testInstantiation()
    {
        $webhookQueueItem = new WebhookQueueItem('content', $this->webhookExchange);
        $this->assertSame(0, $webhookQueueItem->getId());
        $this->assertSame('content', $webhookQueueItem->getContent());
        $this->assertSame($this->webhookExchange, $webhookQueueItem->getWebhookExchange());
    }

    public function testGettersSetters()
    {
        $nextTry = new \DateTimeImmutable();

        $response = new Response();
        $request = new Request('get', 'https://www.example.com');

        $webhookQueueItem = new WebhookQueueItem('content', $this->webhookExchange);
        $webhookQueueItem
            ->setId(1)
            ->setNextTry($nextTry)
            ->setStatus(WebhookQueueItem::STATUS_SUCCESS)
            ->setError('error')
            ->setTries(1)
            ->setResponse($response)
            ->setRequest($request)
        ;

        $this->assertSame(1, $webhookQueueItem->getId());
        $this->assertSame($nextTry, $webhookQueueItem->getNextTry());
        $this->assertSame(WebhookQueueItem::STATUS_SUCCESS, $webhookQueueItem->getStatus());
        $this->assertSame('error', $webhookQueueItem->getError());
        $this->assertSame(1, $webhookQueueItem->getTries());
        $this->assertSame(Psr7\str($response), $webhookQueueItem->getResponse());
        $this->assertSame(Psr7\str($request), $webhookQueueItem->getRequest());
        $this->assertCount(3, WebhookQueueItem::getStatuses());
    }

    public function testMarkAsError()
    {
        $now = new \DateTime();
        $expectedNextTry = \DateTimeImmutable::createFromMutable($now)->add(new \DateInterval('PT2M'));

        $webhookQueueItem = new WebhookQueueItem('content', $this->webhookExchange);
        $webhookQueueItem->setUpdatedAt($now);
        $webhookQueueItem->markAsError('test');

        $this->assertSame('test', $webhookQueueItem->getError());
        $this->assertSame(1, $webhookQueueItem->getTries());
        $this->assertSame(WebhookQueueItem::STATUS_ERROR, $webhookQueueItem->getStatus());
        $this->assertEquals($expectedNextTry, $webhookQueueItem->getNextTry());
    }

    public function testMarkAsErrorWithException()
    {
        $exception = new \Exception('message');
        $webhookQueueItem = new WebhookQueueItem('content', $this->webhookExchange);
        $webhookQueueItem->markAsError($exception);

        $this->assertInternalType('string', $webhookQueueItem->getError());
    }

    public function testUpdateNextTryWithMinutesOver120()
    {
        $now = new \DateTime();
        $expectedNextTry = \DateTimeImmutable::createFromMutable($now)->add(new \DateInterval('PT120M'));

        $exception = new \Exception('message');
        $webhookQueueItem = new WebhookQueueItem('content', $this->webhookExchange);
        $webhookQueueItem->setUpdatedAt($now);
        $webhookQueueItem->setTries(10);
        $webhookQueueItem->markAsError($exception);

        $this->assertEquals($expectedNextTry, $webhookQueueItem->getNextTry());
    }
}
