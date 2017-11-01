<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Http;

use Loevgaard\DandomainAltapayBundle\Entity\HttpTransaction;
use Loevgaard\DandomainAltapayBundle\Entity\HttpTransactionRepository;
use Loevgaard\DandomainAltapayBundle\Http\TransactionLogger;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class TransactionLoggerTest extends TestCase
{
    public function testTransactionLog()
    {
        $request = new Request();
        $response = new Response();

        /** @var HttpTransactionRepository|\PHPUnit_Framework_MockObject_MockObject $httpTransactionRepository */
        $httpTransactionRepository = $this->getMockBuilder(HttpTransactionRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $transactionCounter = 0;

        $httpTransactionRepository
            ->expects($this->any())
            ->method('save')
            ->willReturnCallback(function () use (&$transactionCounter) {
                ++$transactionCounter;
            })
        ;

        $transactionLogger = new TransactionLogger($httpTransactionRepository);
        $transactionLogger->setRequest($request);
        $transactionLogger->setResponse($request, $response);
        $transactionLogger->flush();

        $this->assertSame(1, $transactionCounter);
    }

    /**
     * @return HttpTransaction|\PHPUnit_Framework_MockObject_MockObject
     */
    public function createHttpTransaction()
    {
        return new HttpTransaction();
    }
}
