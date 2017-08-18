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
        //$order->setLoadBalancerRealIp($paymentRequest->getLoadBalancerRealIp());
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

        // create shipping method object
        // @todo figure out if the payment request from Dandomain contains a payment method id (check also shipping method)
        //$shippingMethod = $this->shippingMethodManager->findByExternalId($paymentRequest->ship)
        $order->setShippingMethod($paymentRequest->getShippingMethod());
        $order->setShippingMethodFee($paymentRequest->getShippingFee());
        $order->setPaymentMethod($paymentRequest->getPaymentMethod());
        $order->setPaymentMethodFee($paymentRequest->getPaymentFee());

        // add delivery to order
        $order->setDelivery($delivery);

        foreach ($paymentRequest->getOrderLines() as $orderLine) {
            $orderLineEntity = $this->orderLineManager->create();
            $orderLineEntity
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
}