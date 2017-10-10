<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Http;

use Loevgaard\DandomainAltapayBundle\Entity\HttpTransaction;
use Loevgaard\DandomainAltapayBundle\Http\TransactionLogger;
use Loevgaard\DandomainAltapayBundle\Manager\HttpTransactionManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class TransactionLoggerTest extends TestCase
{
    public function testTransactionLog()
    {
        $request = new Request();
        $response = new Response();

        /** @var HttpTransactionManager|\PHPUnit_Framework_MockObject_MockObject $httpTransactionManager */
        $httpTransactionManager = $this->getMockBuilder(HttpTransactionManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $httpTransactionManager
            ->expects($this->any())
            ->method('create')
            ->willReturnCallback([$this, 'createHttpTransaction'])
        ;

        $transactionCounter = 0;

        $httpTransactionManager
            ->expects($this->any())
            ->method('update')
            ->willReturnCallback(function ($entity) use ($request, $response, &$transactionCounter) {
                /* @var HttpTransaction $entity */
                ++$transactionCounter;
            })
        ;

        $transactionLogger = new TransactionLogger($httpTransactionManager);
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
        return $this->getMockForAbstractClass(HttpTransaction::class);
    }
}
