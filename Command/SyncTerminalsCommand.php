<?php
namespace Loevgaard\DandomainAltapayBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncTerminalsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('loevgaard_dandomain_altapay:sync-terminals')
            ->setDescription('Synchronizes terminals from Altapay to the local database (one way sync)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $terminalManager = $this->getContainer()->get('loevgaard_dandomain_altapay.terminal_manager');
        $altapayClient = $this->getContainer()->get('loevgaard_dandomain_altapay.altapay_client');
        $response = $altapayClient->getTerminals();

        foreach ($response->getTerminals() as $terminal) {
            $entity = $terminalManager->findTerminalByTitle($terminal->getTitle());
            if(!$entity) {
                $entity = $terminalManager->create();
            }
            $entity
                ->setTitle($terminal->getTitle())
                ->setCountry($terminal->getCountry())
                ->setNatures(array_map(function ($val) {
                    return (string)$val;
                }, $terminal->getNatures()))
                ->setCurrencies(array_map(function ($val) {
                    return (string)$val;
                }, $terminal->getCurrencies()))
            ;

            $terminalManager->update($entity);
        }
    }
}