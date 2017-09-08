<?php

namespace Loevgaard\DandomainAltapayBundle\EventListener;

use Loevgaard\DandomainAltapayBundle\Annotation\LogHttpTransaction;
use Loevgaard\DandomainAltapayBundle\Http\TransactionLogger;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Doctrine\Common\Annotations\Reader;

class ControllerListener
{
    /**
     * @var TransactionLogger
     */
    private $transactionLogger;

    private $reader;

    public function __construct(TransactionLogger $transactionLogger, Reader $reader)
    {
        $this->transactionLogger = $transactionLogger;
        $this->reader = $reader;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if(!$event->isMasterRequest() || !is_array($controller)) {
            return;
        }

        list($class, $method) = $controller;

        $controllerReflection = new \ReflectionObject($class);
        $methodReflection = $controllerReflection->getMethod($method);
        $annotation = $this->reader->getMethodAnnotation($methodReflection, LogHttpTransaction::class);

        if($annotation) {
            $request = $event->getRequest();
            $this->transactionLogger->setRequest($request);
        }
    }
}