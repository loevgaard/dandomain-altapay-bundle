<?php

namespace Loevgaard\DandomainAltapayBundle\Event;

use Loevgaard\DandomainAltapayBundle\Entity\Payment;

class PaymentCreated implements EventInterface
{
    /**
     * @var Payment
     */
    private $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * @return Payment
     */
    public function getPayment(): Payment
    {
        return $this->payment;
    }
}
