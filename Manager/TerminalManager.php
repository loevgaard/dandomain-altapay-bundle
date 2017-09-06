<?php

namespace Loevgaard\DandomainAltapayBundle\Manager;

use Loevgaard\DandomainAltapayBundle\Entity\TerminalInterface;
use Loevgaard\DandomainAltapayBundle\Synchronizer\TerminalSynchronizer;
use Loevgaard\DandomainFoundationBundle\Manager\Manager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @method TerminalInterface create()
 * @method delete(TerminalInterface $obj)
 * @method update(TerminalInterface $obj, $flush = true)
 */
class TerminalManager extends Manager implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param string $title
     *
     * @return TerminalInterface|null
     */
    public function findTerminalByTitle(string $title, $fetch = false): ?TerminalInterface
    {
        /** @var TerminalInterface $terminal */
        $terminal = $this->getRepository()->findOneBy([
            'title' => $title,
        ]);

        if($fetch) {
            $this->container->get('loevgaard_dandomain_altapay.terminal_synchronizer')->syncAll();
            return $this->findTerminalByTitle($title, false);
        }

        return $terminal;
    }

    /**
     * @param string $slug
     * @param bool $fetch
     *
     * @return TerminalInterface|null
     */
    public function findTerminalBySlug(string $slug, $fetch = false): ?TerminalInterface
    {
        /** @var TerminalInterface $terminal */
        $terminal = $this->getRepository()->findOneBy([
            'slug' => $slug,
        ]);

        if($fetch) {
            $this->container->get('loevgaard_dandomain_altapay.terminal_synchronizer')->syncAll();
            return $this->findTerminalBySlug($slug, false);
        }

        return $terminal;
    }
}
