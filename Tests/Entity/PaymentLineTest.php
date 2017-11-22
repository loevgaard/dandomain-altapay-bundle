<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Entity;

use Loevgaard\DandomainAltapayBundle\Entity\PaymentLine;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;

class PaymentLineTest extends TestCase
{
    public function testGettersSetters()
    {
        $price = new Money(1, new Currency('DKK'));
        $paymentLine = new PaymentLine('product_number', 'name', 1, $price, 25);
        $paymentLine->setId(1);

        $this->assertSame(1, $paymentLine->getId());
        $this->assertEquals($price, $paymentLine->getPrice());
    }

    public function testZeroAmount()
    {
        $price = new Money(0, new Currency('DKK'));
        $paymentLine = new PaymentLine('product_number', 'name', 1, $price, 25);
        $paymentLine->setId(1);

        $this->assertEquals($price, $paymentLine->getPrice());
    }
}
