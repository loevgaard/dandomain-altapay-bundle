<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Entity;

use Loevgaard\DandomainAltapayBundle\Entity\Payment;
use Loevgaard\DandomainAltapayBundle\Entity\PaymentLine;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;

class PaymentTest extends TestCase
{
    public function testGettersSetters()
    {
        $payment = new Payment();

        $created = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2017-10-01 04:00:00');
        $updated = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2017-10-01 04:00:01');

        $reservedAmount = new Money(25050, new Currency('DKK'));
        $capturedAmount = new Money(30020, new Currency('DKK'));
        $refundedAmount = new Money(40040, new Currency('DKK'));
        $recurringDefaultAmount = new Money(50060, new Currency('DKK'));
        $totalAmount = new Money(60060, new Currency('DKK'));
        $shippingFee = new Money(70080, new Currency('DKK'));
        $paymentFee = new Money(80080, new Currency('DKK'));

        $payment
            ->setId(1)
            ->setAltapayId('altapayid')
            ->setCardStatus('cardstatus')
            ->setCreditCardToken('cctoken')
            ->setCreditCardMaskedPan('ccmaskedpan')
            ->setThreeDSecureResult('3dres')
            ->setLiableForChargeback('liable')
            ->setBlacklistToken('blacklisttoken')
            ->setShop('shop')
            ->setTerminal('terminal')
            ->setTransactionStatus('transactionstatus')
            ->setReasonCode('reasoncode')
            ->setMerchantCurrency(208)
            ->setMerchantCurrencyAlpha('DKK')
            ->setCardHolderCurrency(700)
            ->setCardHolderCurrencyAlpha('EUR')
            ->setReservedAmount($reservedAmount)
            ->setCapturedAmount($capturedAmount)
            ->setRefundedAmount($refundedAmount)
            ->setRecurringDefaultAmount($recurringDefaultAmount)
            ->setCreatedDate($created)
            ->setUpdatedDate($updated)
            ->setPaymentNature('paymentnature')
            ->setSupportsRefunds(false)
            ->setSupportsRelease(false)
            ->setSupportsMultipleCaptures(true)
            ->setSupportsMultipleRefunds(true)
            ->setFraudRiskScore(13.37)
            ->setFraudExplanation('fraudexplanation')
            ->setTotalAmount($totalAmount)
            ->setShippingFee($shippingFee)
            ->setPaymentFee($paymentFee)
        ;

        $this->assertSame(1, $payment->getId());
        $this->assertSame('altapayid', $payment->getAltapayId());
        $this->assertSame('cardstatus', $payment->getCardStatus());
        $this->assertSame('cctoken', $payment->getCreditCardToken());
        $this->assertSame('ccmaskedpan', $payment->getCreditCardMaskedPan());
        $this->assertSame('3dres', $payment->getThreeDSecureResult());
        $this->assertSame('liable', $payment->getLiableForChargeback());
        $this->assertSame('blacklisttoken', $payment->getBlacklistToken());
        $this->assertSame('shop', $payment->getShop());
        $this->assertSame('terminal', $payment->getTerminal());
        $this->assertSame('transactionstatus', $payment->getTransactionStatus());
        $this->assertSame('reasoncode', $payment->getReasonCode());
        $this->assertSame(208, $payment->getMerchantCurrency());
        $this->assertSame('DKK', $payment->getMerchantCurrencyAlpha());
        $this->assertSame(700, $payment->getCardHolderCurrency());
        $this->assertSame('EUR', $payment->getCardHolderCurrencyAlpha());
        $this->assertEquals($reservedAmount, $payment->getReservedAmount());
        $this->assertEquals($capturedAmount, $payment->getCapturedAmount());
        $this->assertEquals($refundedAmount, $payment->getRefundedAmount());
        $this->assertEquals($recurringDefaultAmount, $payment->getRecurringDefaultAmount());
        $this->assertSame($created, $payment->getCreatedDate());
        $this->assertSame($updated, $payment->getUpdatedDate());
        $this->assertSame('paymentnature', $payment->getPaymentNature());
        $this->assertSame(false, $payment->getSupportsRefunds());
        $this->assertSame(false, $payment->getSupportsRelease());
        $this->assertSame(true, $payment->getSupportsMultipleCaptures());
        $this->assertSame(true, $payment->getSupportsMultipleRefunds());
        $this->assertSame(13.37, $payment->getFraudRiskScore());
        $this->assertSame('fraudexplanation', $payment->getFraudExplanation());
        $this->assertEquals($totalAmount, $payment->getTotalAmount());
        $this->assertEquals($shippingFee, $payment->getShippingFee());
        $this->assertEquals($paymentFee, $payment->getPaymentFee());
    }

