<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\PayloadGenerator;

use Loevgaard\AltaPay\Payload\OrderLine as OrderLinePayload;
use Loevgaard\AltaPay\Payload\PaymentRequest\Config as ConfigPayload;
use Loevgaard\AltaPay\Payload\PaymentRequest\CustomerInfo as CustomerInfoPayload;
use Loevgaard\Dandomain\Pay\Helper\ChecksumHelper;
use Loevgaard\Dandomain\Pay\Model\Payment as DandomainPayment;
use Loevgaard\DandomainAltapayBundle\Entity\Payment;
use Loevgaard\DandomainAltapayBundle\Entity\Terminal;
use Loevgaard\DandomainAltapayBundle\Tests\PayloadGenerator\Fixture\Gateway;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouterInterface;

final class PaymentRequestPayloadGeneratorTest extends TestCase
{
    public function testCreateOrderLine()
    {
        $generator = $this->getGenerator();

        $description = 'description';
        $itemId = 'itemid';
        $quantity = 1.0;
        $unitPrice = new Money(9995, new Currency('DKK'));

        // without optional parameters
        $payload = $generator->createOrderLine($description, $itemId, $quantity, $unitPrice);
        $this->assertInstanceOf(OrderLinePayload::class, $payload);
        $this->assertSame($description, $payload->getDescription());
        $this->assertSame($itemId, $payload->getItemId());
        $this->assertSame($quantity, $payload->getQuantity());
        $this->assertEquals($unitPrice, $payload->getUnitPrice());

        // with tax percent
        $taxPercent = 25.0;
        $payload = $generator->createOrderLine($description, $itemId, $quantity, $unitPrice, $taxPercent);
        $this->assertInstanceOf(OrderLinePayload::class, $payload);
        $this->assertSame($description, $payload->getDescription());
        $this->assertSame($itemId, $payload->getItemId());
        $this->assertSame($quantity, $payload->getQuantity());
        $this->assertEquals($unitPrice, $payload->getUnitPrice());
        $this->assertSame($taxPercent, $payload->getTaxPercent());

        // with goods type
        $goodsType = OrderLinePayload::GOODS_TYPE_ITEM;
        $payload = $generator->createOrderLine($description, $itemId, $quantity, $unitPrice, null, $goodsType);
        $this->assertInstanceOf(OrderLinePayload::class, $payload);
        $this->assertSame($description, $payload->getDescription());
        $this->assertSame($itemId, $payload->getItemId());
        $this->assertSame($quantity, $payload->getQuantity());
        $this->assertEquals($unitPrice, $payload->getUnitPrice());
        $this->assertSame($goodsType, $payload->getGoodsType());
    }

    public function testCreateCustomerInfo()
    {
        $generator = $this->getGenerator();

        $billingFirstName = 'billingfirstname';
        $billingLastName = 'billinglastname';
        $billingAddress = 'billingaddress';
        $billingPostal = 'billingpostal';
        $billingCity = 'billingcity';
        $billingCountry = 'billingcountry';
        $shippingFirstName = 'shippingfirstname';
        $shippingLastName = 'shippinglastname';
        $shippingAddress = 'shippingaddress';
        $shippingPostal = 'shippingpostal';
        $shippingCity = 'shippingcity';
        $shippingCountry = 'shippingcountry';

        // without optional parameters
        $payload = $generator->createCustomerInfo($billingFirstName, $billingLastName, $billingAddress, $billingPostal, $billingCity, $billingCountry, $shippingFirstName, $shippingLastName, $shippingAddress, $shippingPostal, $shippingCity, $shippingCountry);
        $this->assertInstanceOf(CustomerInfoPayload::class, $payload);
        $this->assertSame($billingFirstName, $payload->getBillingFirstName());
        $this->assertSame($billingLastName, $payload->getBillingLastName());
        $this->assertSame($billingAddress, $payload->getBillingAddress());
        $this->assertSame($billingPostal, $payload->getBillingPostal());
        $this->assertSame($billingCity, $payload->getBillingCity());
        $this->assertSame($billingCountry, $payload->getBillingCountry());
        $this->assertSame($shippingFirstName, $payload->getShippingFirstName());
        $this->assertSame($shippingLastName, $payload->getShippingLastName());
        $this->assertSame($shippingAddress, $payload->getShippingAddress());
        $this->assertSame($shippingPostal, $payload->getShippingPostal());
        $this->assertSame($shippingCity, $payload->getShippingCity());
        $this->assertSame($shippingCountry, $payload->getShippingCountry());
    }

    public function testCreateConfig()
    {
        $generator = $this->getGenerator();

        $callbackForm = 'form';
        $callbackOk = 'ok';
        $callbackFail = 'fail';
        $callbackRedirect = 'redirect';
        $callbackOpen = 'open';
        $callbackNotification = 'notification';

        // without optional parameters
        $payload = $generator->createConfig($callbackForm, $callbackOk, $callbackFail, $callbackRedirect, $callbackOpen, $callbackNotification);
        $this->assertInstanceOf(ConfigPayload::class, $payload);
        $this->assertSame($callbackForm, $payload->getCallbackForm());
        $this->assertSame($callbackOk, $payload->getCallbackOk());
        $this->assertSame($callbackFail, $payload->getCallbackFail());
        $this->assertSame($callbackRedirect, $payload->getCallbackRedirect());
        $this->assertSame($callbackOpen, $payload->getCallbackOpen());
        $this->assertSame($callbackNotification, $payload->getCallbackNotification());
    }

    private function getGenerator()
    {
        $router = $this->getRouter();
        $dandomainPayment = $this->getDandomainPayment();
        $terminal = $this->getTerminal();
        $payment = $this->getPayment();
        $handler = $this->getChecksumHelper($dandomainPayment);

        $generator = new Gateway($router, $dandomainPayment, $terminal, $payment, $handler, 'payment_id', 'checksum_complete');

        return $generator;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|RouterInterface
     */
    private function getRouter()
    {
        $router = $this->createMock(RouterInterface::class);

        return $router;
    }

    private function getDandomainPayment()
    {
        $paymentRequest = new DandomainPayment();

        return $paymentRequest;
    }

    /**
     * @return Terminal
     */
    private function getTerminal()
    {
        return new Terminal();
    }

    /**
     * @return Payment|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getPayment()
    {
        $payment = $this->getMockForAbstractClass(Payment::class);

        return $payment;
    }

    private function getChecksumHelper(DandomainPayment $payment)
    {
        $checksumHelper = new ChecksumHelper($payment, 'sharedkey1', 'sharedkey2');

        return $checksumHelper;
    }
}
