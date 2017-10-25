<?php

namespace Loevgaard\DandomainAltapayBundle\PsrHttpMessage;

use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Component\HttpFoundation\Request;

trait DiactorosTrait
{
    /**
     * @param Request $request
     *
     * @return ServerRequestInterface
     */
    private function createPsrRequest(Request $request): ServerRequestInterface
    {
        $psr7Factory = new DiactorosFactory();
        $psrRequest = $psr7Factory->createRequest($request);

        return $psrRequest;
    }
}
