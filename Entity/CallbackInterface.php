<?php

namespace Loevgaard\DandomainAltapayBundle\Entity;

use Loevgaard\DandomainFoundationBundle\Model\Payment;

interface CallbackInterface
{
    /**
     * Returns unique callback id.
     *
     * @return mixed
     */
    public function getId();

    /**
     * Sets the callback id.
     *
     * @param $id
     *
     * @return CallbackInterface
     */
    public function setId($id): self;

    /**
     * @return int
     */
    public function getOrderId(): ?int;

    /**
     * @param int $orderId
     *
     * @return CallbackInterface
     */
    public function setOrderId(int $orderId): self;

    /**
     * @return float
     */
    public function getAmount(): ?float;

    /**
     * @param float $amount
     *
     * @return CallbackInterface
     */
    public function setAmount(float $amount): self;

    /**
     * @return int
     */
    public function getCurrency(): ?int;

    /**
     * @param int $currency
     *
     * @return CallbackInterface
     */
    public function setCurrency(int $currency): self;

    /**
     * @return string
     */
    public function getLanguage(): ?string;

    /**
     * @param string $language
     *
     * @return CallbackInterface
     */
    public function setLanguage(string $language): self;

    /**
     * @return array
     */
    public function getTransactionInfo(): ?array;

    /**
     * @param array $transactionInfo
     *
     * @return CallbackInterface
     */
    public function setTransactionInfo(array $transactionInfo): self;

    /**
     * @return string
     */
    public function getStatus(): ?string;

    /**
     * @param string $status
     *
     * @return CallbackInterface
     */
    public function setStatus(string $status): self;

    /**
     * @return string
     */
    public function getErrorMessage(): ?string;

    /**
     * @param string $errorMessage
     *
     * @return CallbackInterface
     */
    public function setErrorMessage(string $errorMessage): self;

    /**
     * @return string
     */
    public function getMerchantErrorMessage(): ?string;

    /**
     * @param string $merchantErrorMessage
     *
     * @return CallbackInterface
     */
    public function setMerchantErrorMessage(string $merchantErrorMessage): self;

    /**
     * @return bool
     */
    public function isCardholderMessageMustBeShown(): ?bool;

    /**
     * @param bool $cardholderMessageMustBeShown
     *
     * @return CallbackInterface
     */
    public function setCardholderMessageMustBeShown(bool $cardholderMessageMustBeShown): self;

    /**
     * @return string
     */
    public function getTransactionId(): ?string;

    /**
     * @param string $transactionId
     *
     * @return CallbackInterface
     */
    public function setTransactionId(string $transactionId): self;

    /**
     * @return string
     */
    public function getType(): ?string;

    /**
     * @param string $type
     *
     * @return CallbackInterface
     */
    public function setType(string $type): self;

    /**
     * @return string
     */
    public function getPaymentStatus(): ?string;

    /**
     * @param string $paymentStatus
     *
     * @return CallbackInterface
     */
    public function setPaymentStatus(string $paymentStatus): self;

    /**
     * @return string
     */
    public function getMaskedCreditCard(): ?string;

    /**
     * @param string $maskedCreditCard
     *
     * @return CallbackInterface
     */
    public function setMaskedCreditCard(string $maskedCreditCard): self;

    /**
     * @return string
     */
    public function getBlacklistToken(): ?string;

    /**
     * @param string $blacklistToken
     *
     * @return CallbackInterface
     */
    public function setBlacklistToken(string $blacklistToken): self;

    /**
     * @return string
     */
    public function getCreditCardToken(): ?string;

    /**
     * @param string $creditCardToken
     *
     * @return CallbackInterface
     */
    public function setCreditCardToken(string $creditCardToken): self;

    /**
     * @return string
     */
    public function getNature(): ?string;

    /**
     * @param string $nature
     *
     * @return CallbackInterface
     */
    public function setNature(string $nature): self;

    /**
     * @return bool
     */
    public function isRequireCapture(): ?bool;

    /**
     * @param bool $requireCapture
     *
     * @return CallbackInterface
     */
    public function setRequireCapture(bool $requireCapture): self;

    /**
     * @return string
     */
    public function getXml(): ?string;

    /**
     * @param string $xml
     *
     * @return CallbackInterface
     */
    public function setXml(string $xml): self;

    /**
     * @return string
     */
    public function getChecksum(): ?string;

    /**
     * @param string $checksum
     *
     * @return CallbackInterface
     */
    public function setChecksum(string $checksum): self;

    /**
     * @return float
     */
    public function getFraudRiskScore(): ?float;

    /**
     * @param float $fraudRiskScore
     *
     * @return CallbackInterface
     */
    public function setFraudRiskScore(float $fraudRiskScore): self;

    /**
     * @return string
     */
    public function getFraudExplanation(): ?string;

    /**
     * @param string $fraudExplanation
     *
     * @return CallbackInterface
     */
    public function setFraudExplanation(string $fraudExplanation): self;

    /**
     * @return string
     */
    public function getFraudRecommendation(): ?string;

    /**
     * @param string $fraudRecommendation
     *
     * @return CallbackInterface
     */
    public function setFraudRecommendation(string $fraudRecommendation): self;

    /**
     * @return string
     */
    public function getAvsCode(): ?string;

    /**
     * @param string $avsCode
     *
     * @return CallbackInterface
     */
    public function setAvsCode(string $avsCode): self;

    /**
     * @return string
     */
    public function getAvsText(): ?string;

    /**
     * @param string $avsText
     *
     * @return CallbackInterface
     */
    public function setAvsText(string $avsText): self;

    /**
     * @return string
     */
    public function getRequest(): ?string;

    /**
     * @param string $request
     *
     * @return CallbackInterface
     */
    public function setRequest(string $request): self;

    /**
     * @return Payment
     */
    public function getPayment(): ?Payment;

    /**
     * @param Payment $payment
     *
     * @return CallbackInterface
     */
    public function setPayment(Payment $payment): self;
}
