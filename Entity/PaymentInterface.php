<?php
namespace Loevgaard\DandomainAltapayBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

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
     * @return int
     */
    public function getOrderId() : int;

    /**
     * @param int $orderId
     * @return PaymentInterface
     */
    public function setOrderId(int $orderId) : PaymentInterface;

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
     * @return int
     */
    public function getLanguageId() : int;

    /**
     * @param int $languageId
     * @return PaymentInterface
     */
    public function setLanguageId(int $languageId) : PaymentInterface;

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
    public function getCustomerRekvNr() : string;

    /**
     * @param string $customerRekvNr
     * @return PaymentInterface
     */
    public function setCustomerRekvNr(string $customerRekvNr) : PaymentInterface;

    /**
     * @return string
     */
    public function getCustomerName() : string;

    /**
     * @param string $customerName
     * @return PaymentInterface
     */
    public function setCustomerName(string $customerName) : PaymentInterface;

    /**
     * @return string
     */
    public function getCustomerCompany() : string;

    /**
     * @param string $customerCompany
     * @return PaymentInterface
     */
    public function setCustomerCompany(string $customerCompany) : PaymentInterface;

    /**
     * @return string
     */
    public function getCustomerAddress() : string;

    /**
     * @param string $customerAddress
     * @return PaymentInterface
     */
    public function setCustomerAddress(string $customerAddress) : PaymentInterface;

    /**
     * @return string
     */
    public function getCustomerAddress2() : string;

    /**
     * @param string $customerAddress2
     * @return PaymentInterface
     */
    public function setCustomerAddress2(string $customerAddress2) : PaymentInterface;

    /**
     * @return string
     */
    public function getCustomerZipCode() : string;

    /**
     * @param string $customerZipCode
     * @return PaymentInterface
     */
    public function setCustomerZipCode(string $customerZipCode) : PaymentInterface;

    /**
     * @return string
     */
    public function getCustomerCity() : string;

    /**
     * @param string $customerCity
     * @return PaymentInterface
     */
    public function setCustomerCity(string $customerCity) : PaymentInterface;

    /**
     * @return int
     */
    public function getCustomerCountryId();

    /**
     * @param int $customerCountryId
     * @return PaymentInterface
     */
    public function setCustomerCountryId(int $customerCountryId) : PaymentInterface;

    /**
     * @return string
     */
    public function getCustomerCountry() : string;

    /**
     * @param string $customerCountry
     * @return PaymentInterface
     */
    public function setCustomerCountry(string $customerCountry) : PaymentInterface;

    /**
     * @return string
     */
    public function getCustomerPhone() : string;

    /**
     * @param string $customerPhone
     * @return PaymentInterface
     */
    public function setCustomerPhone(string $customerPhone) : PaymentInterface;

    /**
     * @return string
     */
    public function getCustomerFax() : string;

    /**
     * @param string $customerFax
     * @return PaymentInterface
     */
    public function setCustomerFax(string $customerFax) : PaymentInterface;

    /**
     * @return string
     */
    public function getCustomerEmail() : string;

    /**
     * @param string $customerEmail
     * @return PaymentInterface
     */
    public function setCustomerEmail(string $customerEmail) : PaymentInterface;

    /**
     * @return string
     */
    public function getCustomerNote() : string;

    /**
     * @param string $customerNote
     * @return PaymentInterface
     */
    public function setCustomerNote(string $customerNote) : PaymentInterface;

    /**
     * @return string
     */
    public function getCustomerCvrnr() : string;

    /**
     * @param string $customerCvrnr
     * @return PaymentInterface
     */
    public function setCustomerCvrnr(string $customerCvrnr) : PaymentInterface;

    /**
     * @return int
     */
    public function getCustomerCustTypeId() : int;

    /**
     * @param int $customerCustTypeId
     * @return PaymentInterface
     */
    public function setCustomerCustTypeId(int $customerCustTypeId) : PaymentInterface;

    /**
     * @return string
     */
    public function getCustomerEan() : string;

    /**
     * @param string $customerEan
     * @return PaymentInterface
     */
    public function setCustomerEan(string $customerEan) : PaymentInterface;

    /**
     * @return string
     */
    public function getCustomerRes1() : string;

    /**
     * @param string $customerRes1
     * @return PaymentInterface
     */
    public function setCustomerRes1(string $customerRes1) : PaymentInterface;

    /**
     * @return string
     */
    public function getCustomerRes2() : string;

    /**
     * @param string $customerRes2
     * @return PaymentInterface
     */
    public function setCustomerRes2(string $customerRes2) : PaymentInterface;

    /**
     * @return string
     */
    public function getCustomerRes3() : string;

    /**
     * @param string $customerRes3
     * @return PaymentInterface
     */
    public function setCustomerRes3(string $customerRes3) : PaymentInterface;

    /**
     * @return string
     */
    public function getCustomerRes4() : string;

    /**
     * @param string $customerRes4
     * @return PaymentInterface
     */
    public function setCustomerRes4(string $customerRes4) : PaymentInterface;

    /**
     * @return string
     */
    public function getCustomerRes5() : string;

    /**
     * @param string $customerRes5
     * @return PaymentInterface
     */
    public function setCustomerRes5(string $customerRes5) : PaymentInterface;

    /**
     * @return string
     */
    public function getCustomerIp() : string;

    /**
     * @param string $customerIp
     * @return PaymentInterface
     */
    public function setCustomerIp(string $customerIp) : PaymentInterface;

    /**
     * @return string
     */
    public function getDeliveryName() : string;

    /**
     * @param string $deliveryName
     * @return PaymentInterface
     */
    public function setDeliveryName(string $deliveryName) : PaymentInterface;

    /**
     * @return string
     */
    public function getDeliveryCompany() : string;

    /**
     * @param string $deliveryCompany
     * @return PaymentInterface
     */
    public function setDeliveryCompany(string $deliveryCompany) : PaymentInterface;

    /**
     * @return string
     */
    public function getDeliveryAddress() : string;

    /**
     * @param string $deliveryAddress
     * @return PaymentInterface
     */
    public function setDeliveryAddress(string $deliveryAddress) : PaymentInterface;

    /**
     * @return string
     */
    public function getDeliveryAddress2() : string;

    /**
     * @param string $deliveryAddress2
     * @return PaymentInterface
     */
    public function setDeliveryAddress2(string $deliveryAddress2) : PaymentInterface;

    /**
     * @return string
     */
    public function getDeliveryZipCode() : string;

    /**
     * @param string $deliveryZipCode
     * @return PaymentInterface
     */
    public function setDeliveryZipCode(string $deliveryZipCode) : PaymentInterface;

    /**
     * @return string
     */
    public function getDeliveryCity() : string;

    /**
     * @param string $deliveryCity
     * @return PaymentInterface
     */
    public function setDeliveryCity(string $deliveryCity) : PaymentInterface;

    /**
     * @return int
     */
    public function getDeliveryCountryID() : int;

    /**
     * @param int $deliveryCountryID
     * @return PaymentInterface
     */
    public function setDeliveryCountryID(int $deliveryCountryID) : PaymentInterface;

    /**
     * @return string
     */
    public function getDeliveryCountry() : string;

    /**
     * @param string $deliveryCountry
     * @return PaymentInterface
     */
    public function setDeliveryCountry(string $deliveryCountry) : PaymentInterface;

    /**
     * @return string
     */
    public function getDeliveryPhone() : string;

    /**
     * @param string $deliveryPhone
     * @return PaymentInterface
     */
    public function setDeliveryPhone(string $deliveryPhone) : PaymentInterface;

    /**
     * @return string
     */
    public function getDeliveryFax() : string;

    /**
     * @param string $deliveryFax
     * @return PaymentInterface
     */
    public function setDeliveryFax(string $deliveryFax) : PaymentInterface;

    /**
     * @return string
     */
    public function getDeliveryEmail() : string;

    /**
     * @param string $deliveryEmail
     * @return PaymentInterface
     */
    public function setDeliveryEmail(string $deliveryEmail) : PaymentInterface;

    /**
     * @return string
     */
    public function getDeliveryEan() : string;

    /**
     * @param string $deliveryEan
     * @return PaymentInterface
     */
    public function setDeliveryEan(string $deliveryEan) : PaymentInterface;

    /**
     * @return string
     */
    public function getShippingMethod() : string;

    /**
     * @param string $shippingMethod
     * @return PaymentInterface
     */
    public function setShippingMethod(string $shippingMethod) : PaymentInterface;

    /**
     * @return float
     */
    public function getShippingFee() : float;

    /**
     * @param float $shippingFee
     * @return PaymentInterface
     */
    public function setShippingFee(float $shippingFee) : PaymentInterface;

    /**
     * @return string
     */
    public function getPaymentMethod() : string;

    /**
     * @param string $paymentMethod
     * @return PaymentInterface
     */
    public function setPaymentMethod(string $paymentMethod) : PaymentInterface;

    /**
     * @return float
     */
    public function getPaymentFee() : float;

    /**
     * @param float $paymentFee
     * @return PaymentInterface
     */
    public function setPaymentFee(float $paymentFee) : PaymentInterface;

    /**
     * @return string
     */
    public function getLoadBalancerRealIp() : string;

    /**
     * @param string $loadBalancerRealIp
     * @return PaymentInterface
     */
    public function setLoadBalancerRealIp(string $loadBalancerRealIp) : PaymentInterface;

    /**
     * @return ArrayCollection|OrderLineInterface[]
     */
    public function getOrderLines() : ArrayCollection;

    /**
     * @param ArrayCollection|OrderLineInterface[] $orderLines
     * @return PaymentInterface
     */
    public function setOrderLines(ArrayCollection $orderLines) : PaymentInterface;

    /**
     * @param OrderLineInterface $orderLine
     * @return PaymentInterface
     */
    public function addOrderLine(OrderLineInterface $orderLine) : PaymentInterface;

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