<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\EventListener;

use Doctrine\Common\Annotations\AnnotationReader;
use Loevgaard\DandomainAltapayBundle\EventListener\ControllerListener;
use Loevgaard\DandomainAltapayBundle\Http\TransactionLogger;
use Loevgaard\DandomainAltapayBundle\Tests\EventListener\Fixture\ControllerWithAnnotation;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ControllerListenerTest extends TestCase
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var ControllerListener
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

        $this->listener = new ControllerListener($this->transactionLogger, new AnnotationReader());

        $this->request = new Request();

        // trigger the autoloading of the @LogHttpTransaction annotation
        class_exists('Loevgaard\DandomainAltapayBundle\Annotation\LogHttpTransaction');
    }

    public function tearDown()
    {
        $this->request = null;
        $this->listener = null;
        $this->transactionLogger = null;
    }

    public function testNotMasterRequest()
    {
        $controller = new ControllerWithAnnotation();
        $event = $this->getFilterControllerEvent([$controller, 'foobarAction'], $this->request, HttpKernelInterface::SUB_REQUEST);
        $res = $this->listener->onKernelController($event);
        $this->assertTrue(false === $res);
    }

    public function testControllerNotArray()
    {
        $event = $this->getFilterControllerEvent(function () {}, $this->request);
        $res = $this->listener->onKernelController($event);
        $this->assertTrue(false === $res);
    }

    public function testAnnotation()
    {
        $calls = 0;

        $this->transactionLogger
            ->expects($this->any())
            ->method('setRequest')
            ->willReturnCallback(function () use (&$calls) {
                ++$calls;
            });

        $controller = new ControllerWithAnnotation();
        $event = $this->getFilterControllerEvent([$controller, 'foobarAction'], $this->request);
        $this->listener->onKernelController($event);

        $this->assertSame(1, $calls);
    }

    protected function getFilterControllerEvent($controller, Request $request, int $requestType = HttpKernelInterface::MASTER_REQUEST)
    {
        $mockKernel = $this->getMockForAbstractClass('Symfony\Component\HttpKernel\Kernel', ['', '']);

        return new FilterControllerEvent($mockKernel, $controller, $request, $requestType);
    }
}
