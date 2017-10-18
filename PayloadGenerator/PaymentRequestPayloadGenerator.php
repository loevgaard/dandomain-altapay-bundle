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
            $orderLinePayload = new OrderLinePayload(
                $paymentLine->getName(),
                $paymentLine->getProductNumber(),
                $paymentLine->getQuantity(),
                $paymentLine->getPrice()
            );
            $orderLinePayload->setTaxPercent($paymentLine->getVat());

            $paymentRequestPayload->addOrderLine($orderLinePayload);
        }

        // add payment fee as an order line if it's set
        if ($this->paymentRequest->getPaymentFee()) {
            $orderLinePayload = new OrderLinePayload(
                $this->paymentRequest->getPaymentMethod(),
                $this->paymentRequest->getPaymentMethod(),
                1,
                $this->paymentRequest->getPaymentFee()
            );
            $orderLinePayload->setGoodsType(OrderLinePayload::GOODS_TYPE_HANDLING);
            $paymentRequestPayload->addOrderLine($orderLinePayload);
        }

        // add shipping fee as an order line if it's set
        if ($this->paymentRequest->getShippingFee()) {
            $orderLinePayload = new OrderLinePayload(
                $this->paymentRequest->getShippingMethod(),
                $this->paymentRequest->getShippingMethod(),
                1,
                $this->paymentRequest->getShippingFee()
            );
            $orderLinePayload->setGoodsType(OrderLinePayload::GOODS_TYPE_SHIPMENT);
            $paymentRequestPayload->addOrderLine($orderLinePayload);
        }

        $customerInfoPayload = new CustomerInfoPayload();
        $customerNames = explode(' ', $this->paymentRequest->getCustomerName(), 2);
        $shippingNames = explode(' ', $this->paymentRequest->getDeliveryName(), 2);
        $customerInfoPayload
            ->setBillingFirstName($customerNames[0] ?? '')
            ->setBillingLastName($customerNames[1] ?? '')
            ->setBillingAddress(
                $this->paymentRequest->getCustomerAddress().
                ($this->paymentRequest->getCustomerAddress2() ? "\r\n".$this->paymentRequest->getCustomerAddress2() : '')
            )
            ->setBillingPostal($this->paymentRequest->getCustomerZipCode())
            ->setBillingCity($this->paymentRequest->getCustomerCity())
            ->setBillingCountry($this->paymentRequest->getCustomerCountry())
            ->setShippingFirstName($shippingNames[0] ?? '')
            ->setShippingLastName($shippingNames[1] ?? '')
            ->setShippingAddress(
                $this->paymentRequest->getDeliveryAddress().
                ($this->paymentRequest->getDeliveryAddress2() ? "\r\n".$this->paymentRequest->getDeliveryAddress2() : '')
            )
            ->setShippingPostal($this->paymentRequest->getDeliveryZipCode())
            ->setShippingCity($this->paymentRequest->getDeliveryCity())
            ->setShippingCountry($this->paymentRequest->getDeliveryCountry())
        ;
        $paymentRequestPayload->setCustomerInfo($customerInfoPayload);

        $configPayload = new ConfigPayload();
        $configPayload
            ->setCallbackForm(
                $this->router->generate(
                    'loevgaard_dandomain_altapay_callback_form',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            )
            ->setCallbackOk(
                $this->router->generate(
                    'loevgaard_dandomain_altapay_callback_ok',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            )
            ->setCallbackFail(
                $this->router->generate(
                    'loevgaard_dandomain_altapay_callback_fail',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            )
            ->setCallbackRedirect(
                $this->router->generate(
                    'loevgaard_dandomain_altapay_callback_redirect',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            )
            ->setCallbackOpen(
                $this->router->generate(
                    'loevgaard_dandomain_altapay_callback_open',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            )
            ->setCallbackNotification(
                $this->router->generate(
                    'loevgaard_dandomain_altapay_callback_notification',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            )
        ;
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
}
