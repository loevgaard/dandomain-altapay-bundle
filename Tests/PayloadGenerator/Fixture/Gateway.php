<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\PayloadGenerator\Fixture;

use Loevgaard\AltaPay\Payload\OrderLine as OrderLinePayload;
use Loevgaard\AltaPay\Payload\PaymentRequest\Config as ConfigPayload;
use Loevgaard\AltaPay\Payload\PaymentRequest\CustomerInfo as CustomerInfoPayload;
use Loevgaard\DandomainAltapayBundle\PayloadGenerator\PaymentRequestPayloadGenerator;

final class Gateway extends PaymentRequestPayloadGenerator
{
    public function createOrderLine(
        string $description,
        string $itemId,
        string $quantity,
        float $unitPrice,
        float $taxPercent = null,
        string $goodsType = null
    ): OrderLinePayload {
        return parent::createOrderLine($description, $itemId, $quantity, $unitPrice, $taxPercent,
            $goodsType);
    }

    public function createCustomerInfo(
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
        return parent::createCustomerInfo($billingFirstName, $billingLastName, $billingAddress, $billingPostal,
            $billingCity, $billingCountry, $shippingFirstName, $shippingLastName, $shippingAddress, $shippingPostal,
            $shippingCity, $shippingCountry);
    }

    public function createConfig(
        string $callbackForm,
        string $callbackOk,
        string $callbackFail,
        string $callbackRedirect,
        string $callbackOpen,
        string $callbackNotification
    ): ConfigPayload {
        return parent::createConfig($callbackForm, $callbackOk, $callbackFail, $callbackRedirect, $callbackOpen,
            $callbackNotification);
    }
}
