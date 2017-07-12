<?php
namespace Tests\Loevgaard\DandomainAltapayBundle\Entity;

use Loevgaard\DandomainAltapayBundle\Entity\Terminal;
use PHPUnit\Framework\TestCase;

class TerminalTest extends TestCase
{
    public function testGettersSetters()
    {
        $terminal = $this->getTerminal();

        $this->assertNull($terminal->getTitle());
        $this->assertNull($terminal->getSlug());
        $this->assertNull($terminal->getCountry());
        $this->assertNull($terminal->getCurrencies());
        $this->assertNull($terminal->getNatures());

        $terminal
            ->setTitle('title')
            ->setCountry('DK')
            ->setCurrencies(['EUR', 'DKK'])
            ->setNatures(['Nature 1', 'Nature 2'])
        ;

        $this->assertSame('title', $terminal->getTitle());
        $this->assertSame('DK', $terminal->getCountry());
        $this->assertSame(['EUR', 'DKK'], $terminal->getCurrencies());
        $this->assertSame(['Nature 1', 'Nature 2'], $terminal->getNatures());
    }

    public function testUpdateCanonical()
    {
        $terminal = $this->getTerminal();
        $terminal->setTitle('test title !"#(€ EUR');
        $terminal->updateSlug();

        $this->assertEquals('test-title-eur', $terminal->getSlug());
    }

    /**
     * @return Terminal
     */
    public function getTerminal()
    {
        return $this->getMockForAbstractClass(Terminal::class);
    }
}