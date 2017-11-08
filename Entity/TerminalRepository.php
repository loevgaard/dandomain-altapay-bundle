<?php

namespace Loevgaard\DandomainAltapayBundle\Entity;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class TerminalRepository extends EntityRepository implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @param string $title
     * @param bool   $fetch
     *
     * @return Terminal|null
     */
    public function findTerminalByTitle(string $title, $fetch = false): ?Terminal
    {
        /** @var Terminal $terminal */
        $terminal = $this->findOneBy([
            'title' => $title,
        ]);

        if (!$terminal && $fetch) {
            $this->sync();

            return $this->findTerminalByTitle($title, false);
        }

        return $terminal;
    }

    /**
     * @param string $slug
     * @param bool   $fetch
     *
     * @return Terminal|null
     */
    public function findTerminalBySlug(string $slug, $fetch = false): ?Terminal
    {
        /** @var Terminal $terminal */
        $terminal = $this->findOneBy([
            'slug' => $slug,
        ]);

        if (!$terminal && $fetch) {
            $this->sync();

            return $this->findTerminalBySlug($slug, false);
        }

        return $terminal;
    }

    private function sync()
    {
        $this->container->get('loevgaard_dandomain_altapay.terminal_synchronizer')->syncAll();
    }
}
