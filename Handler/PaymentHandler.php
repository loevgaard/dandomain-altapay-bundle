<?php

namespace Loevgaard\DandomainAltapayBundle\Handler;

use Loevgaard\AltaPay\Client\Client;
use Loevgaard\AltaPay\Entity\Transaction;
use Loevgaard\AltaPay\Payload\CaptureReservation as CaptureReservationPayload;
use Loevgaard\AltaPay\Payload\OrderLine;
use Loevgaard\AltaPay\Payload\RefundCapturedReservation as RefundCapturedReservationPayload;
use Loevgaard\AltaPay\Response\RefundCapturedReservation as RefundCapturedReservationResponse;
use Loevgaard\DandomainAltapayBundle\Entity\Payment;
use Loevgaard\DandomainAltapayBundle\Entity\PaymentLine;
use Loevgaard\DandomainAltapayBundle\Entity\PaymentRepository;
use Money\Currency;
use Money\Money;

class PaymentHandler
{
    /**
     * @var Client
     */
    private $altapayClient;

    /**
     * @var PaymentRepository
     */
    private $paymentRepository;

    public function __construct(Client $client, PaymentRepository $paymentRepository)
    {
        $this->altapayClient = $client;
        $this->paymentRepository = $paymentRepository;
    }

    public function capture(Payment $payment, Money $amount = null)
    {
        $payload = new CaptureReservationPayload($payment->getAltapayId());
        if ($amount) {
            $payload->setAmount($amount);
        }

        $res = $this->altapayClient->captureReservation($payload);

        if ($res->isSuccessful()) {
            $this->updatePaymentFromTransactions($payment, $res->getTransactions());
        }

        return $res;
    }

    /**
     * @param Payment            $payment      The payment to refund
     * @param PaymentLine[]|null $paymentLines The payment lines to refund
     * @param Money|null         $amount       The amount to refund
     *
     * @return RefundCapturedReservationResponse
     */
    public function refund(Payment $payment, array $paymentLines = null, Money $amount = null)
    {
        $payload = new RefundCapturedReservationPayload($payment->getAltapayId());

        if ($amount) {
            $payload->setAmount($amount);
        }

        if ($paymentLines && count($paymentLines)) {
            $paymentLinesAmountInclVat = new Money(0, new Currency($amount->getCurrency()->getCode()));

            foreach ($paymentLines as $paymentLine) {
                $orderLine = new OrderLine(
                    $paymentLine->getName(),
                    $paymentLine->getProductNumber(),
                    $paymentLine->getQuantity(),
                    $paymentLine->getPriceInclVat()
                );
                $orderLine->setTaxPercent($paymentLine->getVat());

                $payload->addOrderLine($orderLine);

                $paymentLinesAmountInclVat = $paymentLinesAmountInclVat->add($paymentLine->getPriceInclVat());
            }

            /*
             * If the amount is set, but does not match the payment lines amount we have to
             * make a 'good will' refund which according to Altapay is made by adding an order line
             * with goods type equals 'refund' and the amount has to equal the refund amount including vat
             */
            if ($amount && !$amount->equals($paymentLinesAmountInclVat)) {
                $orderLine = new OrderLine('refund', 'refund', 1, $amount);
                $orderLine->setGoodsType(OrderLine::GOODS_TYPE_REFUND);

                // this effectively removes already added order lines and adds the 'refund' order line
                $payload->setOrderLines([$orderLine]);
            }
        }

        $res = $this->altapayClient->refundCapturedReservation($payload);

        if ($res->isSuccessful()) {
            $this->updatePaymentFromTransactions($payment, $res->getTransactions());
        }

        return $res;
    }

    /**
     * @param Payment       $payment
     * @param Transaction[] $transactions
     */
    private function updatePaymentFromTransactions(Payment $payment, array $transactions)
    {
        if (count($transactions)) {
            $transaction = $transactions[0];

            if ($transaction->getCapturedAmount()) {
                $payment->setCapturedAmount($transaction->getCapturedAmount());
            }

            if ($transaction->getRefundedAmount()) {
                $payment->setRefundedAmount($transaction->getRefundedAmount());
            }

            $this->paymentRepository->save($payment);
        }
    }
}
