<?php

namespace Loevgaard\DandomainAltapayBundle\Synchronizer;

use Loevgaard\AltaPay\Client as AltapayClient;
use Loevgaard\DandomainAltapayBundle\Manager\TerminalManager;

class TerminalSynchronizer
{
    /**
     * @var TerminalManager
     */
    protected $terminalManager;

    /**
     * @var AltapayClient
     */
    protected $altapayClient;

    public function __construct(TerminalManager $terminalManager, AltapayClient $altapayClient)
    {
        $this->terminalManager = $terminalManager;
        $this->altapayClient = $altapayClient;
    }

    public function syncAll()
    {
        $response = $this->altapayClient->getTerminals();

        foreach ($response->getTerminals() as $terminal) {
            $entity = $this->terminalManager->findTerminalByTitle($terminal->getTitle());
            if (!$entity) {
                $entity = $this->terminalManager->create();
            }
            $entity
                ->setTitle($terminal->getTitle())
                ->setCountry($terminal->getCountry())
                ->setNatures(array_map(function ($val) {
                    return (string) $val;
                }, $terminal->getNatures()))
                ->setCurrencies(array_map(function ($val) {
                    return (string) $val;
                }, $terminal->getCurrencies()))
            ;

            $this->terminalManager->update($entity);
        }
    }
}
