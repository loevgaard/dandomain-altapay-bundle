<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Exception;

use Loevgaard\DandomainAltapayBundle\Entity\Payment;
use Loevgaard\DandomainAltapayBundle\Exception\PaymentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class PaymentExceptionTest extends TestCase
{
    public function testGettersSetters()
    {
        $message = 'message';
        $request = Request::create('/test');
        $payment = $this->getMockForAbstractClass(Payment::class);
        $e = PaymentException::create($message, $request, $payment);

        $this->assertSame($message, $e->getMessage());
        $this->assertSame($request, $e->getRequest());
        $this->assertSame($payment, $e->getPayment());
    }
}
