<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Handler;

use Loevgaard\AltaPay\Client\Client;
use Loevgaard\AltaPay\Entity\Transaction;
use Loevgaard\AltaPay\Payload\OrderLine;
use Loevgaard\AltaPay\Payload\RefundCapturedReservation as RefundCapturedReservationPayload;
use Loevgaard\AltaPay\Response\CaptureReservation as CaptureReservationResponse;
use Loevgaard\AltaPay\Response\RefundCapturedReservation as RefundCapturedReservationResponse;
use Loevgaard\DandomainAltapayBundle\Entity\Payment;
use Loevgaard\DandomainAltapayBundle\Entity\PaymentLine;
use Loevgaard\DandomainAltapayBundle\Entity\PaymentRepository;
use Loevgaard\DandomainAltapayBundle\Handler\PaymentHandler;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;

final class PaymentHandlerTest extends TestCase
{
    public function testCapture()
    {
        $payment = $this->getPayment();
        $payment->setAltapayId('altapayid');

        $altapayClient = $this->getAltapayClient();
        $altapayClient
            ->method('captureReservation')
            ->willReturnCallback(function () {
                $response = $this->getMockBuilder(CaptureReservationResponse::class)
                    ->disableOriginalConstructor()
                    ->getMock()
                ;
                $response->method('isSuccessful')->willReturn(true);

                return $response;
            })
        ;

        $paymentHandler = $this->getPaymentHandler($altapayClient);
        $res = $paymentHandler->capture($payment, new Money(9995, new Currency('DKK')));

        $this->assertInstanceOf(CaptureReservationResponse::class, $res);
    }

    public function testRefund1()
    {
        $amount = null;
        $orderLines = [];

        $altapayClient = $this->getAltapayClient();
        $altapayClient
            ->expects($this->any())
            ->method('refundCapturedReservation')
            ->willReturnCallback(function ($val) use (&$amount, &$orderLines) {
                /** @var RefundCapturedReservationPayload $val */
                $amount = $val->getAmount();
                $orderLines = $val->getOrderLines();

                $response = $this->getMockBuilder(RefundCapturedReservationResponse::class)
                    ->disableOriginalConstructor()
                    ->getMock()
                ;

                return $response;
            })
        ;

        $payment = $this->getPayment();
        $payment->setAltapayId('altapayid');

        $paymentHandler = $this->getPaymentHandler($altapayClient);
        $paymentHandler->refund($payment);

        $this->assertSame(null, $amount);
        $this->assertSame([], $orderLines);
    }

    public function testRefund2()
    {
        $amount = null;
        $orderLines = [];

        $altapayClient = $this->getAltapayClient();
        $altapayClient
            ->expects($this->any())
            ->method('refundCapturedReservation')
            ->willReturnCallback(function ($val) use (&$amount, &$orderLines) {
                /** @var RefundCapturedReservationPayload $val */
                $amount = $val->getAmount();
                $orderLines = $val->getOrderLines();

                $response = $this->getMockBuilder(RefundCapturedReservationResponse::class)
                    ->disableOriginalConstructor()
                    ->getMock()
                ;
                $response->method('isSuccessful')->willReturn(true);

                $transaction = new Transaction();
                $response->method('getTransactions')->willReturn([$transaction]);

                return $response;
            })
        ;

        $payment = $this->getPayment();
        $payment->setAltapayId('altapayid');

        $paymentHandler = $this->getPaymentHandler($altapayClient);
        $paymentHandler->refund($payment, new Money(10055, new Currency('DKK')));

        $this->assertEquals(new Money(10055, new Currency('DKK')), $amount);
        $this->assertSame([], $orderLines);
    }

    public function testRefundAmountMatchesPaymentLineAmount()
    {
        $amount = null;
        $orderLines = [];

        $altapayClient = $this->getAltapayClient();
        $altapayClient
            ->expects($this->any())
            ->method('refundCapturedReservation')
            ->willReturnCallback(function ($val) use (&$amount, &$orderLines) {
                /** @var RefundCapturedReservationPayload $val */
                $amount = $val->getAmount();
                $orderLines = $val->getOrderLines();

                $response = $this->getMockBuilder(RefundCapturedReservationResponse::class)
                    ->disableOriginalConstructor()
                    ->getMock()
                ;

                return $response;
            })
        ;

        $payment = $this->getPayment();
        $payment->setAltapayId('altapayid');

        /** @var PaymentLine|\PHPUnit_Framework_MockObject_MockObject $paymentLine */
        $paymentLine = new PaymentLine('productnumber', 'name', 1, new Money('7996', new Currency('DKK')), 25);

        $paymentHandler = $this->getPaymentHandler($altapayClient);
        $paymentHandler->refund($payment, new Money(9995, new Currency('DKK')), [$paymentLine]);

        $this->assertEquals(new Money(9995, new Currency('DKK')), $amount);

        /** @var OrderLine $orderLine */
        $orderLine = $orderLines[0];
        $this->assertEquals(new Money(9995, new Currency('DKK')), $orderLine->getUnitPrice());
        $this->assertSame(25.0, $orderLine->getTaxPercent());
        $this->assertSame(1.0, $orderLine->getQuantity());
        $this->assertSame('name', $orderLine->getDescription());
        $this->assertSame('productnumber', $orderLine->getItemId());
    }

    public function testRefundAmountDoesNotMatchPaymentLineAmount()
    {
        $amount = null;
        $orderLines = [];

        $altapayClient = $this->getAltapayClient();
        $altapayClient
            ->expects($this->any())
            ->method('refundCapturedReservation')
            ->willReturnCallback(function ($val) use (&$amount, &$orderLines) {
                /** @var RefundCapturedReservationPayload $val */
                $amount = $val->getAmount();
                $orderLines = $val->getOrderLines();

                $response = $this->getMockBuilder(RefundCapturedReservationResponse::class)
                    ->disableOriginalConstructor()
                    ->getMock()
                ;

                return $response;
            })
        ;

        $payment = $this->getPayment();
        $payment->setAltapayId('altapayid');

        $paymentLine = new PaymentLine('productnumber', 'name', 1, new Money('10000', new Currency('DKK')), 25);

        $paymentHandler = $this->getPaymentHandler($altapayClient);
        $paymentHandler->refund($payment, new Money(8000, new Currency('DKK')), [$paymentLine]);

        $this->assertEquals(new Money(8000, new Currency('DKK')), $amount);

        /** @var OrderLine $orderLine */
        $orderLine = $orderLines[0];
        $this->assertEquals(new Money(8000, new Currency('DKK')), $orderLine->getUnitPrice());
        $this->assertSame(1.0, $orderLine->getQuantity());
        $this->assertSame('refund', $orderLine->getDescription());
        $this->assertSame('refund', $orderLine->getItemId());
    }

    /**
     * @return Payment|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getPayment()
    {
        return $this->getMockForAbstractClass(Payment::class);
    }

    /**
     * @return Client|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getAltapayClient()
    {
        /** @var Client|\PHPUnit_Framework_MockObject_MockObject $altapayClient */
        $altapayClient = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        return $altapayClient;
    }

    /**
     * @param $altapayClient
     *
     * @return PaymentHandler
     */
    private function getPaymentHandler($altapayClient): PaymentHandler
    {
        /** @var PaymentRepository|\PHPUnit_Framework_MockObject_MockObject $paymentRepository */
        $paymentRepository = $this->getMockBuilder(PaymentRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        return new PaymentHandler($altapayClient, $paymentRepository);
    }
}
