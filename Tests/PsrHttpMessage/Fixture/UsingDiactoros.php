<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\PsrHttpMessage\Fixture;

use Loevgaard\DandomainAltapayBundle\PsrHttpMessage\DiactorosTrait;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Request;

class UsingDiactoros
{
    use DiactorosTrait;

    /**
     * @param Request $request
     *
     * @return ServerRequestInterface
     */
    public function createIt(Request $request)
    {
        return $this->createPsrRequest($request);
    }
}
