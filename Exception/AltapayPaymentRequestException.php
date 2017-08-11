<?php
namespace Loevgaard\DandomainAltapayBundle\Exception;

use Loevgaard\DandomainAltapayBundle\Entity\PaymentInterface;
use Symfony\Component\HttpFoundation\Request;
use Loevgaard\AltaPay\Payload\PaymentRequest as PaymentRequestPayload;

class AltapayPaymentRequestException extends PaymentException
{
    /**
     * @var PaymentRequestPayload
     */
    protected $payload;
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
}