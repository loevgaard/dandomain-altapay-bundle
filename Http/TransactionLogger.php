<?php

namespace Loevgaard\DandomainAltapayBundle\Http;

use Loevgaard\DandomainAltapayBundle\Entity\HttpTransaction;
use Loevgaard\DandomainAltapayBundle\Entity\HttpTransactionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TransactionLogger
{
    /**
     * @var array
     */
    private $transactions;

    /**
     * @var HttpTransactionRepository
     */
    private $httpTransactionRepository;

    public function __construct(HttpTransactionRepository $httpTransactionRepository)
    {
        $this->transactions = [];
        $this->httpTransactionRepository = $httpTransactionRepository;
    }

    public function setRequest(Request $request)
    {
        $requestHash = $this->hashRequest($request);

        $this->transactions[$requestHash] = [
            'request' => $request,
            'response' => null,
        ];
    }

    public function setResponse(Request $request, Response $response)
    {
        $requestHash = $this->hashRequest($request);

        // if the request hash is set it means we have a corresponding request
        // which means we want to log it
        if (isset($this->transactions[$requestHash])) {
            $this->transactions[$requestHash]['response'] = $response;
        }
    }

    /**
     * Flushes the HTTP transaction log.
     */
    public function flush()
    {
        foreach ($this->transactions as $transaction) {
            $entity = new HttpTransaction();
            $entity->setRequest($transaction['request'])
                ->setResponse((string) $transaction['response']);

            $this->httpTransactionRepository->save($entity);
        }
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    private function hashRequest(Request $request)
    {
        return spl_object_hash($request);
    }
}
