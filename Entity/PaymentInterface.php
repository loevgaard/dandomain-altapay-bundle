<?php
namespace Loevgaard\DandomainAltapayBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Loevgaard\DandomainFoundationBundle\Model\OrderInterface;

interface PaymentInterface
{
    /**
     * Returns unique payment id
     *
     * @return mixed
     */
    public function getId();

    /**
     * @return string
     */
    public function getApiKey() : string;

    /**
     * @param string $apiKey
     * @return PaymentInterface
     */
    public function setApiKey(string $apiKey) : PaymentInterface;

    /**
     * @return string
     */
    public function getMerchant() : string;

    /**
     * @param string $merchant
     * @return PaymentInterface
     */
    public function setMerchant(string $merchant) : PaymentInterface;

    /**
     * @return OrderInterface
     */
    public function getOrder() : OrderInterface;

    /**
     * @param OrderInterface $order
     * @return PaymentInterface
     */
    public function setOrder(OrderInterface $order) : PaymentInterface;

    /**
     * @return string
     */
    public function getSessionId() : string;

    /**
     * @param string $sessionId
     * @return PaymentInterface
     */
    public function setSessionId(string $sessionId) : PaymentInterface;

    /**
     * @return string
     */
    public function getCurrencySymbol() : string;

    /**
     * @param string $currencySymbol
     * @return PaymentInterface
     */
    public function setCurrencySymbol(string $currencySymbol) : PaymentInterface;

    /**
     * @return float
     */
    public function getTotalAmount() : float;

    /**
     * @param float $totalAmount
     * @return PaymentInterface
     */
    public function setTotalAmount(float $totalAmount) : PaymentInterface;

    /**
     * @return string
     */
    public function getCallBackUrl() : string;

    /**
     * @param string $callBackUrl
     * @return PaymentInterface
     */
    public function setCallBackUrl(string $callBackUrl) : PaymentInterface;

    /**
     * @return string
     */
    public function getFullCallBackOkUrl() : string;

    /**
     * @param string $fullCallBackOkUrl
     * @return PaymentInterface
     */
    public function setFullCallBackOkUrl(string $fullCallBackOkUrl) : PaymentInterface;

    /**
     * @return string
     */
    public function getCallBackOkUrl() : string;

    /**
     * @param string $callBackOkUrl
     * @return PaymentInterface
     */
    public function setCallBackOkUrl(string $callBackOkUrl) : PaymentInterface;

    /**
     * @return string
     */
    public function getCallBackServerUrl() : string;

    /**
     * @param string $callBackServerUrl
     * @return PaymentInterface
     */
    public function setCallBackServerUrl(string $callBackServerUrl) : PaymentInterface;

    /**
     * @return bool
     */
    public function isTestMode(): bool;

    /**
     * @param bool $testMode
     * @return PaymentInterface
     */
    public function setTestMode(bool $testMode) : PaymentInterface;

    /**
     * @return int
     */
    public function getPaymentGatewayCurrencyCode();

    /**
     * @param int $paymentGatewayCurrencyCode
     * @return PaymentInterface
     */
    public function setPaymentGatewayCurrencyCode(int $paymentGatewayCurrencyCode) : PaymentInterface;

    /**
     * @return int
     */
    public function getCardTypeId();

    /**
     * @param int $cardTypeId
     * @return PaymentInterface
     */
    public function setCardTypeId(int $cardTypeId) : PaymentInterface;

    /**
     * @return string
     */
    public function getLoadBalancerRealIp(): string;

    /**
     * @param string $loadBalancerRealIp
     * @return PaymentInterface
     */
    public function setLoadBalancerRealIp(string $loadBalancerRealIp) : PaymentInterface;

    /**
     * @return ArrayCollection|CallbackInterface[]
     */
    public function getCallbacks() : ArrayCollection;

    /**
     * @param ArrayCollection|CallbackInterface[] $callbacks
     * @return PaymentInterface
     */
    public function setCallbacks(ArrayCollection $callbacks) : PaymentInterface;

    /**
     * @param CallbackInterface $callback
     * @return PaymentInterface
     */
    public function addCallback(CallbackInterface $callback) : PaymentInterface;
}