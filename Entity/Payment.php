<?php
namespace Loevgaard\DandomainAltapayBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping AS ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\MappedSuperclass
 */
abstract class Payment implements PaymentInterface
{
    use ORMBehaviors\Timestampable\Timestampable;

    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $apiKey;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $merchant;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $orderId;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $sessionId;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $currencySymbol;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", scale=2, precision=10)
     */
    protected $totalAmount;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $callBackUrl;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $fullCallBackOkUrl;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $callBackOkUrl;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $callBackServerUrl;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $languageId;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $testMode;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $paymentGatewayCurrencyCode;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $cardTypeId;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $customerRekvNr;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $customerName;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $customerCompany;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $customerAddress;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $customerAddress2;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $customerZipCode;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $customerCity;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $customerCountryId;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $customerCountry;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $customerPhone;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $customerFax;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $customerEmail;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $customerNote;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $customerCvrnr;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $customerCustTypeId;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $customerEan;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $customerRes1;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $customerRes2;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $customerRes3;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $customerRes4;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $customerRes5;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $customerIp;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $deliveryName;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $deliveryCompany;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $deliveryAddress;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $deliveryAddress2;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $deliveryZipCode;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $deliveryCity;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $deliveryCountryID;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $deliveryCountry;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $deliveryPhone;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $deliveryFax;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $deliveryEmail;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $deliveryEan;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $shippingMethod;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", scale=2, precision=10)
     */
    protected $shippingFee;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $paymentMethod;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", scale=2, precision=10)
     */
    protected $paymentFee;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $loadBalancerRealIp;

    /**
     * @var OrderLine[]
     */
    protected $orderLines;

    /**
     * @var Callback[]
     */
    protected $callbacks;

    public function __construct()
    {
        $this->orderLines = new ArrayCollection();
        $this->callbacks = new ArrayCollection();
    }

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
    public function getApiKey() : string
    {
        return $this->apiKey;
    }

    /**
     * @inheritdoc
     */
    public function setApiKey(string $apiKey) : PaymentInterface
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getMerchant() : string
    {
        return $this->merchant;
    }

