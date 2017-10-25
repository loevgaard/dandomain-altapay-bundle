<?php

namespace Loevgaard\DandomainAltapayBundle\EventListener;

use Loevgaard\DandomainAltapayBundle\Http\TransactionLogger;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class ResponseListener
{
    /**
     * @var TransactionLogger
     */
    private $transactionLogger;

    public function __construct(TransactionLogger $transactionLogger)
    {
        $this->transactionLogger = $transactionLogger;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return false;
        }

        $this->transactionLogger->setResponse($event->getRequest(), $event->getResponse());
        $this->transactionLogger->flush();
    }
}
