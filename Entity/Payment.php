<?php

namespace Loevgaard\DandomainAltapayBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Loevgaard\Dandomain\Pay\Model\Payment as BasePayment;
use Money\Money;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The Payment entity is a special entity since it maps a payment from the Dandomain Payment API
 * This is also the reason why it doesn't implement an interface but extends the PaymentRequest
 * from the Dandomain Pay PHP SDK.
 *
 * Also it doesn't relate to any other entities other than PaymentLine since the Dandomain Payment API
 * POST request is not complete with all information needed to populate all the related entities, i.e. customers,
 * deliveries etc.
 *
 * @ORM\Table(name="dandomain_altapay_payments")
 * @ORM\Entity()
 */
class Payment extends BasePayment
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="191")
     *
     * @ORM\Column(type="string", length=191)
     */
    protected $apiKey;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="191")
     *
     * @ORM\Column(type="string", length=191)
     */
    protected $merchant;

    /**
     * @ORM\Column(type="integer")
     */
    protected $orderId;

    /**
     * @ORM\Column(type="text")
     */
    protected $sessionId;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="191")
     *
     * @ORM\Column(type="string", length=191)
     */
    protected $currencySymbol;

    /**
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="integer")
     */
    protected $totalAmountAmount;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="191")
     *
     * @ORM\Column(type="string", length=191)
     */
    protected $callBackUrl;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="191")
     *
     * @ORM\Column(type="string", length=191)
     */
    protected $fullCallBackOkUrl;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="191")
     *
     * @ORM\Column(type="string", length=191)
     */
    protected $callBackOkUrl;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="191")
     *
     * @ORM\Column(type="string", length=191)
     */
    protected $callBackServerUrl;

    /**
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="integer")
     */
    protected $languageId;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="191")
     *
     * @ORM\Column(type="string", length=191)
     */
    protected $testMode;

    /**
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="integer")
     */
    protected $paymentGatewayCurrencyCode;

    /**
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="integer")
     */
    protected $cardTypeId;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="191")
     *
     * @ORM\Column(type="string", length=191)
     */
    protected $customerRekvNr;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="191")
     *
     * @ORM\Column(type="string", length=191)
     */
    protected $customerName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $customerCompany;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="191")
     *
     * @ORM\Column(type="string", length=191)
     */
    protected $customerAddress;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $customerAddress2;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="191")
     *
     * @ORM\Column(type="string", length=191)
     */
    protected $customerZipCode;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="191")
     *
     * @ORM\Column(type="string", length=191)
     */
    protected $customerCity;

    /**
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="integer")
     */
    protected $customerCountryId;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="191")
     *
     * @ORM\Column(type="string", length=191)
     */
    protected $customerCountry;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="191")
     *
     * @ORM\Column(type="string", length=191)
     */
    protected $customerPhone;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $customerFax;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="191")
     *
     * @ORM\Column(type="string", length=191)
     */
    protected $customerEmail;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $customerNote;

    /**
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $customerCvrnr;

    /**
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="integer")
     */
    protected $customerCustTypeId;

    /**
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $customerEan;

    /**
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $customerRes1;

    /**
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $customerRes2;

    /**
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $customerRes3;

    /**
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $customerRes4;

    /**
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $customerRes5;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="191")
     *
     * @ORM\Column(type="string", length=191)
     */
    protected $customerIp;

    /**
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $deliveryName;

    /**
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $deliveryCompany;

    /**
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $deliveryAddress;

    /**
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $deliveryAddress2;

    /**
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $deliveryZipCode;

    /**
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $deliveryCity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $deliveryCountryID;

    /**
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $deliveryCountry;

    /**
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $deliveryPhone;

    /**
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $deliveryFax;

    /**
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $deliveryEmail;

    /**
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $deliveryEan;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="191")
     *
     * @ORM\Column(type="string", length=191)
     */
    protected $shippingMethod;

    /**
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="integer")
     */
    protected $shippingFeeAmount;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="191")
     *
     * @ORM\Column(type="string", length=191)
     */
    protected $paymentMethod;

    /**
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="integer")
     */
    protected $paymentFeeAmount;

    /**
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $loadBalancerRealIp;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $referrer;

    /**
     * @ORM\OneToMany(targetEntity="PaymentLine", mappedBy="payment", cascade={"persist", "remove"}, fetch="EAGER")
     */
    protected $paymentLines;

    /**********************************
     * Properties specific to Altapay *
     *********************************/

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, unique=true, length=191)
     */
    protected $altapayId;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $cardStatus;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $creditCardToken;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $creditCardMaskedPan;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $threeDSecureResult;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $liableForChargeback;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $blacklistToken;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $shop;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $terminal;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $transactionStatus;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $reasonCode;

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $merchantCurrency;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $merchantCurrencyAlpha;

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $cardHolderCurrency;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $cardHolderCurrencyAlpha;

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer")
     */
    protected $reservedAmount;

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer")
     */
    protected $capturedAmount;

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer")
     */
    protected $refundedAmount;

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer")
     */
    protected $recurringDefaultAmount;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $createdDate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updatedDate;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $paymentNature;

    /**
     * @var bool|null
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $supportsRefunds;

    /**
     * @var bool|null
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $supportsRelease;

    /**
     * @var bool|null
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $supportsMultipleCaptures;

    /**
     * @var bool|null
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $supportsMultipleRefunds;

    /**
     * @var float|null
     *
     * @ORM\Column(type="float", nullable=true)
     */
    protected $fraudRiskScore;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=191)
     */
    protected $fraudExplanation;

    public function __construct()
    {
        parent::__construct();

        $this->paymentLines = new ArrayCollection();
        $this->reservedAmount = 0;
        $this->capturedAmount = 0;
        $this->refundedAmount = 0;
        $this->recurringDefaultAmount = 0;
    }

    public static function createPaymentLine(string $productNumber, string $name, int $quantity, Money $price, int $vat)
    {
        return new PaymentLine($productNumber, $name, $quantity, $price, $vat);
    }

    /**
     * Returns true if the payment can be captured.
     *
     * @return bool
     */
    public function isCaptureable(): bool
    {
        if ($this->capturableAmount()->getAmount() <= 0) {
            return false;
        }

        if ($this->getCapturedAmount()->getAmount() > 0 && !$this->supportsMultipleCaptures) {
            return false;
        }

        return true;
    }

    /**
     * Returns true if the payment can be refunded.
     *
     * @return bool
     */
    public function isRefundable(): bool
    {
        if ($this->refundableAmount()->getAmount() <= 0) {
            return false;
        }

        if ($this->getRefundedAmount()->getAmount() > 0 && !$this->supportsMultipleRefunds) {
            return false;
        }

        return true;
    }

    /**
     * @return Money
     */
    public function refundableAmount()
    {
        $capturedAmount = $this->getCapturedAmount();

        return $capturedAmount->subtract($this->getRefundedAmount());
    }

    /**
     * @return Money
     */
    public function capturableAmount(): Money
    {
        $reservedAmount = $this->getReservedAmount();
        $realCapturedAmount = $this->getCapturedAmount()->subtract($this->getRefundedAmount());

        return $reservedAmount->subtract($realCapturedAmount);
    }

    // @todo create type hints for getters and setters

    public function setTotalAmount(Money $totalAmount): BasePayment
    {
        parent::setTotalAmount($totalAmount);

        $this->totalAmountAmount = $totalAmount->getAmount();

        return $this;
    }

    public function getTotalAmount(): ?Money
    {
        return $this->createMoney((int) $this->totalAmountAmount);
    }

    public function setShippingFee(Money $shippingFee): BasePayment
    {
        parent::setShippingFee($shippingFee);

        $this->shippingFeeAmount = $shippingFee->getAmount();

        return $this;
    }

    public function getShippingFee(): ?Money
    {
        return $this->createMoney((int) $this->shippingFeeAmount);
    }

    public function setPaymentFee(Money $paymentFee): BasePayment
    {
        parent::setPaymentFee($paymentFee);

        $this->paymentFeeAmount = $paymentFee->getAmount();

        return $this;
    }

    public function getPaymentFee(): ?Money
    {
        return $this->createMoney((int) $this->paymentFeeAmount);
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Payment
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getAltapayId()
    {
        return $this->altapayId;
    }

    /**
     * @param null|string $altapayId
     *
     * @return Payment
     */
    public function setAltapayId($altapayId)
    {
        $this->altapayId = $altapayId;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getCardStatus()
    {
        return $this->cardStatus;
    }

    /**
     * @param null|string $cardStatus
     *
     * @return Payment
     */
    public function setCardStatus($cardStatus)
    {
        $this->cardStatus = $cardStatus;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getCreditCardToken()
    {
        return $this->creditCardToken;
    }

    /**
     * @param null|string $creditCardToken
     *
     * @return Payment
     */
    public function setCreditCardToken($creditCardToken)
    {
        $this->creditCardToken = $creditCardToken;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getCreditCardMaskedPan()
    {
        return $this->creditCardMaskedPan;
    }

    /**
     * @param null|string $creditCardMaskedPan
     *
     * @return Payment
     */
    public function setCreditCardMaskedPan($creditCardMaskedPan)
    {
        $this->creditCardMaskedPan = $creditCardMaskedPan;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getThreeDSecureResult()
    {
        return $this->threeDSecureResult;
    }

    /**
     * @param null|string $threeDSecureResult
     *
     * @return Payment
     */
    public function setThreeDSecureResult($threeDSecureResult)
    {
        $this->threeDSecureResult = $threeDSecureResult;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getLiableForChargeback()
    {
        return $this->liableForChargeback;
    }

    /**
     * @param null|string $liableForChargeback
     *
     * @return Payment
     */
    public function setLiableForChargeback($liableForChargeback)
    {
        $this->liableForChargeback = $liableForChargeback;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getBlacklistToken()
    {
        return $this->blacklistToken;
    }

    /**
     * @param null|string $blacklistToken
     *
     * @return Payment
     */
    public function setBlacklistToken($blacklistToken)
    {
        $this->blacklistToken = $blacklistToken;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getShop()
    {
        return $this->shop;
    }

    /**
     * @param null|string $shop
     *
     * @return Payment
     */
    public function setShop($shop)
    {
        $this->shop = $shop;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getTerminal()
    {
        return $this->terminal;
    }

    /**
     * @param null|string $terminal
     *
     * @return Payment
     */
    public function setTerminal($terminal)
    {
        $this->terminal = $terminal;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getTransactionStatus()
    {
        return $this->transactionStatus;
    }

    /**
     * @param null|string $transactionStatus
     *
     * @return Payment
     */
    public function setTransactionStatus($transactionStatus)
    {
        $this->transactionStatus = $transactionStatus;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getReasonCode()
    {
        return $this->reasonCode;
    }

    /**
     * @param null|string $reasonCode
     *
     * @return Payment
     */
    public function setReasonCode($reasonCode)
    {
        $this->reasonCode = $reasonCode;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMerchantCurrency()
    {
        return $this->merchantCurrency;
    }

    /**
     * @param int|null $merchantCurrency
     *
     * @return Payment
     */
    public function setMerchantCurrency($merchantCurrency)
    {
        $this->merchantCurrency = $merchantCurrency;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getMerchantCurrencyAlpha()
    {
        return $this->merchantCurrencyAlpha;
    }

    /**
     * @param null|string $merchantCurrencyAlpha
     *
     * @return Payment
     */
    public function setMerchantCurrencyAlpha($merchantCurrencyAlpha)
    {
        $this->merchantCurrencyAlpha = $merchantCurrencyAlpha;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCardHolderCurrency()
    {
        return $this->cardHolderCurrency;
    }

    /**
     * @param int|null $cardHolderCurrency
     *
     * @return Payment
     */
    public function setCardHolderCurrency($cardHolderCurrency)
    {
        $this->cardHolderCurrency = $cardHolderCurrency;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getCardHolderCurrencyAlpha()
    {
        return $this->cardHolderCurrencyAlpha;
    }

    /**
     * @param null|string $cardHolderCurrencyAlpha
     *
     * @return Payment
     */
    public function setCardHolderCurrencyAlpha($cardHolderCurrencyAlpha)
    {
        $this->cardHolderCurrencyAlpha = $cardHolderCurrencyAlpha;

        return $this;
    }

    /**
     * @return Money|null
     */
    public function getReservedAmount(): ?Money
    {
        return $this->createMoney((int) $this->reservedAmount);
    }

    /**
     * @param Money $reservedAmount
     *
     * @return Payment
     */
    public function setReservedAmount(Money $reservedAmount)
    {
        $this->reservedAmount = $reservedAmount->getAmount();

        return $this;
    }

    /**
     * @return Money|null
     */
    public function getCapturedAmount(): ?Money
    {
        return $this->createMoney((int) $this->capturedAmount);
    }

    /**
     * @param Money $capturedAmount
     *
     * @return Payment
     */
    public function setCapturedAmount(Money $capturedAmount)
    {
        $this->capturedAmount = $capturedAmount->getAmount();

        return $this;
    }

    /**
     * @return Money|null
     */
    public function getRefundedAmount(): ?Money
    {
        return $this->createMoney((int) $this->refundedAmount);
    }

    /**
     * @param Money $refundedAmount
     *
     * @return Payment
     */
    public function setRefundedAmount(Money $refundedAmount)
    {
        $this->refundedAmount = $refundedAmount->getAmount();

        return $this;
    }

    /**
     * @return Money|null
     */
    public function getRecurringDefaultAmount(): ?Money
    {
        return $this->createMoney((int) $this->recurringDefaultAmount);
    }

    /**
     * @param Money $recurringDefaultAmount
     *
     * @return Payment
     */
    public function setRecurringDefaultAmount(Money $recurringDefaultAmount)
    {
        $this->recurringDefaultAmount = $recurringDefaultAmount->getAmount();

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * @param \DateTimeInterface|null $createdDate
     *
     * @return Payment
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    /**
     * @param \DateTimeInterface|null $updatedDate
     *
     * @return Payment
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = $updatedDate;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPaymentNature()
    {
        return $this->paymentNature;
    }

    /**
     * @param null|string $paymentNature
     *
     * @return Payment
     */
    public function setPaymentNature($paymentNature)
    {
        $this->paymentNature = $paymentNature;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getSupportsRefunds()
    {
        return $this->supportsRefunds;
    }

    /**
     * @param bool|null $supportsRefunds
     *
     * @return Payment
     */
    public function setSupportsRefunds($supportsRefunds)
    {
        $this->supportsRefunds = $supportsRefunds;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getSupportsRelease()
    {
        return $this->supportsRelease;
    }

    /**
     * @param bool|null $supportsRelease
     *
     * @return Payment
     */
    public function setSupportsRelease($supportsRelease)
    {
        $this->supportsRelease = $supportsRelease;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getSupportsMultipleCaptures()
    {
        return $this->supportsMultipleCaptures;
    }

    /**
     * @param bool|null $supportsMultipleCaptures
     *
     * @return Payment
     */
    public function setSupportsMultipleCaptures($supportsMultipleCaptures)
    {
        $this->supportsMultipleCaptures = $supportsMultipleCaptures;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getSupportsMultipleRefunds()
    {
        return $this->supportsMultipleRefunds;
    }

    /**
     * @param bool|null $supportsMultipleRefunds
     *
     * @return Payment
     */
    public function setSupportsMultipleRefunds($supportsMultipleRefunds)
    {
        $this->supportsMultipleRefunds = $supportsMultipleRefunds;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getFraudRiskScore()
    {
        return $this->fraudRiskScore;
    }

    /**
     * @param float|null $fraudRiskScore
     *
     * @return Payment
     */
    public function setFraudRiskScore($fraudRiskScore)
    {
        $this->fraudRiskScore = $fraudRiskScore;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getFraudExplanation()
    {
        return $this->fraudExplanation;
    }

    /**
     * @param null|string $fraudExplanation
     *
     * @return Payment
     */
    public function setFraudExplanation($fraudExplanation)
    {
        $this->fraudExplanation = $fraudExplanation;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function getCurrency(): ?string
    {
        if ($this->currencySymbol) {
            return $this->currencySymbol;
        }

        if ($this->merchantCurrencyAlpha) {
            return $this->merchantCurrencyAlpha;
        }

        return null;
    }
}
