<?php

namespace Loevgaard\DandomainAltapayBundle\PayloadGenerator;

use Loevgaard\AltaPay\Payload\OrderLine as OrderLinePayload;
use Loevgaard\AltaPay\Payload\PaymentRequest as PaymentRequestPayload;
use Loevgaard\AltaPay\Payload\PaymentRequest\Config as ConfigPayload;
use Loevgaard\AltaPay\Payload\PaymentRequest\CustomerInfo as CustomerInfoPayload;
use Loevgaard\Dandomain\Pay\Helper\ChecksumHelper;
use Loevgaard\Dandomain\Pay\Model\Payment as DandomainPayment;
use Loevgaard\DandomainAltapayBundle\Entity\Payment;
use Loevgaard\DandomainAltapayBundle\Entity\Terminal;
use Money\Money;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class PaymentRequestPayloadGenerator implements PayloadGeneratorInterface
{
    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var DandomainPayment
     */
    protected $dandomainPayment;

    /**
     * @var Terminal
     */
    protected $terminal;

    /**
     * @var Payment
     */
    protected $payment;

    /**
     * @var ChecksumHelper
     */
    protected $checksumHelper;

    /**
     * @var string
     */
    protected $cookiePaymentId;

    /**
     * @var string
     */
    protected $cookieChecksumComplete;

    public function __construct(
        RouterInterface $router,
        DandomainPayment $paymentRequest,
        Terminal $terminal,
        Payment $payment,
        ChecksumHelper $checksumHelper,
        string $cookiePaymentId,
        string $cookieChecksumComplete
    ) {
        $this->router = $router;
        $this->dandomainPayment = $paymentRequest;
        $this->terminal = $terminal;
        $this->payment = $payment;
        $this->checksumHelper = $checksumHelper;
        $this->cookiePaymentId = $cookiePaymentId;
        $this->cookieChecksumComplete = $cookieChecksumComplete;
    }

    /**
     * @return PaymentRequestPayload
     */
    public function generate(): PaymentRequestPayload
    {
        $paymentRequestPayload = new PaymentRequestPayload(
            $this->terminal->getTitle(),
            $this->dandomainPayment->getOrderId(),
            $this->dandomainPayment->getTotalAmount()
        );

        foreach ($this->dandomainPayment->getPaymentLines() as $paymentLine) {
            $orderLinePayload = $this->createOrderLine(
                $paymentLine->getName(),
                $paymentLine->getProductNumber(),
                $paymentLine->getQuantity(),
                $paymentLine->getPriceExclVat(),
                $paymentLine->getVat()
            );

            $paymentRequestPayload->addOrderLine($orderLinePayload);
        }

        // add payment fee as an order line if it's set
        if ($this->dandomainPayment->getPaymentFee() && 0 !== (int) $this->dandomainPayment->getPaymentFee()->getAmount()) {
            $orderLinePayload = $this->createOrderLine(
                $this->dandomainPayment->getPaymentMethod(),
                $this->dandomainPayment->getPaymentMethod(),
                1,
                $this->dandomainPayment->getPaymentFee(),
                null,
                OrderLinePayload::GOODS_TYPE_HANDLING
            );
            $paymentRequestPayload->addOrderLine($orderLinePayload);
        }

        // add shipping fee as an order line if it's set
        if ($this->dandomainPayment->getShippingFee() && 0 !== (int) $this->dandomainPayment->getShippingFee()->getAmount()) {
            $orderLinePayload = $this->createOrderLine(
                $this->dandomainPayment->getShippingMethod(),
                $this->dandomainPayment->getShippingMethod(),
                1,
                $this->dandomainPayment->getShippingFee(),
                null,
                OrderLinePayload::GOODS_TYPE_SHIPMENT
            );
            $paymentRequestPayload->addOrderLine($orderLinePayload);
        }

        $customerNames = explode(' ', $this->dandomainPayment->getCustomerName(), 2);
        $shippingNames = explode(' ', $this->dandomainPayment->getDeliveryName(), 2);

        $customerInfoPayload = $this->createCustomerInfo(
            $customerNames[0] ?? '',
            $customerNames[1] ?? '',
            $this->dandomainPayment->getCustomerAddress().($this->dandomainPayment->getCustomerAddress2() ? "\r\n".$this->dandomainPayment->getCustomerAddress2() : ''),
            $this->dandomainPayment->getCustomerZipCode(),
            $this->dandomainPayment->getCustomerCity(),
            $this->dandomainPayment->getCustomerCountryCode(),
            $shippingNames[0] ?? '',
            $shippingNames[1] ?? '',
            $this->dandomainPayment->getDeliveryAddress().($this->dandomainPayment->getDeliveryAddress2() ? "\r\n".$this->dandomainPayment->getDeliveryAddress2() : ''),
            $this->dandomainPayment->getDeliveryZipCode(),
            $this->dandomainPayment->getDeliveryCity(),
            $this->dandomainPayment->getDeliveryCountryCode()
        );
        $paymentRequestPayload->setCustomerInfo($customerInfoPayload);

        $configPayload = $this->createConfig(
            $this->router->generate('loevgaard_dandomain_altapay_callback_form', [], UrlGeneratorInterface::ABSOLUTE_URL),
            $this->router->generate('loevgaard_dandomain_altapay_callback_ok', [], UrlGeneratorInterface::ABSOLUTE_URL),
            $this->router->generate('loevgaard_dandomain_altapay_callback_fail', [], UrlGeneratorInterface::ABSOLUTE_URL),
            $this->router->generate('loevgaard_dandomain_altapay_callback_redirect', [], UrlGeneratorInterface::ABSOLUTE_URL),
            $this->router->generate('loevgaard_dandomain_altapay_callback_open', [], UrlGeneratorInterface::ABSOLUTE_URL),
            $this->router->generate('loevgaard_dandomain_altapay_callback_notification', [], UrlGeneratorInterface::ABSOLUTE_URL)
        );
        $paymentRequestPayload->setConfig($configPayload);

        $paymentRequestPayload
            ->setCookiePart($this->cookiePaymentId, $this->payment->getId())
            ->setCookiePart($this->cookieChecksumComplete, $this->checksumHelper->getChecksum2())
        ;

        return $paymentRequestPayload;
    }

    /**
     * @param string      $description
     * @param string      $itemId
     * @param string      $quantity
     * @param Money       $unitPrice
     * @param float|null  $taxPercent
     * @param string|null $goodsType
     *
     * @return OrderLinePayload
     */
    protected function createOrderLine(
        string $description,
        string $itemId,
        string $quantity,
        Money $unitPrice,
        float $taxPercent = null,
        string $goodsType = null
    ): OrderLinePayload {
        $payload = new OrderLinePayload($description, $itemId, $quantity, $unitPrice);

        if ($taxPercent) {
            $payload->setTaxPercent($taxPercent);
        }

        if ($goodsType) {
            $payload->setGoodsType($goodsType);
        }

        return $payload;
    }

    /**
     * @param string $billingFirstName
     * @param string $billingLastName
     * @param string $billingAddress
     * @param string $billingPostal
     * @param string $billingCity
     * @param string $billingCountry
     * @param string $shippingFirstName
     * @param string $shippingLastName
     * @param string $shippingAddress
     * @param string $shippingPostal
     * @param string $shippingCity
     * @param string $shippingCountry
     *
     * @return CustomerInfoPayload
     */
    protected function createCustomerInfo(
        string $billingFirstName,
        string $billingLastName,
        string $billingAddress,
        string $billingPostal,
        string $billingCity,
        string $billingCountry,
        string $shippingFirstName,
        string $shippingLastName,
        string $shippingAddress,
        string $shippingPostal,
        string $shippingCity,
        string $shippingCountry
    ): CustomerInfoPayload {
        $payload = new CustomerInfoPayload();
        $payload
            ->setBillingFirstName($billingFirstName)
            ->setBillingLastName($billingLastName)
            ->setBillingAddress($billingAddress)
            ->setBillingPostal($billingPostal)
            ->setBillingCity($billingCity)
            ->setBillingCountry($billingCountry)
            ->setShippingFirstName($shippingFirstName)
            ->setShippingLastName($shippingLastName)
            ->setShippingAddress($shippingAddress)
            ->setShippingPostal($shippingPostal)
            ->setShippingCity($shippingCity)
            ->setShippingCountry($shippingCountry)
        ;

        return $payload;
    }

    /**
     * @param string $callbackForm
     * @param string $callbackOk
     * @param string $callbackFail
     * @param string $callbackRedirect
     * @param string $callbackOpen
     * @param string $callbackNotification
     *
     * @return ConfigPayload
     */
    protected function createConfig(
        string $callbackForm,
        string $callbackOk,
        string $callbackFail,
        string $callbackRedirect,
        string $callbackOpen,
        string $callbackNotification
    ): ConfigPayload {
        $payload = new ConfigPayload();
        $payload
            ->setCallbackForm($callbackForm)
            ->setCallbackOk($callbackOk)
            ->setCallbackFail($callbackFail)
            ->setCallbackRedirect($callbackRedirect)
            ->setCallbackOpen($callbackOpen)
            ->setCallbackNotification($callbackNotification)
        ;

        return $payload;
    }
}
