<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Handler;

use Loevgaard\AltaPay\Client;
use Loevgaard\AltaPay\Entity\Transaction;
use Loevgaard\AltaPay\Payload\OrderLine;
use Loevgaard\AltaPay\Payload\RefundCapturedReservation as RefundCapturedReservationPayload;
use Loevgaard\AltaPay\Response\CaptureReservation as CaptureReservationResponse;
use Loevgaard\AltaPay\Response\RefundCapturedReservation as RefundCapturedReservationResponse;
use Loevgaard\DandomainAltapayBundle\Entity\Payment;
use Loevgaard\DandomainAltapayBundle\Entity\PaymentLine;
use Loevgaard\DandomainAltapayBundle\Handler\PaymentHandler;
use Loevgaard\DandomainAltapayBundle\Manager\PaymentManager;
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
        $res = $paymentHandler->capture($payment, 99.95);

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
        $paymentHandler->refund($payment, null, 100.55);

        $this->assertSame(100.55, $amount);
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
        $paymentLine = $this->getMockForAbstractClass(PaymentLine::class);
        $paymentLine->setPrice(79.96)
            ->setVat(25)
            ->setQuantity(1)
            ->setName('name')
            ->setProductNumber('productnumber')
        ;

        $paymentHandler = $this->getPaymentHandler($altapayClient);
        $paymentHandler->refund($payment, [$paymentLine], 99.95);

        $this->assertSame(99.95, $amount);

        /** @var OrderLine $orderLine */
        $orderLine = $orderLines[0];
        $this->assertSame(99.95, $orderLine->getUnitPrice());
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

        /** @var PaymentLine|\PHPUnit_Framework_MockObject_MockObject $paymentLine */
        $paymentLine = $this->getMockForAbstractClass(PaymentLine::class);
        $paymentLine->setPrice(100)
            ->setVat(25)
            ->setQuantity(1)
            ->setName('name')
            ->setProductNumber('productnumber')
        ;

        $paymentHandler = $this->getPaymentHandler($altapayClient);
        $paymentHandler->refund($payment, [$paymentLine], 80);

        $this->assertSame(80.0, $amount);

        /** @var OrderLine $orderLine */
        $orderLine = $orderLines[0];
        $this->assertSame(80.0, $orderLine->getUnitPrice());
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
        /** @var PaymentManager|\PHPUnit_Framework_MockObject_MockObject $paymentManager */
        $paymentManager = $this->getMockBuilder(PaymentManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        return new PaymentHandler($altapayClient, $paymentManager);
    }
}
