<?php

namespace Loevgaard\DandomainAltapayBundle\Translation;

use Symfony\Component\Translation\TranslatorInterface;

trait TranslatorTrait
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @return TranslatorInterface
     */
    private function getTranslator(): TranslatorInterface
    {
        if (!$this->translator) {
            $this->translator = $this->container->get('translator');
        }

        return $this->translator;
    }
}
