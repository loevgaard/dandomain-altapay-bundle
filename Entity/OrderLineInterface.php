<?php
namespace Loevgaard\DandomainAltapayBundle\Entity;

interface OrderLineInterface
{
    /**
     * Returns unique order line id
     *
     * @return mixed
     */
    public function getId();

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
    public function setName(string $name) : OrderLineInterface;

    /**
     * @return int
     */
    public function getQuantity() : int;

    /**
     * @param int $quantity
     * @return OrderLineInterface
     */
    public function setQuantity(int $quantity) : OrderLineInterface;

    /**
     * @return float
     */
    public function getPrice() : float;

    /**
     * @param float $price
     * @return OrderLineInterface
     */
    public function setPrice(float $price) : OrderLineInterface;

    /**
     * @return int
     */
    public function getVat() : int;

    /**
     * @param int $vat
     * @return OrderLineInterface
     */
    public function setVat(int $vat) : OrderLineInterface;

    /**
     * @return PaymentInterface
     */
    public function getPayment() : PaymentInterface;

    /**
     * @param PaymentInterface $payment
     * @return OrderLineInterface
     */
    public function setPayment(PaymentInterface $payment) : OrderLineInterface;
}