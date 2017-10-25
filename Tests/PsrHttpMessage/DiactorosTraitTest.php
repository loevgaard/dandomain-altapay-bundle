<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\PsrHttpMessage;

use Loevgaard\DandomainAltapayBundle\Tests\PsrHttpMessage\Fixture\UsingDiactoros;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Request;

class DiactorosTraitTest extends TestCase
{
    public function testCreatePsrRequest()
    {
        $obj = new UsingDiactoros();

        $request = Request::create('/uri');

        $this->assertInstanceOf(ServerRequestInterface::class, $obj->createIt($request));
    }
}
