<?php
namespace Loevgaard\DandomainAltapayBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class OrderLine implements OrderLineInterface
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $productNumber;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $quantity;

    /**
     * The price excl vat
     *
     * @var float
     *
     * @ORM\Column(type="decimal", scale=2, precision=10)
     */
    protected $price;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $vat;

    /**
     * @var PaymentInterface
     */
    protected $payment;

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getProductNumber() : string
    {
        return $this->productNumber;
    }

    /**
     * @inheritdoc
     */
    public function setProductNumber(string $productNumber) : OrderLineInterface
    {
        $this->productNumber = $productNumber;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function setName(string $name) : OrderLineInterface
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getQuantity() : int
    {
        return $this->quantity;
    }

    /**
     * @inheritdoc
     */
    public function setQuantity(int $quantity) : OrderLineInterface
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPrice() : float
    {
        return $this->price;
    }

    /**
     * @inheritdoc
     */
    public function setPrice(float $price) : OrderLineInterface
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getVat() : int
    {
        return $this->vat;
    }

    /**
     * @inheritdoc
     */
    public function setVat(int $vat) : OrderLineInterface
    {
        $this->vat = $vat;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPayment(): PaymentInterface
    {
        return $this->payment;
    }

    /**
     * @inheritdoc
     */
    public function setPayment(PaymentInterface $payment): OrderLineInterface
    {
        $this->payment = $payment;
        return $this;
    }

}