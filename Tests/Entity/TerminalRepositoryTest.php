<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Entity;

use Loevgaard\DandomainAltapayBundle\Entity\Terminal;
use Loevgaard\DandomainAltapayBundle\Entity\TerminalRepository;
use Loevgaard\DandomainAltapayBundle\Synchronizer\TerminalSynchronizer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TerminalRepositoryTest extends TestCase
{
    public function testReturnNull()
    {
        $terminalRepository = $this->getTerminalRepository();

        $terminalRepository
            ->method('findOneBy')
            ->willReturn(null);

        $this->assertSame(null, $terminalRepository->findTerminalBySlug('slug'));
    }

    public function testFindTerminalByTitle()
    {
        $terminalRepository = $this->getTerminalRepository();

        $obj = new Terminal();
        $terminalRepository
            ->method('findOneBy')
            ->willReturn($obj);

        $this->assertSame($obj, $terminalRepository->findTerminalByTitle('title'));
    }

    public function testFindTerminalByTitleWithFetch()
    {
        $terminalRepository = $this->getTerminalRepository();

        $obj = new Terminal();
        $terminalRepository
            ->method('findOneBy')
            ->will($this->onConsecutiveCalls(null, $obj));

        $this->assertSame($obj, $terminalRepository->findTerminalByTitle('title', true));
    }

    public function testFindTerminalBySlug()
    {
        $terminalRepository = $this->getTerminalRepository();

        $obj = new Terminal();
        $terminalRepository
            ->method('findOneBy')
            ->willReturn($obj);

        $this->assertSame($obj, $terminalRepository->findTerminalBySlug('slug'));
    }

    public function testFindTerminalBySlugWithFetch()
    {
        $terminalRepository = $this->getTerminalRepository();

        $obj = new Terminal();
        $terminalRepository
            ->method('findOneBy')
            ->will($this->onConsecutiveCalls(null, $obj));

        $this->assertSame($obj, $terminalRepository->findTerminalBySlug('slug', true));
    }

    /**
     * @return TerminalRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getTerminalRepository()
    {
        /** @var TerminalRepository|\PHPUnit_Framework_MockObject_MockObject $terminalRepository */
        $terminalRepository = $this->getMockBuilder(TerminalRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findOneBy'])
            ->getMock();

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $terminalSynchronizer = $this->getMockBuilder(TerminalSynchronizer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $terminalSynchronizer
            ->method('syncAll')
            ->willReturn(null);

        // the only get call we do is a call to retrieve the terminal synchronizer
        $container
            ->method('get')
            ->willReturn($terminalSynchronizer);

        $terminalRepository->setContainer($container);

        return $terminalRepository;
    }
}
