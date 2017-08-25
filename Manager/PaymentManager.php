<?php
namespace Loevgaard\DandomainAltapayBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Loevgaard\Dandomain\Pay\PaymentRequest;
use Loevgaard\DandomainAltapayBundle\Entity\PaymentInterface;
use Loevgaard\DandomainFoundationBundle\Manager\Manager;
use Loevgaard\DandomainFoundationBundle\Manager\OrderManager;
use Loevgaard\DandomainFoundationBundle\Manager\OrderLineManager;
use Loevgaard\DandomainFoundationBundle\Manager\PaymentMethodManager;
use Loevgaard\DandomainFoundationBundle\Manager\ShippingMethodManager;
use Loevgaard\DandomainFoundationBundle\Manager\SiteManager;
use Loevgaard\DandomainFoundationBundle\Manager\CustomerManager;
use Loevgaard\DandomainFoundationBundle\Manager\DeliveryManager;
use Loevgaard\DandomainFoundationBundle\Manager\ProductManager;

/**
 * @method PaymentInterface create()
 * @method delete(PaymentInterface $obj)
 * @method update(PaymentInterface $obj, $flush = true)
 */
class PaymentManager extends Manager
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var OrderManager
     */
    protected $orderManager;

    /**
     * @var OrderLineManager
     */
    protected $orderLineManager;

    /**
     * @var SiteManager
     */
    protected $siteManager;

    /**
     * @var CustomerManager
     */
    protected $customerManager;

    /**
     * @var DeliveryManager
     */
    protected $deliveryManager;

    /**
     * @var PaymentMethodManager
     */
    protected $paymentMethodManager;

    /**
     * @var ShippingMethodManager
     */
    protected $shippingMethodManager;

    /**
     * @var ProductManager
     */
    protected $productManager;

    /**
     * @var string
     */
    protected $class;

    /**
     * @param PaymentRequest $paymentRequest
     * @return PaymentInterface
     */
    public function createPaymentFromDandomainPaymentRequest(PaymentRequest $paymentRequest) : PaymentInterface
    {
        $order = $this->orderManager->create();


        $site = $this->siteManager->findByExternalId($paymentRequest->getLanguageId());
        $order->setExternalId($paymentRequest->getOrderId());
        $order->setCurrencyCode($paymentRequest->getCurrencySymbol());
        $order->setIp($paymentRequest->getCustomerIp());
        $order->setSite($site);

        // create customer object
        $customer = $this->customerManager->create();
        //$customer->setCustomerRekvNr($paymentRequest->getCustomerRekvNr()); @todo ask Dandomain why this field is not present on the customer object
        $customer->setAttention($paymentRequest->getCustomerName());
        $customer->setName($paymentRequest->getCustomerCompany());
        $customer->setAddress($paymentRequest->getCustomerAddress());
        $customer->setAddress2($paymentRequest->getCustomerAddress2());
        $customer->setZipCode($paymentRequest->getCustomerZipCode());
        $customer->setCity($paymentRequest->getCustomerCity());
        $customer->setCountryId((int)$paymentRequest->getCustomerCountryId());
        $customer->setCountry($paymentRequest->getCustomerCountry());
        $customer->setPhone($paymentRequest->getCustomerPhone());
        $customer->setFax($paymentRequest->getCustomerFax());
        $customer->setEmail($paymentRequest->getCustomerEmail());
        $customer->setComments($paymentRequest->getCustomerNote());
        $customer->setCvr($paymentRequest->getCustomerCvrnr());
        //$customer->setCustomerCustTypeId($paymentRequest->getCustomerCustTypeId()); @todo figure out what this field is
        $customer->setEan($paymentRequest->getCustomerEan());
        $customer->setReservedField1($paymentRequest->getCustomerRes1());
        $customer->setReservedField2($paymentRequest->getCustomerRes2());
        $customer->setReservedField3($paymentRequest->getCustomerRes3());
        $customer->setReservedField4($paymentRequest->getCustomerRes4());
        $customer->setReservedField5($paymentRequest->getCustomerRes5());

        // add customer to order
        $order->setCustomer($customer);

        // create delivery object
        $delivery = $this->deliveryManager->create();
        $delivery->setAttention($paymentRequest->getDeliveryName());
        $delivery->setName($paymentRequest->getDeliveryCompany());
        $delivery->setAddress($paymentRequest->getDeliveryAddress());
        $delivery->setAddress2($paymentRequest->getDeliveryAddress2());
        $delivery->setZipCode($paymentRequest->getDeliveryZipCode());
        $delivery->setCity($paymentRequest->getDeliveryCity());
        $delivery->setCountryId((int)$paymentRequest->getDeliveryCountryID());
        $delivery->setCountry($paymentRequest->getDeliveryCountry());
        $delivery->setPhone($paymentRequest->getDeliveryPhone());
        $delivery->setFax($paymentRequest->getDeliveryFax());
        $delivery->setEmail($paymentRequest->getDeliveryEmail());
        $delivery->setEan($paymentRequest->getDeliveryEan());

        // add delivery to order
        $order->setDelivery($delivery);

        /**
         * @todo the payment request from Dandomain Payment API does not contain a shipping method id
         * we should be able to find it using language and name, but the shipping method manager from
         * dandomain-foundation-bundle does not contain such a method, so we will just set the fee.
         * The same is true for the payment method below shipping method
         */
        //$order->setShippingMethod($paymentRequest->getShippingMethod());
        $order->setShippingMethodFee($paymentRequest->getShippingFee());
        //$order->setPaymentMethod($paymentRequest->getPaymentMethod());
        $order->setPaymentMethodFee($paymentRequest->getPaymentFee());

        foreach ($paymentRequest->getOrderLines() as $orderLine) {
            $product = $this->productManager->findByProductNumber($orderLine->getProductNumber());
            $orderLineEntity = $this->orderLineManager->create();
            $orderLineEntity
                ->setProduct($product)
                ->setProductNumber($orderLine->getProductNumber())
                ->setProductName($orderLine->getName())
                ->setQuantity($orderLine->getQuantity())
                ->setUnitPrice($orderLine->getPrice())
                ->setVatPct($orderLine->getVat())
            ;
            $order->addOrderLine($orderLineEntity);
        }

        // create payment object
        $payment = $this->create();
        $payment->setApiKey($paymentRequest->getApiKey());
        $payment->setMerchant($paymentRequest->getMerchant());
        $payment->setSessionId($paymentRequest->getSessionId());
        $payment->setCurrencySymbol($paymentRequest->getCurrencySymbol());
        $payment->setTotalAmount($paymentRequest->getTotalAmount());
        $payment->setCallBackUrl($paymentRequest->getCallBackUrl());
        $payment->setFullCallBackOkUrl($paymentRequest->getFullCallBackOkUrl());
        $payment->setCallBackOkUrl($paymentRequest->getCallBackOkUrl());
        $payment->setCallBackServerUrl($paymentRequest->getCallBackServerUrl());
        $payment->setTestMode($paymentRequest->isTestMode());
        $payment->setPaymentGatewayCurrencyCode($paymentRequest->getPaymentGatewayCurrencyCode());
        $payment->setCardTypeId($paymentRequest->getCardTypeId());
        $payment->setLoadBalancerRealIp($paymentRequest->getLoadBalancerRealIp());
        $payment->setOrder($order);

        return $payment;
    }

    /**
     * @param int $id
     * @return PaymentInterface|null
     */
    public function findPaymentById(int $id) : ?PaymentInterface
    {
        /** @var PaymentInterface $payment */
        $payment = $this->getRepository()->find($id);

        return $payment;
    }

    /**
     * Setters
     */

    /**
     * @param OrderManager $orderManager
     * @return PaymentManager
     */
    public function setOrderManager(OrderManager $orderManager)
    {
        $this->orderManager = $orderManager;
        return $this;
    }

    /**
     * @param OrderLineManager $orderLineManager
     * @return PaymentManager
     */
    public function setOrderLineManager(OrderLineManager $orderLineManager)
    {
        $this->orderLineManager = $orderLineManager;
        return $this;
    }

    /**
     * @param SiteManager $siteManager
     * @return PaymentManager
     */
    public function setSiteManager(SiteManager $siteManager)
    {
        $this->siteManager = $siteManager;
        return $this;
    }

    /**
     * @param CustomerManager $customerManager
     * @return PaymentManager
     */
    public function setCustomerManager(CustomerManager $customerManager)
    {
        $this->customerManager = $customerManager;
        return $this;
    }

    /**
     * @param DeliveryManager $deliveryManager
     * @return PaymentManager
     */
    public function setDeliveryManager(DeliveryManager $deliveryManager)
    {
        $this->deliveryManager = $deliveryManager;
        return $this;
    }

    /**
     * @param PaymentMethodManager $paymentMethodManager
     * @return PaymentManager
     */
    public function setPaymentMethodManager(PaymentMethodManager $paymentMethodManager)
    {
        $this->paymentMethodManager = $paymentMethodManager;
        return $this;
    }

    /**
     * @param ShippingMethodManager $shippingMethodManager
     * @return PaymentManager
     */
    public function setShippingMethodManager(ShippingMethodManager $shippingMethodManager)
    {
        $this->shippingMethodManager = $shippingMethodManager;
        return $this;
    }

    /**
     * @param ProductManager $productManager
     * @return PaymentManager
     */
    public function setProductManager(ProductManager $productManager)
    {
        $this->productManager = $productManager;
        return $this;
    }
}