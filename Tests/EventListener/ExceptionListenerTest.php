<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\EventListener;

use Loevgaard\DandomainAltapayBundle\EventListener\ExceptionListener;
use Loevgaard\DandomainAltapayBundle\Exception\Exception;
use Loevgaard\DandomainAltapayBundle\Exception\PaymentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Loevgaard\DandomainAltapayBundle\Entity\Payment;

class ExceptionListenerTest extends TestCase
{
    /**
     * @var EngineInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $engine;

    /**
     * @var ExceptionListener
     */
    protected $listener;

    /**
     * @var Request
     */
    protected $request;

    public function setUp()
    {
        $this->engine = $this->getMockForAbstractClass(EngineInterface::class);

        // the engines render method will always return 'response' concatenated with the redirect variable
        $this->engine
            ->expects($this->any())
            ->method('render')
            ->willReturnCallback(function($template, $parameters) {
                return 'response'.$parameters['redirect'];
            });
        ;

        $this->listener = new ExceptionListener($this->engine);
        $this->request = new Request();
    }

    public function tearDown()
    {
        $this->engine = null;
        $this->listener = null;
    }

    public function testNotBundleException()
    {
        $event = $this->getGetResponseForExceptionEvent($this->request, new \Exception());
        $res = $this->listener->onKernelException($event);
        $this->assertTrue($res === false);
    }

    public function testNormalException()
    {
        $exception = $this->getMockForAbstractClass(Exception::class);
        $event = $this->getGetResponseForExceptionEvent($this->request, $exception);
        $this->listener->onKernelException($event);

        $this->assertSame('response', $event->getResponse()->getContent());
    }

    public function testPaymentExceptionWithReferrer()
    {
        $payment = $this->getMockForAbstractClass(Payment::class);
        $payment->setReferrer('referrer');

        $exception = PaymentException::create('exception', $this->request, $payment);

        $event = $this->getGetResponseForExceptionEvent($this->request, $exception);
        $this->listener->onKernelException($event);

        $this->assertSame('responsereferrer', $event->getResponse()->getContent());
    }

    public function testPaymentExceptionWithCallbackServerUrl()
    {
        $payment = $this->getMockForAbstractClass(Payment::class);
        $payment->setCallBackServerUrl('example.com');

        $exception = PaymentException::create('exception', $this->request, $payment);

        $event = $this->getGetResponseForExceptionEvent($this->request, $exception);
        $this->listener->onKernelException($event);

        $this->assertSame('responsehttp://example.com', $event->getResponse()->getContent());
    }

    protected function getGetResponseForExceptionEvent(Request $request, \Exception $e)
    {
        $mockKernel = $this->getMockForAbstractClass('Symfony\Component\HttpKernel\Kernel', array('', ''));

        return new GetResponseForExceptionEvent($mockKernel, $request, HttpKernelInterface::MASTER_REQUEST, $e);
    }
}
