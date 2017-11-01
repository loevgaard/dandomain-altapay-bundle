<?php

namespace Loevgaard\DandomainAltapayBundle\Synchronizer;

use Loevgaard\AltaPay\Client as AltapayClient;
use Loevgaard\DandomainAltapayBundle\Entity\Terminal;
use Loevgaard\DandomainAltapayBundle\Entity\TerminalRepository;

class TerminalSynchronizer
{
    /**
     * @var TerminalRepository
     */
    protected $terminalRepository;

    /**
     * @var AltapayClient
     */
    protected $altapayClient;

    public function __construct(TerminalRepository $terminalRepository, AltapayClient $altapayClient)
    {
        $this->terminalRepository = $terminalRepository;
        $this->altapayClient = $altapayClient;
    }

    public function syncAll()
    {
        $response = $this->altapayClient->getTerminals();

        foreach ($response->getTerminals() as $terminal) {
            $entity = $this->terminalRepository->findTerminalByTitle($terminal->getTitle());
            if (!$entity) {
                $entity = new Terminal();
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

            $this->terminalRepository->save($entity);
        }
    }
}
