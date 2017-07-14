<?php
namespace Loevgaard\DandomainAltapayBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\MappedSuperclass
 */
abstract class Callback implements CallbackInterface
{
    use ORMBehaviors\Timestampable\Timestampable;

    /**
     * @var mixed
     */
    protected $id;

    /**
     * The order id will always be an integer in Dandomain so we use type int
     * instead of string (although Altapay handles order id as string)
     *
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $orderId;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    protected $amount;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $currency;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $language;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=true)
     */
    protected $transactionInfo;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $errorMessage;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $merchantErrorMessage;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $cardholderMessageMustBeShown;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $transactionId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $paymentStatus;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $maskedCreditCard;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $blacklistToken;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $creditCardToken;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $nature;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $requireCapture;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $xml;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $checksum;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    protected $fraudRiskScore;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $fraudExplanation;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $fraudRecommendation;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $avsCode;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $avsText;

    /**
     * This contains the request string
     *
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $request;

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
    public function getOrderId(): ?int
    {
        return $this->orderId;
    }

    /**
     * @inheritdoc
     */
    public function setOrderId(int $orderId) : CallbackInterface
    {
        $this->orderId = $orderId;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    /**
     * @inheritdoc
     */
    public function setAmount(float $amount) : CallbackInterface
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCurrency(): ?int
    {
        return $this->currency;
    }

    /**
     * @inheritdoc
     */
    public function setCurrency(int $currency) : CallbackInterface
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * @inheritdoc
     */
    public function setLanguage(string $language) : CallbackInterface
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getTransactionInfo(): ?array
    {
        return $this->transactionInfo;
    }

    /**
     * @inheritdoc
     */
    public function setTransactionInfo(array $transactionInfo) : CallbackInterface
    {
        $this->transactionInfo = $transactionInfo;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @inheritdoc
     */
    public function setStatus(string $status) : CallbackInterface
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    /**
     * @inheritdoc
     */
    public function setErrorMessage(string $errorMessage) : CallbackInterface
    {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getMerchantErrorMessage(): ?string
    {
        return $this->merchantErrorMessage;
    }

    /**
     * @inheritdoc
     */
    public function setMerchantErrorMessage(string $merchantErrorMessage) : CallbackInterface
    {
        $this->merchantErrorMessage = $merchantErrorMessage;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function isCardholderMessageMustBeShown(): ?bool
    {
        return $this->cardholderMessageMustBeShown;
    }

    /**
     * @inheritdoc
     */
    public function setCardholderMessageMustBeShown(bool $cardholderMessageMustBeShown) : CallbackInterface
    {
        $this->cardholderMessageMustBeShown = $cardholderMessageMustBeShown;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    /**
     * @inheritdoc
     */
    public function setTransactionId(string $transactionId) : CallbackInterface
    {
        $this->transactionId = $transactionId;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @inheritdoc
     */
    public function setType(string $type) : CallbackInterface
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPaymentStatus(): ?string
    {
        return $this->paymentStatus;
    }

    /**
     * @inheritdoc
     */
    public function setPaymentStatus(string $paymentStatus) : CallbackInterface
    {
        $this->paymentStatus = $paymentStatus;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getMaskedCreditCard(): ?string
    {
        return $this->maskedCreditCard;
    }

    /**
     * @inheritdoc
     */
    public function setMaskedCreditCard(string $maskedCreditCard) : CallbackInterface
    {
        $this->maskedCreditCard = $maskedCreditCard;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getBlacklistToken(): ?string
    {
        return $this->blacklistToken;
    }

    /**
     * @inheritdoc
     */
    public function setBlacklistToken(string $blacklistToken) : CallbackInterface
    {
        $this->blacklistToken = $blacklistToken;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCreditCardToken(): ?string
    {
        return $this->creditCardToken;
    }

    /**
     * @inheritdoc
     */
    public function setCreditCardToken(string $creditCardToken) : CallbackInterface
    {
        $this->creditCardToken = $creditCardToken;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getNature(): ?string
    {
        return $this->nature;
    }

    /**
     * @inheritdoc
     */
    public function setNature(string $nature) : CallbackInterface
    {
        $this->nature = $nature;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function isRequireCapture(): ?bool
    {
        return $this->requireCapture;
    }

    /**
     * @inheritdoc
     */
    public function setRequireCapture(bool $requireCapture) : CallbackInterface
    {
        $this->requireCapture = $requireCapture;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getXml(): ?string
    {
        return $this->xml;
    }

    /**
     * @inheritdoc
     */
    public function setXml(string $xml) : CallbackInterface
    {
        $this->xml = $xml;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getChecksum(): ?string
    {
        return $this->checksum;
    }

    /**
     * @inheritdoc
     */
    public function setChecksum(string $checksum) : CallbackInterface
    {
        $this->checksum = $checksum;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getFraudRiskScore(): ?float
    {
        return $this->fraudRiskScore;
    }

    /**
     * @inheritdoc
     */
    public function setFraudRiskScore(float $fraudRiskScore) : CallbackInterface
    {
        $this->fraudRiskScore = $fraudRiskScore;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getFraudExplanation(): ?string
    {
        return $this->fraudExplanation;
    }

    /**
     * @inheritdoc
     */
    public function setFraudExplanation(string $fraudExplanation) : CallbackInterface
    {
        $this->fraudExplanation = $fraudExplanation;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getFraudRecommendation(): ?string
    {
        return $this->fraudRecommendation;
    }

    /**
     * @inheritdoc
     */
    public function setFraudRecommendation(string $fraudRecommendation) : CallbackInterface
    {
        $this->fraudRecommendation = $fraudRecommendation;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getAvsCode(): ?string
    {
        return $this->avsCode;
    }

    /**
     * @inheritdoc
     */
    public function setAvsCode(string $avsCode) : CallbackInterface
    {
        $this->avsCode = $avsCode;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getAvsText(): ?string
    {
        return $this->avsText;
    }

    /**
     * @inheritdoc
     */
    public function setAvsText(string $avsText) : CallbackInterface
    {
        $this->avsText = $avsText;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getRequest() : ?string
    {
        return $this->request;
    }

    /**
     * @inheritdoc
     */
    public function setRequest(string $request) : CallbackInterface
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPayment(): ?PaymentInterface
    {
        return $this->payment;
    }

    /**
     * @inheritdoc
     */
    public function setPayment(PaymentInterface $payment) : CallbackInterface
    {
        $this->payment = $payment;
        return $this;
    }
}