<?php

namespace Loevgaard\DandomainAltapayBundle\Manager;

use Loevgaard\DandomainAltapayBundle\Entity\TerminalInterface;
use Loevgaard\DandomainFoundationBundle\Manager\Manager;

/**
 * @method TerminalInterface create()
 * @method delete(TerminalInterface $obj)
 * @method update(TerminalInterface $obj, $flush = true)
 */
class TerminalManager extends Manager
{
    /**
     * @param string $title
     *
     * @return TerminalInterface|null
     */
    public function findTerminalByTitle(string $title): ?TerminalInterface
    {
        /** @var TerminalInterface $terminal */
        $terminal = $this->getRepository()->findOneBy([
            'title' => $title,
        ]);

        return $terminal;
    }

    /**
     * @param string $slug
     *
     * @return TerminalInterface|null
     */
    public function findTerminalBySlug(string $slug): ?TerminalInterface
    {
        /** @var TerminalInterface $terminal */
        $terminal = $this->getRepository()->findOneBy([
            'slug' => $slug,
        ]);

        return $terminal;
    }
}
