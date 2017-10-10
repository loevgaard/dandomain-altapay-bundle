<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Entity;

use Loevgaard\DandomainAltapayBundle\Entity\Callback;
use Loevgaard\DandomainAltapayBundle\Entity\CallbackInterface;
use Loevgaard\DandomainAltapayBundle\Entity\Payment;
use PHPUnit\Framework\TestCase;

class CallbackTest extends TestCase
{
    public function testGettersSetters()
    {
        $callback = $this->getCallback();
        $payment = $this->getPayment();

        $callback
            ->setId(1)
            ->setOrderId(2)
            ->setStatus('status')
            ->setPayment($payment)
            ->setRequest('request')
            ->setAmount(100.5)
            ->setTransactionId('trans123')
            ->setCurrency(208)
            ->setLanguage('da')
            ->setAvsCode('avscode')
            ->setAvsText('avstext')
            ->setBlacklistToken('blacklisttoken')
            ->setCardholderMessageMustBeShown(true)
            ->setChecksum('checksum')
            ->setCreditCardToken('credit card token')
            ->setErrorMessage('error message')
            ->setFraudExplanation('fraud explanation')
            ->setFraudRecommendation('fraud recommendation')
            ->setFraudRiskScore(9.5)
            ->setTransactionInfo(['info'])
            ->setMerchantErrorMessage('merchant error')
            ->setType('type')
            ->setPaymentStatus('payment status')
            ->setMaskedCreditCard('masked cc')
            ->setNature('nature')
            ->setRequireCapture(false)
            ->setXml('xml')
        ;

        $this->assertSame(1, $callback->getId());
        $this->assertSame(2, $callback->getOrderId());
        $this->assertSame('status', $callback->getStatus());
        $this->assertSame($payment, $callback->getPayment());
        $this->assertSame('request', $callback->getRequest());
        $this->assertSame(100.5, $callback->getAmount());
        $this->assertSame('trans123', $callback->getTransactionId());
        $this->assertSame(208, $callback->getCurrency());
        $this->assertSame('da', $callback->getLanguage());
        $this->assertSame('avscode', $callback->getAvsCode());
        $this->assertSame('avstext', $callback->getAvsText());
        $this->assertSame('blacklisttoken', $callback->getBlacklistToken());
        $this->assertSame(true, $callback->isCardholderMessageMustBeShown());
        $this->assertSame('checksum', $callback->getChecksum());
        $this->assertSame('credit card token', $callback->getCreditCardToken());
        $this->assertSame('error message', $callback->getErrorMessage());
        $this->assertSame('fraud explanation', $callback->getFraudExplanation());
        $this->assertSame('fraud recommendation', $callback->getFraudRecommendation());
        $this->assertSame(9.5, $callback->getFraudRiskScore());
        $this->assertSame(['info'], $callback->getTransactionInfo());
        $this->assertSame('merchant error', $callback->getMerchantErrorMessage());
        $this->assertSame('type', $callback->getType());
        $this->assertSame('payment status', $callback->getPaymentStatus());
        $this->assertSame('masked cc', $callback->getMaskedCreditCard());
        $this->assertSame('nature', $callback->getNature());
        $this->assertSame(false, $callback->isRequireCapture());
        $this->assertSame('xml', $callback->getXml());
    }

    /**
     * @return CallbackInterface
     */
    public function getCallback()
    {
        return $this->getMockForAbstractClass(Callback::class);
    }

    /**
     * @return Payment
     */
    public function getPayment()
    {
        return $this->getMockForAbstractClass(Payment::class);
    }
}
