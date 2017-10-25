<?php

namespace Loevgaard\DandomainAltapayBundle\PayloadGenerator;

use Loevgaard\AltaPay\Payload\OrderLine as OrderLinePayload;
use Loevgaard\AltaPay\Payload\PaymentRequest as PaymentRequestPayload;
use Loevgaard\AltaPay\Payload\PaymentRequest\Config as ConfigPayload;
use Loevgaard\AltaPay\Payload\PaymentRequest\CustomerInfo as CustomerInfoPayload;
use Loevgaard\Dandomain\Pay\Handler;
use Loevgaard\Dandomain\Pay\PaymentRequest as DandomainPaymentRequest;
use Loevgaard\DandomainAltapayBundle\Entity\Payment;
use Loevgaard\DandomainAltapayBundle\Entity\TerminalInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentRequestPayloadGenerator implements PayloadGeneratorInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var DandomainPaymentRequest
     */
    protected $paymentRequest;

    /**
     * @var TerminalInterface
     */
    protected $terminal;

    /**
     * @var Payment
     */
    protected $payment;

    /**
     * @var Handler
     */
    protected $handler;

    public function __construct(
        ContainerInterface $container,
        DandomainPaymentRequest $paymentRequest,
        TerminalInterface $terminal,
        Payment $payment,
        Handler $handler
    ) {
        $this->container = $container;
        $this->router = $this->container->get('router');
        $this->paymentRequest = $paymentRequest;
        $this->terminal = $terminal;
        $this->payment = $payment;
        $this->handler = $handler;
    }

    /**
     * @return PaymentRequestPayload
     */
    public function generate(): PaymentRequestPayload
    {
        $paymentRequestPayload = new PaymentRequestPayload(
            $this->terminal->getTitle(),
            $this->paymentRequest->getOrderId(),
            $this->paymentRequest->getTotalAmount(),
            $this->paymentRequest->getCurrencySymbol()
        );

        foreach ($this->paymentRequest->getPaymentLines() as $paymentLine) {
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
        if ($this->paymentRequest->getPaymentFee()) {
            $orderLinePayload = $this->createOrderLine(
                $this->paymentRequest->getPaymentMethod(),
                $this->paymentRequest->getPaymentMethod(),
                1,
                $this->paymentRequest->getPaymentFee(),
                null,
                OrderLinePayload::GOODS_TYPE_HANDLING
            );
            $paymentRequestPayload->addOrderLine($orderLinePayload);
        }

        // add shipping fee as an order line if it's set
        if ($this->paymentRequest->getShippingFee()) {
            $orderLinePayload = $this->createOrderLine(
                $this->paymentRequest->getShippingMethod(),
                $this->paymentRequest->getShippingMethod(),
                1,
                $this->paymentRequest->getShippingFee(),
                null,
                OrderLinePayload::GOODS_TYPE_SHIPMENT
            );
            $paymentRequestPayload->addOrderLine($orderLinePayload);
        }

        $customerNames = explode(' ', $this->paymentRequest->getCustomerName(), 2);
        $shippingNames = explode(' ', $this->paymentRequest->getDeliveryName(), 2);

        $customerInfoPayload = $this->createCustomerInfo(
            $customerNames[0] ?? '',
            $customerNames[1] ?? '',
            $this->paymentRequest->getCustomerAddress().($this->paymentRequest->getCustomerAddress2() ? "\r\n".$this->paymentRequest->getCustomerAddress2() : ''),
            $this->paymentRequest->getCustomerZipCode(),
            $this->paymentRequest->getCustomerCity(),
            $this->paymentRequest->getCustomerCountry(),
            $shippingNames[0] ?? '',
            $shippingNames[1] ?? '',
            $this->paymentRequest->getDeliveryAddress().($this->paymentRequest->getDeliveryAddress2() ? "\r\n".$this->paymentRequest->getDeliveryAddress2() : ''),
            $this->paymentRequest->getDeliveryZipCode(),
            $this->paymentRequest->getDeliveryCity(),
            $this->paymentRequest->getDeliveryCountry()
        );
        $paymentRequestPayload->setCustomerInfo($customerInfoPayload);

        $configPayload = $this->createConfig(
            $this->router->generate(
                'loevgaard_dandomain_altapay_callback_form',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
            $this->router->generate(
                'loevgaard_dandomain_altapay_callback_ok',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
            $this->router->generate(
                'loevgaard_dandomain_altapay_callback_fail',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
            $this->router->generate(
                'loevgaard_dandomain_altapay_callback_redirect',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
            $this->router->generate(
                'loevgaard_dandomain_altapay_callback_open',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
            $this->router->generate(
                'loevgaard_dandomain_altapay_callback_notification',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            )
        );
        $paymentRequestPayload->setConfig($configPayload);

        $paymentRequestPayload
            ->setCookiePart(
                $this->container->getParameter('loevgaard_dandomain_altapay.cookie_payment_id'),
                $this->payment->getId()
            )
            ->setCookiePart(
                $this->container->getParameter('loevgaard_dandomain_altapay.cookie_checksum_complete'),
                $this->handler->getChecksum2()
            )
        ;

        return $paymentRequestPayload;
    }

    /**
     * @param string      $description
     * @param string      $itemId
     * @param string      $quantity
     * @param float       $unitPrice
     * @param float|null  $taxPercent
     * @param string|null $goodsType
     *
     * @return OrderLinePayload
     */
    protected function createOrderLine(
        string $description,
        string $itemId,
        string $quantity,
        float $unitPrice,
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
