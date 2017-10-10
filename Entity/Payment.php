<?php

namespace Loevgaard\DandomainAltapayBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Loevgaard\Dandomain\Pay\PaymentRequest;

/**
 * The Payment entity is a special entity since it maps a payment from the Dandomain Payment API
 * This is also the reason why it doesn't implement an interface but extends the PaymentRequest
 * from the Dandomain Pay PHP SDK.
 *
 * Also it doesn't relate to any other entities other than PaymentLine since the Dandomain Payment API
 * POST request is not complete with all information needed to populate all the related entities, i.e. customers,
 * deliveries etc.
 *
 * @ORM\MappedSuperclass
 */
abstract class Payment extends PaymentRequest
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $apiKey;

    /**
     * @ORM\Column(type="string")
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
     * @ORM\Column(type="string")
     */
    protected $currencySymbol;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2)
     */
    protected $totalAmount;

    /**
     * @ORM\Column(type="string")
     */
    protected $callBackUrl;

    /**
     * @ORM\Column(type="string")
     */
    protected $fullCallBackOkUrl;

    /**
     * @ORM\Column(type="string")
     */
    protected $callBackOkUrl;

    /**
     * @ORM\Column(type="string")
     */
    protected $callBackServerUrl;

    /**
     * @ORM\Column(type="integer")
     */
    protected $languageId;

    /**
     * @ORM\Column(type="string")
     */
    protected $testMode;

    /**
     * @ORM\Column(type="integer")
     */
    protected $paymentGatewayCurrencyCode;

    /**
     * @ORM\Column(type="integer")
     */
    protected $cardTypeId;

    /**
     * @ORM\Column(type="string")
     */
    protected $customerRekvNr;

    /**
     * @ORM\Column(type="string")
     */
    protected $customerName;

    /**
     * @ORM\Column(type="string")
     */
    protected $customerCompany;

    /**
     * @ORM\Column(type="string")
     */
    protected $customerAddress;

    /**
     * @ORM\Column(type="string")
     */
    protected $customerAddress2;

    /**
     * @ORM\Column(type="string")
     */
    protected $customerZipCode;

    /**
     * @ORM\Column(type="string")
     */
    protected $customerCity;

    /**
     * @ORM\Column(type="integer")
     */
    protected $customerCountryId;

    /**
     * @ORM\Column(type="string")
     */
    protected $customerCountry;

    /**
     * @ORM\Column(type="string")
     */
    protected $customerPhone;

    /**
     * @ORM\Column(type="string")
     */
    protected $customerFax;

    /**
     * @ORM\Column(type="string")
     */
    protected $customerEmail;

    /**
     * @ORM\Column(type="string")
     */
    protected $customerNote;

    /**
     * @ORM\Column(type="string")
     */
    protected $customerCvrnr;

    /**
     * @ORM\Column(type="integer")
     */
    protected $customerCustTypeId;

    /**
     * @ORM\Column(type="string")
     */
    protected $customerEan;

    /**
     * @ORM\Column(type="string")
     */
    protected $customerRes1;

    /**
     * @ORM\Column(type="string")
     */
    protected $customerRes2;

    /**
     * @ORM\Column(type="string")
     */
    protected $customerRes3;

    /**
     * @ORM\Column(type="string")
     */
    protected $customerRes4;

    /**
     * @ORM\Column(type="string")
     */
    protected $customerRes5;

    /**
     * @ORM\Column(type="string")
     */
    protected $customerIp;

    /**
     * @ORM\Column(type="string")
     */
    protected $deliveryName;

    /**
     * @ORM\Column(type="string")
     */
    protected $deliveryCompany;

    /**
     * @ORM\Column(type="string")
     */
    protected $deliveryAddress;

    /**
     * @ORM\Column(type="string")
     */
    protected $deliveryAddress2;

    /**
     * @ORM\Column(type="string")
     */
    protected $deliveryZipCode;

    /**
     * @ORM\Column(type="string")
     */
    protected $deliveryCity;

    /**
     * @ORM\Column(type="integer")
     */
    protected $deliveryCountryID;

    /**
     * @ORM\Column(type="string")
     */
    protected $deliveryCountry;

    /**
     * @ORM\Column(type="string")
     */
    protected $deliveryPhone;

    /**
     * @ORM\Column(type="string")
     */
    protected $deliveryFax;

    /**
     * @ORM\Column(type="string")
     */
    protected $deliveryEmail;

    /**
     * @ORM\Column(type="string")
     */
    protected $deliveryEan;

    /**
     * @ORM\Column(type="string")
     */
    protected $shippingMethod;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2)
     */
    protected $shippingFee;

    /**
     * @ORM\Column(type="string")
     */
    protected $paymentMethod;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2)
     */
    protected $paymentFee;

    /**
     * @ORM\Column(type="string")
     */
    protected $loadBalancerRealIp;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $referrer;

    /**
     * @ORM\OneToMany(targetEntity="PaymentLine", mappedBy="payment")
     */
    protected $paymentLines;

    /**********************************
     * Properties specific to Altapay *
     *********************************/

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $altapayId;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $cardStatus;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $creditCardToken;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $creditCardMaskedPan;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $threeDSecureResult;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $liableForChargeback;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $blacklistToken;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $shop;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $terminal;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $transactionStatus;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
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
     * @ORM\Column(type="string", nullable=true)
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
     * @ORM\Column(type="string", nullable=true)
     */
    protected $cardHolderCurrencyAlpha;

    /**
     * @var float|null
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    protected $reservedAmount;

    /**
     * @var float|null
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    protected $capturedAmount;

    /**
     * @var float|null
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    protected $refundedAmount;

    /**
     * @var float|null
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
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
     * @ORM\Column(type="string", nullable=true)
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
     * @ORM\Column(type="string", nullable=true)
     */
    protected $fraudExplanation;

    public function __construct()
    {
        parent::__construct();

        $this->paymentLines = new ArrayCollection();
    }

    /**
     * Returns true if the payment can be captured.
     *
     * @todo implement this method
     *
     * @return bool
     */
    public function isCaptureable(): bool
    {
        return true;
    }

    /**
     * Returns true if the payment can be refunded.
     *
     * @todo implement this method
     *
     * @return bool
     */
    public function isRefundable(): bool
    {
        return true;
    }

    // @todo create type hints for getters and setters

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
     * @return float|null
     */
    public function getReservedAmount()
    {
        return $this->reservedAmount;
    }

    /**
     * @param float|null $reservedAmount
     *
     * @return Payment
     */
    public function setReservedAmount($reservedAmount)
    {
        $this->reservedAmount = $reservedAmount;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getCapturedAmount()
    {
        return $this->capturedAmount;
    }

    /**
     * @param float|null $capturedAmount
     *
     * @return Payment
     */
    public function setCapturedAmount($capturedAmount)
    {
        $this->capturedAmount = $capturedAmount;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getRefundedAmount()
    {
        return $this->refundedAmount;
    }

    /**
     * @param float|null $refundedAmount
     *
     * @return Payment
     */
    public function setRefundedAmount($refundedAmount)
    {
        $this->refundedAmount = $refundedAmount;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getRecurringDefaultAmount()
    {
        return $this->recurringDefaultAmount;
    }

    /**
     * @param float|null $recurringDefaultAmount
     *
     * @return Payment
     */
    public function setRecurringDefaultAmount($recurringDefaultAmount)
    {
        $this->recurringDefaultAmount = $recurringDefaultAmount;

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
}
