<?php

namespace Loevgaard\DandomainAltapayBundle\Manager;

use Loevgaard\Dandomain\Pay\Model\Payment as DandomainPayment;
use Loevgaard\DandomainAltapayBundle\Entity\Payment;
use Loevgaard\DoctrineManager\Manager;

class PaymentManager extends Manager
{
    /**
     * @var PaymentLineManager
     */
    protected $paymentLineManager;

    /**
     * This will transform a PaymentRequest (parent) to a Payment (child).
     *
     * @param DandomainPayment $dandomainPayment
     *
     * @return Payment
     */
    public function createPaymentFromDandomainPaymentRequest(DandomainPayment $dandomainPayment)
    {
        $payment = $this->create();

        $methods = get_class_methods($dandomainPayment);

        foreach ($methods as $method) {
            if ('get' === substr($method, 0, 3)) {
                $val = $dandomainPayment->{$method}();
                $property = substr($method, 3);
            } elseif ('is' === substr($method, 0, 2)) {
                $val = $dandomainPayment->{$method}();
                $property = substr($method, 2);
            } else {
                continue;
            }

            if (!is_scalar($val)) {
                continue;
            }

            $setter = 'set'.$property;

            if (method_exists($payment, $setter)) {
                $payment->{$setter}($val);
            }
        }

        foreach ($dandomainPayment->getPaymentLines() as $paymentLine) {
            $newPaymentLine = $this->paymentLineManager->createPaymentLineFromDandomainPaymentRequest($paymentLine);
            $payment->addPaymentLine($newPaymentLine);
        }

        return $payment;
    }

    /**
     * @param $id
     *
     * @return null|Payment
     */
    public function findByOrderIdOrAltapayId($id)
    {
        /** @var Payment $payment */
        $payment = $this->getRepository()->findOneBy([
            'orderId' => $id,
        ]);

        if (!$payment) {
            /** @var Payment $payment */
            $payment = $this->getRepository()->findOneBy([
                'altapayId' => $id,
            ]);
        }

        return $payment;
    }

    /**
     * @return Payment
     */
    public function create()
    {
        return parent::create();
    }

    /**
     * @param Payment $obj
     */
    public function delete($obj)
    {
        parent::delete($obj);
    }

    /**
     * @param Payment $obj
     * @param bool    $flush
     */
    public function update($obj, $flush = true)
    {
        parent::update($obj, $flush);
    }

    /**
     * @param PaymentLineManager $paymentLineManager
     */
    public function setPaymentLineManager(PaymentLineManager $paymentLineManager)
    {
        $this->paymentLineManager = $paymentLineManager;
    }
}
