<?php

namespace Loevgaard\DandomainAltapayBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Loevgaard\Dandomain\Pay\Model\PaymentLine as BasePaymentLine;
use Symfony\Component\Validator\Constraints as Assert;
use Loevgaard\Dandomain\Pay\Model\PaymentLine as DandomainPaymentLine;

/**
 * @ORM\Table(name="dandomain_altapay_payment_lines")
 * @ORM\Entity()
 */
class PaymentLine extends BasePaymentLine
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="191")
     *
     * @ORM\Column(type="string", length=191)
     */
    protected $productNumber;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="191")
     *
     * @ORM\Column(type="string", length=191)
     */
    protected $name;

    /**
     * @Assert\NotBlank()
     * @Assert\GreaterThan(0)
     *
     * @ORM\Column(type="integer")
     */
    protected $quantity;

    /**
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="decimal", precision=12, scale=2)
     */
    protected $price;

    /**
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(0)
     *
     * @ORM\Column(type="integer")
     */
    protected $vat;

    /**
     * @Assert\NotNull()
     *
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * @ORM\ManyToOne(targetEntity="Payment", inversedBy="paymentLines")
     */
    protected $payment;

    /**
     * This will transform a Dandomain Payment Line (parent) to a Payment Line (child).
     *
     * @param DandomainPaymentLine $dandomainPaymentLine
     *
     * @return PaymentLine
     */
    public static function createFromDandomainPaymentLine(DandomainPaymentLine $dandomainPaymentLine)
    {
        $paymentLine = new self();

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
     * @return int
     */
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return PaymentLine
     */
    public function setId($id) : self
    {
        $this->id = $id;
        return $this;
    }
}
