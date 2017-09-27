<?php

namespace Loevgaard\DandomainAltapayBundle\Manager;

use Loevgaard\DandomainAltapayBundle\Entity\TerminalInterface;
use Loevgaard\DoctrineManager\Manager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TerminalManager extends Manager implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param string $title
     * @param bool   $fetch
     *
     * @return TerminalInterface|null
     */
    public function findTerminalByTitle(string $title, $fetch = false): ?TerminalInterface
    {
        /** @var TerminalInterface $terminal */
        $terminal = $this->getRepository()->findOneBy([
            'title' => $title,
        ]);

        if ($fetch) {
            $this->container->get('loevgaard_dandomain_altapay.terminal_synchronizer')->syncAll();

            return $this->findTerminalByTitle($title, false);
        }

        return $terminal;
    }

    /**
     * @param string $slug
     * @param bool   $fetch
     *
     * @return TerminalInterface|null
     */
    public function findTerminalBySlug(string $slug, $fetch = false): ?TerminalInterface
    {
        /** @var TerminalInterface $terminal */
        $terminal = $this->getRepository()->findOneBy([
            'slug' => $slug,
        ]);

        if ($fetch) {
            $this->container->get('loevgaard_dandomain_altapay.terminal_synchronizer')->syncAll();

            return $this->findTerminalBySlug($slug, false);
        }

        return $terminal;
    }

    /**
     * @return TerminalInterface
     */
    public function create()
    {
        return parent::create();
    }

    /**
     * @param TerminalInterface $obj
     */
    public function delete($obj)
    {
        parent::delete($obj);
    }

    /**
     * @param TerminalInterface $obj
     * @param bool              $flush
     */
    public function update($obj, $flush = true)
    {
        parent::update($obj, $flush);
    }
}
