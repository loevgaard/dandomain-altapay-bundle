<?php

namespace Loevgaard\DandomainAltapayBundle\Translation;

use Symfony\Component\Translation\IdentityTranslator;

trait TranslatorTrait
{
    /**
     * @var IdentityTranslator
     */
    private $translator;

    private function trans(string $message, array $params = []): string
    {
        if (!$this->translator) {
            $this->translator = $this->container->get('translator');
        }

        return $this->translator->trans($message, $params, 'LoevgaardDandomainAltapayBundle');
    }
}
