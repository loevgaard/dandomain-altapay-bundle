<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Event;

use Loevgaard\DandomainAltapayBundle\Entity\Payment;
use Loevgaard\DandomainAltapayBundle\Event\PaymentCreated;
use PHPUnit\Framework\TestCase;

final class PaymentCreatedTest extends TestCase
{
    public function testGetter()
    {
        $payment = new Payment();
        $event = new PaymentCreated($payment);

        $this->assertSame($payment, $event->getPayment());
    }
}
