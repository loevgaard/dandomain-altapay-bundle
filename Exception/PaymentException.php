<?php

namespace Loevgaard\DandomainAltapayBundle\Exception;

use Loevgaard\DandomainAltapayBundle\Entity\Payment;
use Symfony\Component\HttpFoundation\Request;

class PaymentException extends Exception
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Payment
     */
    protected $payment;

    /**
     * @param string  $message
     * @param Request $request
     * @param Payment $payment
     *
     * @return PaymentException
     */
    public static function create(string $message, Request $request, Payment $payment): PaymentException
    {
        $e = new static($message);
        $e->setRequest($request);
        $e->setPayment($payment);

        return $e;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @param Request $request
     *
     * @return PaymentException
     */
    public function setRequest(Request $request): PaymentException
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return Payment
     */
    public function getPayment(): Payment
    {
        return $this->payment;
    }

    /**
     * @param Payment $payment
     *
     * @return PaymentException
     */
    public function setPayment(Payment $payment): PaymentException
    {
        $this->payment = $payment;

        return $this;
    }
}
