<?php

namespace Loevgaard\DandomainAltapayBundle\Http;

use Loevgaard\DandomainAltapayBundle\Manager\HttpTransactionManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TransactionLogger
{
    /**
     * @var array
     */
    private $transactions;

    /**
     * @var HttpTransactionManager
     */
    private $httpTransactionManager;

    public function __construct(HttpTransactionManager $httpTransactionManager)
    {
        $this->transactions = [];
        $this->httpTransactionManager = $httpTransactionManager;
    }

    public function setRequest(Request $request)
    {
        $requestHash = $this->hashRequest($request);

        $this->transactions[$requestHash] = [
            'request' => $request,
            'response' => null
        ];
    }

    public function setResponse(Request $request, Response $response)
    {
        $requestHash = $this->hashRequest($request);

        // if the request hash is not set it means we do not have a corresponding request
        // which means we do not want to log it
        if(!isset($this->transactions[$requestHash])) {
            return false;
        }

        $this->transactions[$requestHash]['response'] = $response;
    }

    /**
     * Flushes the HTTP transaction log
     */
    public function flush()
    {
        foreach ($this->transactions as $transaction) {
            $entity = $this->httpTransactionManager->create();
            $entity->setRequest($transaction['request'])
                ->setResponse((string)$transaction['response']);

            $this->httpTransactionManager->update($entity);
        }
    }

    /**
     * @param Request $request
     * @return string
     */
    private function hashRequest(Request $request)
    {
        return spl_object_hash($request);
    }
}