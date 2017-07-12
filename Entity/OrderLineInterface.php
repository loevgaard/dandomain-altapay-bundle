<?php
namespace Loevgaard\DandomainAltapayBundle\Entity;

interface OrderLineInterface
{
    /**
     * @return string
     */
    public function getProductNumber() : string;

    /**
     * @param string $productNumber
     * @return OrderLineInterface
     */
    public function setProductNumber(string $productNumber) : OrderLineInterface;

    /**
     * @return string
     */
    public function getName() : string;

    /**
     * @param string $name
     * @return OrderLineInterface
     */
    public function setName($name) : OrderLineInterface;

    /**
     * @return int
     */
    public function getQuantity() : int;

    /**
     * @param int $quantity
     * @return OrderLineInterface
     */
    public function setQuantity($quantity) : OrderLineInterface;

    /**
     * @return float
     */
    public function getPrice() : float;

    /**
     * @param float $price
     * @return OrderLineInterface
     */
    public function setPrice($price) : OrderLineInterface;

    /**
     * @return int
     */
    public function getVat() : int;

    /**
     * @param int $vat
     * @return OrderLineInterface
     */
    public function setVat($vat) : OrderLineInterface;
}