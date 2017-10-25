<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Translation;

use Loevgaard\DandomainAltapayBundle\Tests\Translation\Fixture\UsingTranslator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Translation\IdentityTranslator;

class TranslatorTraitTest extends TestCase
{
    public function testGetter()
    {
        /** @var ContainerInterface|\PHPUnit_Framework_MockObject_MockObject $container */
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $container->method('get')->willReturn(new IdentityTranslator());

        $obj = new UsingTranslator($container);

        $this->assertInstanceOf(IdentityTranslator::class, $obj->getIt());
    }
}
