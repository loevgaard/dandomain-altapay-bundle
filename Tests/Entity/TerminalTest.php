<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Entity;

use Loevgaard\DandomainAltapayBundle\Entity\Terminal;
use Loevgaard\DandomainAltapayBundle\Entity\TerminalInterface;
use PHPUnit\Framework\TestCase;

class TerminalTest extends TestCase
{
    public function testGettersSetters()
    {
        $terminal = $this->getTerminal();

        $terminal
            ->setId(1)
            ->setTitle('title')
            ->setSlug('sluuuug')
            ->setCountry('DK')
            ->setCurrencies(['EUR', 'DKK'])
            ->setNatures(['Nature 1', 'Nature 2'])
        ;

        $this->assertSame(1, $terminal->getId());
        $this->assertSame('title', $terminal->getTitle());
        $this->assertSame('sluuuug', $terminal->getSlug());
        $this->assertSame('DK', $terminal->getCountry());
        $this->assertSame(['EUR', 'DKK'], $terminal->getCurrencies());
        $this->assertSame(['Nature 1', 'Nature 2'], $terminal->getNatures());
    }

    public function testUpdateSlug()
    {
        $terminal = $this->getTerminal();
        $terminal->setTitle('test title !"#(€ EUR');
        $terminal->updateSlug();

        $this->assertEquals('test-title-eur', $terminal->getSlug());
    }

    /**
     * @return TerminalInterface
     */
    public function getTerminal()
    {
        return $this->getMockForAbstractClass(Terminal::class);
    }
}
