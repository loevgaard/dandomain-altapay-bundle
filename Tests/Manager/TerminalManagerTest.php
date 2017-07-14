<?php
namespace Tests\Loevgaard\DandomainAltapayBundle\Manager;

use Loevgaard\DandomainAltapayBundle\Manager\TerminalManager;
use PHPUnit\Framework\TestCase;

class TerminalManagerTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|TerminalManager
     */
    protected $manager;

    protected function setUp()
    {
        $this->manager = $this->getMockBuilder('Loevgaard\DandomainAltapayBundle\Manager\TerminalManager')
            ->disableOriginalConstructor()
            ->setMethods(['findTerminalByTitle'])
            ->getMock();
    }

    public function testFindTerminalByTitle()
    {
        $terminal = $this->getMockForAbstractClass('Loevgaard\DandomainAltapayBundle\Entity\Terminal');

        $this->manager->expects($this->once())
            ->method('findTerminalByTitle')
            ->with($this->equalTo('terminal'))
            ->willReturn($terminal)
        ;
        $this->manager->findTerminalByTitle('terminal');
    }
}