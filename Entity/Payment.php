<?php
namespace Loevgaard\DandomainAltapayBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class Payment implements PaymentInterface
{
    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $merchant;

    /**
     * @var int
     */
    protected $orderId;

    /**
     * @var string
     */
    protected $sessionId;

    /**
     * @var string
     */
    protected $currencySymbol;

    /**
     * @var float
     */
    protected $totalAmount;

    /**
     * @var string
     */
    protected $callBackUrl;

    /**
     * @var string
     */
    protected $fullCallBackOkUrl;

    /**
     * @var string
     */
    protected $callBackOkUrl;

    /**
     * @var string
     */
    protected $callBackServerUrl;

    /**
     * @var int
     */
    protected $languageId;

    /**
     * @var boolean
     */
    protected $testMode;

    /**
     * @var int
     */
    protected $paymentGatewayCurrencyCode;

    /**
     * @var int
     */
    protected $cardTypeId;

    /**
     * @var string
     */
    protected $customerRekvNr;

    /**
     * @var string
     */
    protected $customerName;

    /**
     * @var string
     */
    protected $customerCompany;

    /**
     * @var string
     */
    protected $customerAddress;

    /**
     * @var string
     */
    protected $customerAddress2;

    /**
     * @var string
     */
    protected $customerZipCode;

    /**
     * @var string
     */
    protected $customerCity;

    /**
     * @var int
     */
    protected $customerCountryId;

    /**
     * @var string
     */
    protected $customerCountry;

    /**
     * @var string
     */
    protected $customerPhone;

    /**
     * @var string
     */
    protected $customerFax;

    /**
     * @var string
     */
    protected $customerEmail;

    /**
     * @var string
     */
    protected $customerNote;

    /**
     * @var string
     */
    protected $customerCvrnr;

    /**
     * @var int
     */
    protected $customerCustTypeId;

    /**
     * @var string
     */
    protected $customerEan;

    /**
     * @var string
     */
    protected $customerRes1;

    /**
     * @var string
     */
    protected $customerRes2;

    /**
     * @var string
     */
    protected $customerRes3;

    /**
     * @var string
     */
    protected $customerRes4;

    /**
     * @var string
     */
    protected $customerRes5;

    /**
     * @var string
     */
    protected $customerIp;

    /**
     * @var string
     */
    protected $deliveryName;

    /**
     * @var string
     */
    protected $deliveryCompany;

    /**
     * @var string
     */
    protected $deliveryAddress;

    /**
     * @var string
     */
    protected $deliveryAddress2;

    /**
     * @var string
     */
    protected $deliveryZipCode;

    /**
     * @var string
     */
    protected $deliveryCity;

    /**
     * @var int
     */
    protected $deliveryCountryID;

    /**
     * @var string
     */
    protected $deliveryCountry;

    /**
     * @var string
     */
    protected $deliveryPhone;

    /**
     * @var string
     */
    protected $deliveryFax;

    /**
     * @var string
     */
    protected $deliveryEmail;

    /**
     * @var string
     */
    protected $deliveryEan;

    /**
     * @var string
     */
    protected $shippingMethod;

    /**
     * @var float
     */
    protected $shippingFee;

    /**
     * @var string
     */
    protected $paymentMethod;

    /**
     * @var float
     */
    protected $paymentFee;

    /**
     * @var string
     */
    protected $loadBalancerRealIp;

    /**
     * @var OrderLine[]
     */
    protected $orderLines;

