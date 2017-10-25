<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\EventListener;

use Loevgaard\DandomainAltapayBundle\EventListener\ControllerListener;
use Loevgaard\DandomainAltapayBundle\EventListener\ResponseListener;
use Loevgaard\DandomainAltapayBundle\Http\TransactionLogger;
use Loevgaard\DandomainAltapayBundle\Tests\EventListener\Fixture\ControllerWithAnnotation;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ResponseListenerTest extends TestCase
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var ResponseListener
     */
    protected $listener;

    /**
     * @var TransactionLogger|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $transactionLogger;

    public function setUp()
    {
        $this->transactionLogger = $this->getMockBuilder(TransactionLogger::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->listener = new ResponseListener($this->transactionLogger);
        $this->request = new Request();
        $this->response = new Response();
    }

    public function tearDown()
    {
        $this->listener = null;
        $this->request = null;
        $this->response = null;
        $this->transactionLogger = null;
    }

    public function testNotMasterRequest()
    {
        $event = $this->getFilterResponseEvent($this->request, $this->response, HttpKernelInterface::SUB_REQUEST);
        $res = $this->listener->onKernelResponse($event);
        $this->assertTrue($res === false);
    }

    public function testSetResponse()
    {
        $calls = 0;

        $this->transactionLogger
            ->expects($this->any())
            ->method('setResponse')
            ->willReturnCallback(function () use (&$calls) {
                $calls++;
            });
        ;

        $event = $this->getFilterResponseEvent($this->request, $this->response);
        $this->listener->onKernelResponse($event);

        $this->assertSame(1, $calls);
    }

    protected function getFilterResponseEvent(Request $request, Response $response, int $requestType = HttpKernelInterface::MASTER_REQUEST)
    {
        $mockKernel = $this->getMockForAbstractClass('Symfony\Component\HttpKernel\Kernel', array('', ''));

        return new FilterResponseEvent($mockKernel, $request, $requestType, $response);
    }
}
