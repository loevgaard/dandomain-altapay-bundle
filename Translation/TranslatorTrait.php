<?php

namespace Loevgaard\DandomainAltapayBundle\Translation;

use Symfony\Component\Translation\IdentityTranslator;

trait TranslatorTrait
{
    /**
     * @var IdentityTranslator
     */
    private $translator;

    /**
     * @return IdentityTranslator
     */
    private function getTranslator() : IdentityTranslator
    {
        if (!$this->translator) {
            $this->translator = $this->container->get('translator');
        }

        return $this->translator;
    }
}
