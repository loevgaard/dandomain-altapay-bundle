<?php
namespace Loevgaard\DandomainAltapayBundle\Exception;

use Loevgaard\DandomainAltapayBundle\Entity\PaymentInterface;
use Symfony\Component\HttpFoundation\Request;

class PaymentException extends Exception
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var PaymentInterface
     */
    protected $payment;

    /**
     * @param string $message
     * @param Request $request
     * @param PaymentInterface $payment
     * @return PaymentException
     */
    public static function create(string $message, Request $request, PaymentInterface $payment) : PaymentException
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
     * @return PaymentException
     */
    public function setRequest(Request $request) : PaymentException
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return PaymentInterface
     */
    public function getPayment() : PaymentInterface
    {
        return $this->payment;
    }

    /**
     * @param PaymentInterface $payment
     * @return PaymentException
     */
    public function setPayment(PaymentInterface $payment) : PaymentException
    {
        $this->payment = $payment;
        return $this;
    }
}