    /**
     * @inheritdoc
     */
    public function setMerchant(string $merchant) : PaymentInterface
    {
        $this->merchant = $merchant;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getOrderId() : int
    {
        return $this->orderId;
    }

    /**
     * @inheritdoc
     */
    public function setOrderId(int $orderId) : PaymentInterface
    {
        $this->orderId = $orderId;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getSessionId() : string
    {
        return $this->sessionId;
    }

    /**
     * @inheritdoc
     */
    public function setSessionId(string $sessionId) : PaymentInterface
    {
        $this->sessionId = $sessionId;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCurrencySymbol() : string
    {
        return $this->currencySymbol;
    }

    /**
     * @inheritdoc
     */
    public function setCurrencySymbol(string $currencySymbol) : PaymentInterface
    {
        $this->currencySymbol = $currencySymbol;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getTotalAmount() : float
    {
        return $this->totalAmount;
    }

    /**
     * @inheritdoc
     */
    public function setTotalAmount(float $totalAmount) : PaymentInterface
    {
        $this->totalAmount = $totalAmount;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCallBackUrl() : string
    {
        return $this->callBackUrl;
    }

    /**
     * @inheritdoc
     */
    public function setCallBackUrl(string $callBackUrl) : PaymentInterface
    {
        $this->callBackUrl = $callBackUrl;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getFullCallBackOkUrl() : string
    {
        return $this->fullCallBackOkUrl;
    }

    /**
     * @inheritdoc
     */
    public function setFullCallBackOkUrl(string $fullCallBackOkUrl) : PaymentInterface
    {
        $this->fullCallBackOkUrl = $fullCallBackOkUrl;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCallBackOkUrl() : string
    {
        return $this->callBackOkUrl;
    }

    /**
     * @inheritdoc
     */
    public function setCallBackOkUrl(string $callBackOkUrl) : PaymentInterface
    {
        $this->callBackOkUrl = $callBackOkUrl;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCallBackServerUrl() : string
    {
        return $this->callBackServerUrl;
    }

    /**
     * @inheritdoc
     */
    public function setCallBackServerUrl(string $callBackServerUrl) : PaymentInterface
    {
        $this->callBackServerUrl = $callBackServerUrl;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getLanguageId() : int
    {
        return $this->languageId;
    }

    /**
     * @inheritdoc
     */
    public function setLanguageId(int $languageId) : PaymentInterface
    {
        $this->languageId = $languageId;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function isTestMode(): bool
    {
        return $this->testMode;
    }

    /**
     * @inheritdoc
     */
    public function setTestMode(bool $testMode) : PaymentInterface
    {
        $this->testMode = $testMode;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPaymentGatewayCurrencyCode()
    {
        return $this->paymentGatewayCurrencyCode;
    }

    /**
     * @inheritdoc
     */
    public function setPaymentGatewayCurrencyCode(int $paymentGatewayCurrencyCode) : PaymentInterface
    {
        $this->paymentGatewayCurrencyCode = $paymentGatewayCurrencyCode;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCardTypeId()
    {
        return $this->cardTypeId;
    }

    /**
     * @inheritdoc
     */
    public function setCardTypeId(int $cardTypeId) : PaymentInterface
    {
        $this->cardTypeId = $cardTypeId;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCustomerRekvNr() : string
    {
        return $this->customerRekvNr;
    }

    /**
     * @inheritdoc
     */
    public function setCustomerRekvNr(string $customerRekvNr) : PaymentInterface
    {
        $this->customerRekvNr = $customerRekvNr;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCustomerName() : string
    {
        return $this->customerName;
    }

    /**
     * @inheritdoc
     */
    public function setCustomerName(string $customerName) : PaymentInterface
    {
        $this->customerName = $customerName;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCustomerCompany() : string
    {
        return $this->customerCompany;
    }

    /**
     * @inheritdoc
     */
    public function setCustomerCompany(string $customerCompany) : PaymentInterface
    {
        $this->customerCompany = $customerCompany;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCustomerAddress() : string
    {
        return $this->customerAddress;
    }

    /**
     * @inheritdoc
     */
    public function setCustomerAddress(string $customerAddress) : PaymentInterface
    {
        $this->customerAddress = $customerAddress;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCustomerAddress2() : string
    {
        return $this->customerAddress2;
    }

    /**
     * @inheritdoc
     */
    public function setCustomerAddress2(string $customerAddress2) : PaymentInterface
    {
        $this->customerAddress2 = $customerAddress2;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCustomerZipCode() : string
    {
        return $this->customerZipCode;
    }

    /**
     * @inheritdoc
     */
    public function setCustomerZipCode(string $customerZipCode) : PaymentInterface
    {
        $this->customerZipCode = $customerZipCode;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCustomerCity() : string
    {
        return $this->customerCity;
    }

    /**
     * @inheritdoc
     */
    public function setCustomerCity(string $customerCity) : PaymentInterface
    {
        $this->customerCity = $customerCity;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCustomerCountryId()
    {
        return $this->customerCountryId;
    }

    /**
     * @inheritdoc
     */
    public function setCustomerCountryId(int $customerCountryId) : PaymentInterface
    {
        $this->customerCountryId = $customerCountryId;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCustomerCountry() : string
    {
        return $this->customerCountry;
    }

    /**
     * @inheritdoc
     */
    public function setCustomerCountry(string $customerCountry) : PaymentInterface
    {
        $this->customerCountry = $customerCountry;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCustomerPhone() : string
    {
        return $this->customerPhone;
    }

    /**
     * @inheritdoc
     */
    public function setCustomerPhone(string $customerPhone) : PaymentInterface
    {
        $this->customerPhone = $customerPhone;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCustomerFax() : string
    {
        return $this->customerFax;
    }

    /**
     * @inheritdoc
     */
    public function setCustomerFax(string $customerFax) : PaymentInterface
    {
        $this->customerFax = $customerFax;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCustomerEmail() : string
    {
        return $this->customerEmail;
    }

    /**
     * @inheritdoc
     */
    public function setCustomerEmail(string $customerEmail) : PaymentInterface
    {
        $this->customerEmail = $customerEmail;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCustomerNote() : string
    {
        return $this->customerNote;
    }

    /**
     * @inheritdoc
     */
    public function setCustomerNote(string $customerNote) : PaymentInterface
    {
        $this->customerNote = $customerNote;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCustomerCvrnr() : string
    {
        return $this->customerCvrnr;
    }

    /**
     * @inheritdoc
     */
    public function setCustomerCvrnr(string $customerCvrnr) : PaymentInterface
    {
        $this->customerCvrnr = $customerCvrnr;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCustomerCustTypeId() : int
    {
        return $this->customerCustTypeId;
    }

    /**
     * @inheritdoc
     */
    public function setCustomerCustTypeId(int $customerCustTypeId) : PaymentInterface
    {
        $this->customerCustTypeId = $customerCustTypeId;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCustomerEan() : string
    {
        return $this->customerEan;
    }

    /**
     * @inheritdoc
     */
    public function setCustomerEan(string $customerEan) : PaymentInterface
    {
        $this->customerEan = $customerEan;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCustomerRes1() : string
    {
        return $this->customerRes1;
    }

    /**
     * @inheritdoc
     */
    public function setCustomerRes1(string $customerRes1) : PaymentInterface
    {
        $this->customerRes1 = $customerRes1;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCustomerRes2() : string
    {
        return $this->customerRes2;
    }

    /**
     * @inheritdoc
     */
    public function setCustomerRes2(string $customerRes2) : PaymentInterface
    {
        $this->customerRes2 = $customerRes2;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCustomerRes3() : string
    {
        return $this->customerRes3;
    }

    /**
     * @inheritdoc
     */
    public function setCustomerRes3(string $customerRes3) : PaymentInterface
    {
        $this->customerRes3 = $customerRes3;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCustomerRes4() : string
    {
        return $this->customerRes4;
    }

    /**
     * @inheritdoc
     */
    public function setCustomerRes4(string $customerRes4) : PaymentInterface
    {
        $this->customerRes4 = $customerRes4;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCustomerRes5() : string
    {
        return $this->customerRes5;
    }

    /**
     * @inheritdoc
     */
    public function setCustomerRes5(string $customerRes5) : PaymentInterface
    {
        $this->customerRes5 = $customerRes5;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCustomerIp() : string
    {
        return $this->customerIp;
    }

    /**
     * @inheritdoc
     */
    public function setCustomerIp(string $customerIp) : PaymentInterface
    {
        $this->customerIp = $customerIp;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDeliveryName() : string
    {
        return $this->deliveryName;
    }

    /**
     * @inheritdoc
     */
    public function setDeliveryName(string $deliveryName) : PaymentInterface
    {
        $this->deliveryName = $deliveryName;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDeliveryCompany() : string
    {
        return $this->deliveryCompany;
    }

    /**
     * @inheritdoc
     */
    public function setDeliveryCompany(string $deliveryCompany) : PaymentInterface
    {
        $this->deliveryCompany = $deliveryCompany;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDeliveryAddress() : string
    {
        return $this->deliveryAddress;
    }

    /**
     * @inheritdoc
     */
    public function setDeliveryAddress(string $deliveryAddress) : PaymentInterface
    {
        $this->deliveryAddress = $deliveryAddress;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDeliveryAddress2() : string
    {
        return $this->deliveryAddress2;
    }

    /**
     * @inheritdoc
     */
    public function setDeliveryAddress2(string $deliveryAddress2) : PaymentInterface
    {
        $this->deliveryAddress2 = $deliveryAddress2;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDeliveryZipCode() : string
    {
        return $this->deliveryZipCode;
    }

    /**
     * @inheritdoc
     */
    public function setDeliveryZipCode(string $deliveryZipCode) : PaymentInterface
    {
        $this->deliveryZipCode = $deliveryZipCode;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDeliveryCity() : string
    {
        return $this->deliveryCity;
    }

    /**
     * @inheritdoc
     */
    public function setDeliveryCity(string $deliveryCity) : PaymentInterface
    {
        $this->deliveryCity = $deliveryCity;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDeliveryCountryID() : int
    {
        return $this->deliveryCountryID;
    }

    /**
     * @inheritdoc
     */
    public function setDeliveryCountryID(int $deliveryCountryID) : PaymentInterface
    {
        $this->deliveryCountryID = $deliveryCountryID;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDeliveryCountry() : string
    {
        return $this->deliveryCountry;
    }

    /**
     * @inheritdoc
     */
    public function setDeliveryCountry(string $deliveryCountry) : PaymentInterface
    {
        $this->deliveryCountry = $deliveryCountry;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDeliveryPhone() : string
    {
        return $this->deliveryPhone;
    }

    /**
     * @inheritdoc
     */
    public function setDeliveryPhone(string $deliveryPhone) : PaymentInterface
    {
        $this->deliveryPhone = $deliveryPhone;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDeliveryFax() : string
    {
        return $this->deliveryFax;
    }

    /**
     * @inheritdoc
     */
    public function setDeliveryFax(string $deliveryFax) : PaymentInterface
    {
        $this->deliveryFax = $deliveryFax;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDeliveryEmail() : string
    {
        return $this->deliveryEmail;
    }

    /**
     * @inheritdoc
     */
    public function setDeliveryEmail(string $deliveryEmail) : PaymentInterface
    {
        $this->deliveryEmail = $deliveryEmail;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDeliveryEan() : string
    {
        return $this->deliveryEan;
    }

    /**
     * @inheritdoc
     */
    public function setDeliveryEan(string $deliveryEan) : PaymentInterface
    {
        $this->deliveryEan = $deliveryEan;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getShippingMethod() : string
    {
        return $this->shippingMethod;
    }

    /**
     * @inheritdoc
     */
    public function setShippingMethod(string $shippingMethod) : PaymentInterface
    {
        $this->shippingMethod = $shippingMethod;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getShippingFee() : float
    {
        return $this->shippingFee;
    }

    /**
     * @inheritdoc
     */
    public function setShippingFee(float $shippingFee) : PaymentInterface
    {
        $this->shippingFee = $shippingFee;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPaymentMethod() : string
    {
        return $this->paymentMethod;
    }

    /**
     * @inheritdoc
     */
    public function setPaymentMethod(string $paymentMethod) : PaymentInterface
    {
        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPaymentFee() : float
    {
        return $this->paymentFee;
    }

    /**
     * @inheritdoc
     */
    public function setPaymentFee(float $paymentFee) : PaymentInterface
    {
        $this->paymentFee = $paymentFee;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getLoadBalancerRealIp() : string
    {
        return $this->loadBalancerRealIp;
    }

    /**
     * @inheritdoc
     */
    public function setLoadBalancerRealIp(string $loadBalancerRealIp) : PaymentInterface
    {
        $this->loadBalancerRealIp = $loadBalancerRealIp;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getOrderLines() : ArrayCollection
    {
        return $this->orderLines;
    }

    /**
     * @inheritdoc
     */
    public function setOrderLines(ArrayCollection $orderLines) : PaymentInterface
    {
        $this->orderLines = $orderLines;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addOrderLine(OrderLineInterface $orderLine) : PaymentInterface
    {
        $this->orderLines[] = $orderLine;
        $orderLine->setPayment($this);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCallbacks() : ArrayCollection
    {
        return $this->callbacks;
    }

    /**
     * @inheritdoc
     */
    public function setCallbacks(ArrayCollection $callbacks) : PaymentInterface
    {
        $this->callbacks = $callbacks;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addCallback(CallbackInterface $callback) : PaymentInterface
    {
        $this->callbacks[] = $callback;
        $callback->setPayment($this);
        return $this;
    }
}