<?php

namespace Loevgaard\DandomainAltapayBundle\EventListener;

use Loevgaard\DandomainAltapayBundle\Exception\Exception;
use Loevgaard\DandomainAltapayBundle\Exception\PaymentException;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * This exception listener will intercept exceptions throw from this bundle and redirect the user
 * to an error page describing the error.
 */
class ExceptionListener
{
    /**
     * @var EngineInterface
     */
    private $engine;

    public function __construct(EngineInterface $engine)
    {
        $this->engine = $engine;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        // check if exception is part of this bundle
        if (!($exception instanceof Exception)) {
            return false;
        }

        $redirect = '';

        if ($exception instanceof PaymentException) {
            $redirect = $exception->getPayment()->getReferrer();
            if (!$redirect) {
                $redirect = 'http://'.$exception->getPayment()->getCallBackServerUrl();
            }
        }

        $responseText = $this->engine->render('@LoevgaardDandomainAltapay/error/error.html.twig', [
            'error' => $exception->getMessage(),
            'redirect' => $redirect,
        ]);

        $response = new Response();
        $response->setContent($responseText);

        $event->setResponse($response);

        return true;
    }
}
