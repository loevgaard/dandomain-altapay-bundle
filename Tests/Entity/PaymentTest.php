<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Entity;

use Loevgaard\DandomainAltapayBundle\Entity\Payment;
use PHPUnit\Framework\TestCase;

class PaymentTest extends TestCase
{
    public function testGettersSetters()
    {
        $payment = new Payment();

        $created = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2017-10-01 04:00:00');
        $updated = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2017-10-01 04:00:01');

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
            ->setReservedAmount(250.5)
            ->setCapturedAmount(125.5)
            ->setRefundedAmount(300.4)
            ->setRecurringDefaultAmount(400.9)
            ->setCreatedDate($created)
            ->setUpdatedDate($updated)
            ->setPaymentNature('paymentnature')
            ->setSupportsRefunds(false)
            ->setSupportsRelease(false)
            ->setSupportsMultipleCaptures(true)
            ->setSupportsMultipleRefunds(true)
            ->setFraudRiskScore(13.37)
            ->setFraudExplanation('fraudexplanation')
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
        $this->assertSame(250.5, $payment->getReservedAmount());
        $this->assertSame(125.5, $payment->getCapturedAmount());
        $this->assertSame(300.4, $payment->getRefundedAmount());
        $this->assertSame(400.9, $payment->getRecurringDefaultAmount());
        $this->assertSame($created, $payment->getCreatedDate());
        $this->assertSame($updated, $payment->getUpdatedDate());
        $this->assertSame('paymentnature', $payment->getPaymentNature());
        $this->assertSame(false, $payment->getSupportsRefunds());
        $this->assertSame(false, $payment->getSupportsRelease());
        $this->assertSame(true, $payment->getSupportsMultipleCaptures());
        $this->assertSame(true, $payment->getSupportsMultipleRefunds());
        $this->assertSame(13.37, $payment->getFraudRiskScore());
        $this->assertSame('fraudexplanation', $payment->getFraudExplanation());
    }

    public function testIsCaptureable1()
    {
        $payment = new Payment();

        $payment
            ->setReservedAmount(100)
            ->setCapturedAmount(100)
            ->setRefundedAmount(0)
        ;

        $this->assertSame(false, $payment->isCaptureable());
    }

    public function testIsCaptureable2()
    {
        $payment = new Payment();

        $payment
            ->setReservedAmount(100)
            ->setCapturedAmount(10)
            ->setRefundedAmount(0)
            ->setSupportsMultipleCaptures(false)
        ;

        $this->assertSame(false, $payment->isCaptureable());
    }

    public function testIsCaptureable3()
    {
        $payment = new Payment();

        $payment
            ->setReservedAmount(100)
            ->setCapturedAmount(0)
            ->setRefundedAmount(0)
        ;

        $this->assertSame(true, $payment->isCaptureable());
    }

    public function testIsRefundable1()
    {
        $payment = new Payment();

        $payment
            ->setCapturedAmount(100)
            ->setRefundedAmount(100)
        ;

        $this->assertSame(false, $payment->isRefundable());
    }

    public function testIsRefundable2()
    {
        $payment = new Payment();

        $payment
            ->setCapturedAmount(100)
            ->setRefundedAmount(50)
            ->setSupportsMultipleRefunds(false)
        ;

        $this->assertSame(false, $payment->isRefundable());
    }

    public function testIsRefundable3()
    {
        $payment = new Payment();

        $payment
            ->setCapturedAmount(100)
            ->setRefundedAmount(0)
        ;

        $this->assertSame(true, $payment->isRefundable());
    }
}