    public function __construct()
    {
        $this->orderLines = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getApiKey() : string
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     * @return PaymentInterface
     */
    public function setApiKey(string $apiKey) : PaymentInterface
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getMerchant() : string
    {
        return $this->merchant;
    }

    /**
     * @param string $merchant
     * @return PaymentInterface
     */
    public function setMerchant(string $merchant) : PaymentInterface
    {
        $this->merchant = $merchant;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrderId() : int
    {
        return $this->orderId;
    }

    /**
     * @param int $orderId
     * @return PaymentInterface
     */
    public function setOrderId(int $orderId) : PaymentInterface
    {
        $this->orderId = $orderId;
        return $this;
    }

    /**
     * @return string
     */
    public function getSessionId() : string
    {
        return $this->sessionId;
    }

    /**
     * @param string $sessionId
     * @return PaymentInterface
     */
    public function setSessionId(string $sessionId) : PaymentInterface
    {
        $this->sessionId = $sessionId;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrencySymbol() : string
    {
        return $this->currencySymbol;
    }

    /**
     * @param string $currencySymbol
     * @return PaymentInterface
     */
    public function setCurrencySymbol(string $currencySymbol) : PaymentInterface
    {
        $this->currencySymbol = $currencySymbol;
        return $this;
    }

    /**
     * @return float
     */
    public function getTotalAmount() : float
    {
        return $this->totalAmount;
    }

    /**
     * @param float $totalAmount
     * @return PaymentInterface
     */
    public function setTotalAmount(float $totalAmount) : PaymentInterface
    {
        $this->totalAmount = $totalAmount;
        return $this;
    }

    /**
     * @return string
     */
    public function getCallBackUrl() : string
    {
        return $this->callBackUrl;
    }

    /**
     * @param string $callBackUrl
     * @return PaymentInterface
     */
    public function setCallBackUrl(string $callBackUrl) : PaymentInterface
    {
        $this->callBackUrl = $callBackUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getFullCallBackOkUrl() : string
    {
        return $this->fullCallBackOkUrl;
    }

    /**
     * @param string $fullCallBackOkUrl
     * @return PaymentInterface
     */
    public function setFullCallBackOkUrl(string $fullCallBackOkUrl) : PaymentInterface
    {
        $this->fullCallBackOkUrl = $fullCallBackOkUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getCallBackOkUrl() : string
    {
        return $this->callBackOkUrl;
    }

    /**
     * @param string $callBackOkUrl
     * @return PaymentInterface
     */
    public function setCallBackOkUrl(string $callBackOkUrl) : PaymentInterface
    {
        $this->callBackOkUrl = $callBackOkUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getCallBackServerUrl() : string
    {
        return $this->callBackServerUrl;
    }

    /**
     * @param string $callBackServerUrl
     * @return PaymentInterface
     */
    public function setCallBackServerUrl(string $callBackServerUrl) : PaymentInterface
    {
        $this->callBackServerUrl = $callBackServerUrl;
        return $this;
    }

    /**
     * @return int
     */
    public function getLanguageId() : int
    {
        return $this->languageId;
    }

    /**
     * @param int $languageId
     * @return PaymentInterface
     */
    public function setLanguageId(int $languageId) : PaymentInterface
    {
        $this->languageId = $languageId;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTestMode(): bool
    {
        return $this->testMode;
    }

    /**
     * @param bool $testMode
     * @return PaymentInterface
     */
    public function setTestMode(bool $testMode) : PaymentInterface
    {
        $this->testMode = $testMode;
        return $this;
    }

    /**
     * @return int
     */
    public function getPaymentGatewayCurrencyCode()
    {
        return $this->paymentGatewayCurrencyCode;
    }

    /**
     * @param int $paymentGatewayCurrencyCode
     * @return PaymentInterface
     */
    public function setPaymentGatewayCurrencyCode(int $paymentGatewayCurrencyCode) : PaymentInterface
    {
        $this->paymentGatewayCurrencyCode = $paymentGatewayCurrencyCode;
        return $this;
    }

    /**
     * @return int
     */
    public function getCardTypeId()
    {
        return $this->cardTypeId;
    }

    /**
     * @param int $cardTypeId
     * @return PaymentInterface
     */
    public function setCardTypeId(int $cardTypeId) : PaymentInterface
    {
        $this->cardTypeId = $cardTypeId;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerRekvNr() : string
    {
        return $this->customerRekvNr;
    }

    /**
     * @param string $customerRekvNr
     * @return PaymentInterface
     */
    public function setCustomerRekvNr(string $customerRekvNr) : PaymentInterface
    {
        $this->customerRekvNr = $customerRekvNr;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerName() : string
    {
        return $this->customerName;
    }

    /**
     * @param string $customerName
     * @return PaymentInterface
     */
    public function setCustomerName(string $customerName) : PaymentInterface
    {
        $this->customerName = $customerName;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerCompany() : string
    {
        return $this->customerCompany;
    }

    /**
     * @param string $customerCompany
     * @return PaymentInterface
     */
    public function setCustomerCompany(string $customerCompany) : PaymentInterface
    {
        $this->customerCompany = $customerCompany;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerAddress() : string
    {
        return $this->customerAddress;
    }

    /**
     * @param string $customerAddress
     * @return PaymentInterface
     */
    public function setCustomerAddress(string $customerAddress) : PaymentInterface
    {
        $this->customerAddress = $customerAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerAddress2() : string
    {
        return $this->customerAddress2;
    }

    /**
     * @param string $customerAddress2
     * @return PaymentInterface
     */
    public function setCustomerAddress2(string $customerAddress2) : PaymentInterface
    {
        $this->customerAddress2 = $customerAddress2;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerZipCode() : string
    {
        return $this->customerZipCode;
    }

    /**
     * @param string $customerZipCode
     * @return PaymentInterface
     */
    public function setCustomerZipCode(string $customerZipCode) : PaymentInterface
    {
        $this->customerZipCode = $customerZipCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerCity() : string
    {
        return $this->customerCity;
    }

    /**
     * @param string $customerCity
     * @return PaymentInterface
     */
    public function setCustomerCity(string $customerCity) : PaymentInterface
    {
        $this->customerCity = $customerCity;
        return $this;
    }

    /**
     * @return int
     */
    public function getCustomerCountryId()
    {
        return $this->customerCountryId;
    }

    /**
     * @param int $customerCountryId
     * @return PaymentInterface
     */
    public function setCustomerCountryId(int $customerCountryId) : PaymentInterface
    {
        $this->customerCountryId = $customerCountryId;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerCountry() : string
    {
        return $this->customerCountry;
    }

    /**
     * @param string $customerCountry
     * @return PaymentInterface
     */
    public function setCustomerCountry(string $customerCountry) : PaymentInterface
    {
        $this->customerCountry = $customerCountry;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerPhone() : string
    {
        return $this->customerPhone;
    }

    /**
     * @param string $customerPhone
     * @return PaymentInterface
     */
    public function setCustomerPhone(string $customerPhone) : PaymentInterface
    {
        $this->customerPhone = $customerPhone;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerFax() : string
    {
        return $this->customerFax;
    }

    /**
     * @param string $customerFax
     * @return PaymentInterface
     */
    public function setCustomerFax(string $customerFax) : PaymentInterface
    {
        $this->customerFax = $customerFax;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerEmail() : string
    {
        return $this->customerEmail;
    }

    /**
     * @param string $customerEmail
     * @return PaymentInterface
     */
    public function setCustomerEmail(string $customerEmail) : PaymentInterface
    {
        $this->customerEmail = $customerEmail;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerNote() : string
    {
        return $this->customerNote;
    }

    /**
     * @param string $customerNote
     * @return PaymentInterface
     */
    public function setCustomerNote(string $customerNote) : PaymentInterface
    {
        $this->customerNote = $customerNote;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerCvrnr() : string
    {
        return $this->customerCvrnr;
    }

    /**
     * @param string $customerCvrnr
     * @return PaymentInterface
     */
    public function setCustomerCvrnr(string $customerCvrnr) : PaymentInterface
    {
        $this->customerCvrnr = $customerCvrnr;
        return $this;
    }

    /**
     * @return int
     */
    public function getCustomerCustTypeId() : int
    {
        return $this->customerCustTypeId;
    }

    /**
     * @param int $customerCustTypeId
     * @return PaymentInterface
     */
    public function setCustomerCustTypeId(int $customerCustTypeId) : PaymentInterface
    {
        $this->customerCustTypeId = $customerCustTypeId;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerEan() : string
    {
        return $this->customerEan;
    }

    /**
     * @param string $customerEan
     * @return PaymentInterface
     */
    public function setCustomerEan(string $customerEan) : PaymentInterface
    {
        $this->customerEan = $customerEan;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerRes1() : string
    {
        return $this->customerRes1;
    }

    /**
     * @param string $customerRes1
     * @return PaymentInterface
     */
    public function setCustomerRes1(string $customerRes1) : PaymentInterface
    {
        $this->customerRes1 = $customerRes1;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerRes2() : string
    {
        return $this->customerRes2;
    }

    /**
     * @param string $customerRes2
     * @return PaymentInterface
     */
    public function setCustomerRes2(string $customerRes2) : PaymentInterface
    {
        $this->customerRes2 = $customerRes2;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerRes3() : string
    {
        return $this->customerRes3;
    }

    /**
     * @param string $customerRes3
     * @return PaymentInterface
     */
    public function setCustomerRes3(string $customerRes3) : PaymentInterface
    {
        $this->customerRes3 = $customerRes3;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerRes4() : string
    {
        return $this->customerRes4;
    }

    /**
     * @param string $customerRes4
     * @return PaymentInterface
     */
    public function setCustomerRes4(string $customerRes4) : PaymentInterface
    {
        $this->customerRes4 = $customerRes4;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerRes5() : string
    {
        return $this->customerRes5;
    }

    /**
     * @param string $customerRes5
     * @return PaymentInterface
     */
    public function setCustomerRes5(string $customerRes5) : PaymentInterface
    {
        $this->customerRes5 = $customerRes5;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerIp() : string
    {
        return $this->customerIp;
    }

    /**
     * @param string $customerIp
     * @return PaymentInterface
     */
    public function setCustomerIp(string $customerIp) : PaymentInterface
    {
        $this->customerIp = $customerIp;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeliveryName() : string
    {
        return $this->deliveryName;
    }

    /**
     * @param string $deliveryName
     * @return PaymentInterface
     */
    public function setDeliveryName(string $deliveryName) : PaymentInterface
    {
        $this->deliveryName = $deliveryName;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeliveryCompany() : string
    {
        return $this->deliveryCompany;
    }

    /**
     * @param string $deliveryCompany
     * @return PaymentInterface
     */
    public function setDeliveryCompany(string $deliveryCompany) : PaymentInterface
    {
        $this->deliveryCompany = $deliveryCompany;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeliveryAddress() : string
    {
        return $this->deliveryAddress;
    }

    /**
     * @param string $deliveryAddress
     * @return PaymentInterface
     */
    public function setDeliveryAddress(string $deliveryAddress) : PaymentInterface
    {
        $this->deliveryAddress = $deliveryAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeliveryAddress2() : string
    {
        return $this->deliveryAddress2;
    }

    /**
     * @param string $deliveryAddress2
     * @return PaymentInterface
     */
    public function setDeliveryAddress2(string $deliveryAddress2) : PaymentInterface
    {
        $this->deliveryAddress2 = $deliveryAddress2;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeliveryZipCode() : string
    {
        return $this->deliveryZipCode;
    }

    /**
     * @param string $deliveryZipCode
     * @return PaymentInterface
     */
    public function setDeliveryZipCode(string $deliveryZipCode) : PaymentInterface
    {
        $this->deliveryZipCode = $deliveryZipCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeliveryCity() : string
    {
        return $this->deliveryCity;
    }

    /**
     * @param string $deliveryCity
     * @return PaymentInterface
     */
    public function setDeliveryCity(string $deliveryCity) : PaymentInterface
    {
        $this->deliveryCity = $deliveryCity;
        return $this;
    }

    /**
     * @return int
     */
    public function getDeliveryCountryID() : int
    {
        return $this->deliveryCountryID;
    }

    /**
     * @param int $deliveryCountryID
     * @return PaymentInterface
     */
    public function setDeliveryCountryID(int $deliveryCountryID) : PaymentInterface
    {
        $this->deliveryCountryID = $deliveryCountryID;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeliveryCountry() : string
    {
        return $this->deliveryCountry;
    }

    /**
     * @param string $deliveryCountry
     * @return PaymentInterface
     */
    public function setDeliveryCountry(string $deliveryCountry) : PaymentInterface
    {
        $this->deliveryCountry = $deliveryCountry;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeliveryPhone() : string
    {
        return $this->deliveryPhone;
    }

    /**
     * @param string $deliveryPhone
     * @return PaymentInterface
     */
    public function setDeliveryPhone(string $deliveryPhone) : PaymentInterface
    {
        $this->deliveryPhone = $deliveryPhone;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeliveryFax() : string
    {
        return $this->deliveryFax;
    }

    /**
     * @param string $deliveryFax
     * @return PaymentInterface
     */
    public function setDeliveryFax(string $deliveryFax) : PaymentInterface
    {
        $this->deliveryFax = $deliveryFax;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeliveryEmail() : string
    {
        return $this->deliveryEmail;
    }

    /**
     * @param string $deliveryEmail
     * @return PaymentInterface
     */
    public function setDeliveryEmail(string $deliveryEmail) : PaymentInterface
    {
        $this->deliveryEmail = $deliveryEmail;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeliveryEan() : string
    {
        return $this->deliveryEan;
    }

    /**
     * @param string $deliveryEan
     * @return PaymentInterface
     */
    public function setDeliveryEan(string $deliveryEan) : PaymentInterface
    {
        $this->deliveryEan = $deliveryEan;
        return $this;
    }

    /**
     * @return string
     */
    public function getShippingMethod() : string
    {
        return $this->shippingMethod;
    }

    /**
     * @param string $shippingMethod
     * @return PaymentInterface
     */
    public function setShippingMethod(string $shippingMethod) : PaymentInterface
    {
        $this->shippingMethod = $shippingMethod;
        return $this;
    }

    /**
     * @return float
     */
    public function getShippingFee() : float
    {
        return $this->shippingFee;
    }

    /**
     * @param float $shippingFee
     * @return PaymentInterface
     */
    public function setShippingFee(float $shippingFee) : PaymentInterface
    {
        $this->shippingFee = $shippingFee;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentMethod() : string
    {
        return $this->paymentMethod;
    }

    /**
     * @param string $paymentMethod
     * @return PaymentInterface
     */
    public function setPaymentMethod(string $paymentMethod) : PaymentInterface
    {
        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    /**
     * @return float
     */
    public function getPaymentFee() : float
    {
        return $this->paymentFee;
    }

    /**
     * @param float $paymentFee
     * @return PaymentInterface
     */
    public function setPaymentFee(float $paymentFee) : PaymentInterface
    {
        $this->paymentFee = $paymentFee;
        return $this;
    }

    /**
     * @return string
     */
    public function getLoadBalancerRealIp() : string
    {
        return $this->loadBalancerRealIp;
    }

    /**
     * @param string $loadBalancerRealIp
     * @return PaymentInterface
     */
    public function setLoadBalancerRealIp(string $loadBalancerRealIp) : PaymentInterface
    {
        $this->loadBalancerRealIp = $loadBalancerRealIp;
        return $this;
    }

    /**
     * @return OrderLineInterface[]
     */
    public function getOrderLines() : array
    {
        return $this->orderLines;
    }

    /**
     * @param OrderLineInterface[] $orderLines
     * @return PaymentInterface
     */
    public function setOrderLines(array $orderLines) : PaymentInterface
    {
        $this->orderLines = $orderLines;
        return $this;
    }

    /**
     * @param OrderLineInterface $orderLine
     * @return PaymentInterface
     */
    public function addOrderLine(OrderLineInterface $orderLine) : PaymentInterface
    {
        $this->orderLines[] = $orderLine;
        return $this;
    }
}