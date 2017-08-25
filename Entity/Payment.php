<?php
namespace Loevgaard\DandomainAltapayBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping AS ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Loevgaard\DandomainFoundationBundle\Model\OrderInterface;

/**
 * @todo move this entity to dandomain foundation bundle
 *
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
     * @var OrderInterface
     *
     * @todo can we make the orm definition?
     */
    protected $order;

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
     */
    protected $loadBalancerRealIp;

    /**
     * @var Callback[]
     */
    protected $callbacks;

    public function __construct()
    {
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
    public function getOrder() : OrderInterface
    {
        return $this->order;
    }

    /**
     * @inheritdoc
     */
    public function setOrder(OrderInterface $order) : PaymentInterface
    {
        $this->order = $order;
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
    public function getLoadBalancerRealIp(): string
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