<?php

namespace Loevgaard\DandomainAltapayBundle\Entity;

use Symfony\Component\HttpFoundation\Request;

interface HttpTransactionInterface
{
    /**
     * @return string
     */
    public function getIp(): string;

    /**
     * @param string $ip
     *
     * @return HttpTransactionInterface
     */
    public function setIp(string $ip): HttpTransactionInterface;

    /**
     * @return string
     */
    public function getRequest(): string;

    /**
     * @param string|Request $request
     *
     * @return HttpTransactionInterface
     */
    public function setRequest($request): HttpTransactionInterface;

    /**
     * @return string
     */
    public function getResponse(): string;

    /**
     * @param string $response
     *
     * @return HttpTransactionInterface
     */
    public function setResponse(string $response): HttpTransactionInterface;
}
