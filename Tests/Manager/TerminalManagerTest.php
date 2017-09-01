<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Manager;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Loevgaard\DandomainAltapayBundle\Entity\TerminalInterface;
use Loevgaard\DandomainAltapayBundle\Manager\TerminalManager;
use PHPUnit\Framework\TestCase;

class TerminalManagerTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|TerminalManager
     */
    protected $manager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ObjectRepository
     */
    protected $objectRepository;

    protected function setUp()
    {
        $objectManager = $this->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var ManagerRegistry|\PHPUnit_Framework_MockObject_MockObject $managerRegistry */
        $managerRegistry = $this->getMockBuilder(ManagerRegistry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $managerRegistry
            ->expects($this->any())
            ->method('getManagerForClass')
            ->willReturn($objectManager);

        $this->objectRepository = $this->getMockBuilder(ObjectRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $objectManager
            ->expects($this->any())
            ->method('getRepository')
            ->willReturn($this->objectRepository);

        $this->manager = new TerminalManager($managerRegistry, 'Loevgaard\DandomainAltapayBundle\Entity\Terminal');
    }

    public function testFindTerminalByTitle()
    {
        $terminal = $this->getTerminal();

        $this->objectRepository
            ->expects($this->any())
            ->method('findOneBy')
            ->with(['title' => 'terminal'])
            ->willReturn($terminal)
        ;

        $res = $this->manager->findTerminalByTitle('terminal');
        $this->assertInstanceOf(TerminalInterface::class, $res);
    }

    public function testFindTerminalBySlug()
    {
        $terminal = $this->getTerminal();

        $this->objectRepository
            ->expects($this->any())
            ->method('findOneBy')
            ->with(['slug' => 'terminal'])
            ->willReturn($terminal)
        ;

        $res = $this->manager->findTerminalBySlug('terminal');
        $this->assertInstanceOf(TerminalInterface::class, $res);
    }

    /**
     * @return TerminalInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getTerminal()
    {
        return $this->getMockForAbstractClass('Loevgaard\DandomainAltapayBundle\Entity\Terminal');
    }
}
