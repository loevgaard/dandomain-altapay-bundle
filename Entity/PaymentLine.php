<?php

namespace Loevgaard\DandomainAltapayBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Loevgaard\Dandomain\Pay\Model\PaymentLine as BasePaymentLine;
use Loevgaard\Dandomain\Pay\Model\PaymentLine as DandomainPaymentLine;
use Money\Currency;
use Money\Money;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(type="integer")
     */
    protected $priceAmount;

    /**
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string")
     */
    protected $priceCurrency;

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
        $paymentLine = new self(
            $dandomainPaymentLine->getProductNumber(),
            $dandomainPaymentLine->getName(),
            $dandomainPaymentLine->getQuantity(),
            $dandomainPaymentLine->getPrice(),
            $dandomainPaymentLine->getVat()
        );

        if ($dandomainPaymentLine->getPayment()) {
            $paymentLine->setPayment($dandomainPaymentLine->getPayment());
        }

        return $paymentLine;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return PaymentLine
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    public function setPrice(Money $price): DandomainPaymentLine
    {
        parent::setPrice($price);

        $this->priceAmount = $price->getAmount();
        $this->priceCurrency = $price->getCurrency()->getCode();

        return $this;
    }

    public function getPrice(): Money
    {
        if (!$this->priceCurrency) {
            return null;
        }

        $amount = $this->priceAmount;
        if (!$amount) {
            $amount = 0;
        }

        return new Money($amount, new Currency($this->priceCurrency));
    }
}
