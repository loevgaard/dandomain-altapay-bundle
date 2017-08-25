<?php
namespace Loevgaard\DandomainAltapayBundle\Exception;

use Loevgaard\DandomainAltapayBundle\Entity\PaymentInterface;
use Loevgaard\DandomainFoundationBundle\Model\Payment;
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
     * @param Payment $payment
     * @return PaymentException
     */
    public static function create(string $message, Request $request, Payment $payment) : PaymentException
    {
        $e = new static($message);
        $e->setRequest($request);
        $e->setPayment($payment);
        return $e;
    }
}