<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Entity;

use Loevgaard\DandomainAltapayBundle\Entity\WebhookExchange;
use Loevgaard\DandomainAltapayBundle\Entity\WebhookExchangeRepository;
use PHPUnit\Framework\TestCase;

class WebhookExchangeRepositoryTest extends TestCase
{
    public function testReturnObject()
    {
        $webhookExchangeRepository = $this->getWebhookExchangeRepository();

        $url = 'https://www.example.com';
        $obj = new WebhookExchange($url);
        $webhookExchangeRepository
            ->method('findOneBy')
            ->willReturn($obj);

        $this->assertSame($obj, $webhookExchangeRepository->findByUrlOrCreate($url));
    }

    /**
     * @return WebhookExchangeRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getWebhookExchangeRepository()
    {
        /** @var WebhookExchangeRepository|\PHPUnit_Framework_MockObject_MockObject $webhookExchangeRepository */
        $webhookExchangeRepository = $this->getMockBuilder(WebhookExchangeRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findOneBy'])
            ->getMock();

        return $webhookExchangeRepository;
    }
}
