<?php

namespace Loevgaard\DandomainAltapayBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Loevgaard\Dandomain\Pay\Model\PaymentLine as BasePaymentLine;

/**
 * @ORM\MappedSuperclass
 */
abstract class PaymentLine extends BasePaymentLine
{
    /**
     * @ORM\Column(type="string")
     */
    protected $productNumber;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="integer")
     */
    protected $quantity;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2)
     */
    protected $price;

    /**
     * @ORM\Column(type="integer")
     */
    protected $vat;

    /**
     * @ORM\ManyToOne(targetEntity="Payment", inversedBy="paymentLines")
     */
    protected $payment;
}
