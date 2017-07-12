<?php
namespace Loevgaard\DandomainAltapayBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Loevgaard\Dandomain\Pay\PaymentRequest\OrderLine as DandomainOrderLine;

/**
 * @ORM\MappedSuperclass
 */
abstract class OrderLine implements OrderLineInterface
{
    /**
     * @var string
     */
    protected $productNumber;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * The price excl vat
     *
     * @var float
     */
    protected $price;

    /**
     * @var int
     */
    protected $vat;

    /**
     * @param DandomainOrderLine $orderLine
     */
    public function populateFromDandomainPaymentRequest(DandomainOrderLine $orderLine)
    {
        
    }

    /**
     * @return string
     */
    public function getProductNumber() : string
    {
        return $this->productNumber;
    }

    /**
     * @param string $productNumber
     * @return OrderLineInterface
     */
    public function setProductNumber(string $productNumber) : OrderLineInterface
    {
        $this->productNumber = $productNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return OrderLineInterface
     */
    public function setName($name) : OrderLineInterface
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity() : int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return OrderLineInterface
     */
    public function setQuantity($quantity) : OrderLineInterface
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrice() : float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return OrderLineInterface
     */
    public function setPrice($price) : OrderLineInterface
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return int
     */
    public function getVat() : int
    {
        return $this->vat;
    }

    /**
     * @param int $vat
     * @return OrderLineInterface
     */
    public function setVat($vat) : OrderLineInterface
    {
        $this->vat = $vat;
        return $this;
    }
}