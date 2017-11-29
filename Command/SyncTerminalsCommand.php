<?php

namespace Loevgaard\DandomainAltapayBundle\Command;

use Loevgaard\DandomainAltapayBundle\Synchronizer\TerminalSynchronizer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncTerminalsCommand extends ContainerAwareCommand
{
    /**
     * @var TerminalSynchronizer
     */
    protected $terminalSynchronizer;

    public function __construct(TerminalSynchronizer $terminalSynchronizer)
    {
        $this->terminalSynchronizer = $terminalSynchronizer;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('loevgaard:dandomain:altapay:sync-terminals')
            ->setDescription('Synchronizes terminals from Altapay to the local database (one way sync)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->terminalSynchronizer->syncAll();
    }
}
