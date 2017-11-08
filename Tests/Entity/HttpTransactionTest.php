<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Entity;

use Loevgaard\DandomainAltapayBundle\Entity\HttpTransaction;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;

class HttpTransactionTest extends TestCase
{
    public function testGettersSetters()
    {
        $httpTransaction = $this->getHttpTransaction();

        $request = $this->getMockClass(RequestInterface::class);
        $response = $this->getMockClass(ResponseInterface::class);

        $httpTransaction
            ->setId(1)
            ->setRequest($request)
            ->setResponse($response)
            ->setIp('127.0.0.1')
        ;

        $this->assertSame(1, $httpTransaction->getId());
        $this->assertSame($request, $httpTransaction->getRequest());
        $this->assertSame($response, $httpTransaction->getResponse());
        $this->assertSame('127.0.0.1', $httpTransaction->getIp());
    }

    public function testSymfonyRequest()
    {
        $httpTransaction = $this->getHttpTransaction();

        $request = Request::createFromGlobals();

        $httpTransaction->setRequest($request);

        $this->assertInternalType('string', $httpTransaction->getRequest());
    }

    public function testSymfonyRequestPost()
    {
        $httpTransaction = $this->getHttpTransaction();

        $request = Request::create('/test', 'POST', ['key' => 'vaæ']);

        $httpTransaction->setRequest($request);

        $this->assertInternalType('string', $httpTransaction->getRequest());
    }

    /**
     * @return HttpTransaction
     */
    public function getHttpTransaction()
    {
        return $this->getMockForAbstractClass(HttpTransaction::class);
    }
}