    public function testCurrencyIsNull()
    {
        $payment = new Payment();
        $this->assertSame(null, $payment->getTotalAmount());
    }

    public function testCreatePaymentLine()
    {
        $price = new Money(100, new Currency('DKK'));
        $paymentLine = Payment::createPaymentLine('product_number', 'name', 1, $price, 25);

        $this->assertInstanceOf(PaymentLine::class, $paymentLine);
        $this->assertSame('product_number', $paymentLine->getProductNumber());
        $this->assertSame('name', $paymentLine->getName());
        $this->assertSame(1, $paymentLine->getQuantity());
        $this->assertEquals($price, $paymentLine->getPrice());
        $this->assertSame(25, $paymentLine->getVat());
    }

    public function testIsCaptureable1()
    {
        $payment = new Payment();

        $payment
            ->setReservedAmount(new Money(10000, new Currency('DKK')))
            ->setCapturedAmount(new Money(10000, new Currency('DKK')))
            ->setRefundedAmount(new Money(0, new Currency('DKK')))
            ->setCurrencySymbol('DKK')
        ;

        $this->assertSame(false, $payment->isCaptureable());
    }

    public function testIsCaptureable2()
    {
        $payment = new Payment();

        $payment
            ->setReservedAmount(new Money(10000, new Currency('DKK')))
            ->setCapturedAmount(new Money(1000, new Currency('DKK')))
            ->setRefundedAmount(new Money(0, new Currency('DKK')))
            ->setSupportsMultipleCaptures(false)
            ->setCurrencySymbol('DKK')
        ;

        $this->assertSame(false, $payment->isCaptureable());
    }

    public function testIsCaptureable3()
    {
        $payment = new Payment();

        $payment
            ->setReservedAmount(new Money(10000, new Currency('DKK')))
            ->setCapturedAmount(new Money(0, new Currency('DKK')))
            ->setRefundedAmount(new Money(0, new Currency('DKK')))
            ->setCurrencySymbol('DKK')
        ;

        $this->assertSame(true, $payment->isCaptureable());
    }

    public function testIsRefundable1()
    {
        $payment = new Payment();

        $payment
            ->setCapturedAmount(new Money(10000, new Currency('DKK')))
            ->setRefundedAmount(new Money(10000, new Currency('DKK')))
            ->setCurrencySymbol('DKK')
        ;

        $this->assertSame(false, $payment->isRefundable());
    }

    public function testIsRefundable2()
    {
        $payment = new Payment();

        $payment
            ->setCapturedAmount(new Money(10000, new Currency('DKK')))
            ->setRefundedAmount(new Money(5000, new Currency('DKK')))
            ->setSupportsMultipleRefunds(false)
            ->setCurrencySymbol('DKK')
        ;

        $this->assertSame(false, $payment->isRefundable());
    }

    public function testIsRefundable3()
    {
        $payment = new Payment();

        $payment
            ->setCapturedAmount(new Money(10000, new Currency('DKK')))
            ->setRefundedAmount(new Money(0, new Currency('DKK')))
            ->setCurrencySymbol('DKK')
        ;

        $this->assertSame(true, $payment->isRefundable());
    }
}
