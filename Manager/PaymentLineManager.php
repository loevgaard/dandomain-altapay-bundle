<?php

namespace Loevgaard\DandomainAltapayBundle\Manager;

use Loevgaard\Dandomain\Pay\PaymentRequest\PaymentLine as DandomainPaymentLine;
use Loevgaard\DandomainAltapayBundle\Entity\PaymentLine;
use Loevgaard\DoctrineManager\Manager;

class PaymentLineManager extends Manager
{
    /**
     * This will transform a PaymentRequest (parent) to a Payment (child).
     *
     * @param DandomainPaymentLine $dandomainPaymentLine
     *
     * @return PaymentLine
     */
    public function createPaymentLineFromDandomainPaymentRequest(DandomainPaymentLine $dandomainPaymentLine)
    {
        $paymentLine = $this->create();

        $methods = get_class_methods($dandomainPaymentLine);

        foreach ($methods as $method) {
            if ('get' === substr($method, 0, 3)) {
                $val = $dandomainPaymentLine->{$method}();
                $property = substr($method, 3);
            } elseif ('is' === substr($method, 0, 2)) {
                $val = $dandomainPaymentLine->{$method}();
                $property = substr($method, 2);
            } else {
                continue;
            }

            if (!is_scalar($val)) {
                continue;
            }

            $setter = 'set'.$property;

            if (method_exists($paymentLine, $setter)) {
                $paymentLine->{$setter}($val);
            }
        }

        return $paymentLine;
    }

    /**
     * @return PaymentLine
     */
    public function create()
    {
        return parent::create();
    }

    /**
     * @param PaymentLine $obj
     */
    public function delete($obj)
    {
        parent::delete($obj);
    }

    /**
     * @param PaymentLine $obj
     * @param bool        $flush
     */
    public function update($obj, $flush = true)
    {
        parent::update($obj, $flush);
    }
}
