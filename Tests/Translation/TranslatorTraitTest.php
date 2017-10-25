<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Translation;

use Loevgaard\DandomainAltapayBundle\EventListener\ControllerListener;
use Loevgaard\DandomainAltapayBundle\EventListener\ResponseListener;
use Loevgaard\DandomainAltapayBundle\Http\TransactionLogger;
use Loevgaard\DandomainAltapayBundle\Tests\EventListener\Fixture\ControllerWithAnnotation;
use Loevgaard\DandomainAltapayBundle\Tests\Translation\Fixture\UsingTranslator;
use Loevgaard\DandomainAltapayBundle\Translation\TranslatorTrait;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
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
