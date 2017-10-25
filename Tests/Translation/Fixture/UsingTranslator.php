<?php
namespace Loevgaard\DandomainAltapayBundle\Tests\Translation\Fixture;

use Loevgaard\DandomainAltapayBundle\Translation\TranslatorTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Translation\IdentityTranslator;

class UsingTranslator
{
    use TranslatorTrait;

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return IdentityTranslator
     */
    public function getIt()
    {
        return $this->getTranslator();
    }
}