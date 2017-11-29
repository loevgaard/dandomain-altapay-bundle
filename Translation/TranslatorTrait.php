<?php

namespace Loevgaard\DandomainAltapayBundle\Translation;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Translation\TranslatorInterface;

trait TranslatorTrait
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param ContainerInterface $container
     *
     * @return TranslatorInterface
     */
    private function getTranslator(ContainerInterface $container): TranslatorInterface
    {
        if (!$this->translator) {
            $this->translator = $container->get('translator');
        }

        return $this->translator;
    }
}
