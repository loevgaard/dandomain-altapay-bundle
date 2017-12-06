<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Command;

use Loevgaard\AltaPay\Client\Client;
use Loevgaard\DandomainAltapayBundle\Command\SyncTerminalsCommand;
use Loevgaard\DandomainAltapayBundle\Entity\TerminalRepository;
use Loevgaard\DandomainAltapayBundle\Synchronizer\TerminalSynchronizer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class SyncTerminalsCommandTest extends TestCase
{
    public function testExecute()
    {
        $terminalRepository = $this->createMock(TerminalRepository::class);
        $altapayClient = $this->createMock(Client::class);
        $terminalSynchronizer = new TerminalSynchronizer($terminalRepository, $altapayClient);

        $command = new SyncTerminalsCommand($terminalSynchronizer);

        $application = new Application();
        $application->setAutoExit(false);
        $application->add($command);

        $command = $application->find('loevgaard:dandomain:altapay:sync-terminals');
        $commandTester = new CommandTester($command);
        $exitCode = $commandTester->execute([
            'command' => $command->getName(),
        ]);

        $this->assertSame(0, $exitCode, 'Returns 0 in case of success');
    }
}
