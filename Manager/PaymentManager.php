<?php
namespace Loevgaard\DandomainAltapayBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Loevgaard\Dandomain\Pay\PaymentRequest;
use Loevgaard\DandomainAltapayBundle\Entity\OrderLineInterface;
use Loevgaard\DandomainAltapayBundle\Entity\PaymentInterface;
use Loevgaard\DandomainAltapayBundle\Entity\TerminalInterface;

class PaymentManager
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var OrderLineManager
     */
    protected $orderLineManager;

    /**
     * @var string
     */
    protected $class;

    public function __construct(ObjectManager $objectManager, OrderLineManager $orderLineManager, string $class)
    {
        $this->objectManager = $objectManager;
        $this->orderLineManager = $orderLineManager;
        $this->class = $class;
    }

    /**
     * @return ObjectRepository
     */
    protected function getRepository() : ObjectRepository
    {
        return $this->objectManager->getRepository($this->getClass());
    }

    /**
     * @return string
     */
    public function getClass() : string
    {
        if (false !== strpos($this->class, ':')) {
            $metadata = $this->objectManager->getClassMetadata($this->class);
            $this->class = $metadata->getName();
        }
        return $this->class;
    }

    /**
     * @return PaymentInterface
     */
    public function createPayment() : PaymentInterface
    {
        $class = $this->getClass();
        $payment = new $class();
        return $payment;
    }

    /**
     * @param PaymentRequest $paymentRequest
     * @return PaymentInterface
     */
    public function createPaymentFromDandomainPaymentRequest(PaymentRequest $paymentRequest) : PaymentInterface
    {
        $payment = $this->createPayment();
        $payment->setApiKey($paymentRequest->getApiKey());
        $payment->setMerchant($paymentRequest->getMerchant());
        $payment->setOrderId($paymentRequest->getOrderId());
        $payment->setSessionId($paymentRequest->getSessionId());
        $payment->setCurrencySymbol($paymentRequest->getCurrencySymbol());
        $payment->setTotalAmount($paymentRequest->getTotalAmount());
        $payment->setCallBackUrl($paymentRequest->getCallBackUrl());
        $payment->setFullCallBackOkUrl($paymentRequest->getFullCallBackOkUrl());
        $payment->setCallBackOkUrl($paymentRequest->getCallBackOkUrl());
        $payment->setCallBackServerUrl($paymentRequest->getCallBackServerUrl());
        $payment->setLanguageId((int)$paymentRequest->getLanguageId());
        $payment->setTestMode($paymentRequest->isTestMode());
        $payment->setPaymentGatewayCurrencyCode($paymentRequest->getPaymentGatewayCurrencyCode());
        $payment->setCardTypeId($paymentRequest->getCardTypeId());
        $payment->setCustomerRekvNr($paymentRequest->getCustomerRekvNr());
        $payment->setCustomerName($paymentRequest->getCustomerName());
        $payment->setCustomerCompany($paymentRequest->getCustomerCompany());
        $payment->setCustomerAddress($paymentRequest->getCustomerAddress());
        $payment->setCustomerAddress2($paymentRequest->getCustomerAddress2());
        $payment->setCustomerZipCode($paymentRequest->getCustomerZipCode());
        $payment->setCustomerCity($paymentRequest->getCustomerCity());
        $payment->setCustomerCountryId((int)$paymentRequest->getCustomerCountryId());
        $payment->setCustomerCountry($paymentRequest->getCustomerCountry());
        $payment->setCustomerPhone($paymentRequest->getCustomerPhone());
        $payment->setCustomerFax($paymentRequest->getCustomerFax());
        $payment->setCustomerEmail($paymentRequest->getCustomerEmail());
        $payment->setCustomerNote($paymentRequest->getCustomerNote());
        $payment->setCustomerCvrnr($paymentRequest->getCustomerCvrnr());
        $payment->setCustomerCustTypeId($paymentRequest->getCustomerCustTypeId());
        $payment->setCustomerEan($paymentRequest->getCustomerEan());
        $payment->setCustomerRes1($paymentRequest->getCustomerRes1());
        $payment->setCustomerRes2($paymentRequest->getCustomerRes2());
        $payment->setCustomerRes3($paymentRequest->getCustomerRes3());
        $payment->setCustomerRes4($paymentRequest->getCustomerRes4());
        $payment->setCustomerRes5($paymentRequest->getCustomerRes5());
        $payment->setDeliveryName($paymentRequest->getDeliveryName());
        $payment->setDeliveryCompany($paymentRequest->getDeliveryCompany());
        $payment->setDeliveryAddress($paymentRequest->getDeliveryAddress());
        $payment->setDeliveryAddress2($paymentRequest->getDeliveryAddress2());
        $payment->setDeliveryZipCode($paymentRequest->getDeliveryZipCode());
        $payment->setDeliveryCity($paymentRequest->getDeliveryCity());
        $payment->setDeliveryCountryID((int)$paymentRequest->getDeliveryCountryID());
        $payment->setDeliveryCountry($paymentRequest->getDeliveryCountry());
        $payment->setDeliveryPhone($paymentRequest->getDeliveryPhone());
        $payment->setDeliveryFax($paymentRequest->getDeliveryFax());
        $payment->setDeliveryEmail($paymentRequest->getDeliveryEmail());
        $payment->setDeliveryEan($paymentRequest->getDeliveryEan());
        $payment->setShippingMethod($paymentRequest->getShippingMethod());
        $payment->setShippingFee($paymentRequest->getShippingFee());
        $payment->setPaymentMethod($paymentRequest->getPaymentMethod());
        $payment->setPaymentFee($paymentRequest->getPaymentFee());
        $payment->setCustomerIp($paymentRequest->getCustomerIp());
        $payment->setLoadBalancerRealIp($paymentRequest->getLoadBalancerRealIp());

        foreach ($paymentRequest->getOrderLines() as $orderLine) {
            $orderLineEntity = $this->orderLineManager->createOrderLine();
            $orderLineEntity
                ->setProductNumber($orderLine->getProductNumber())
                ->setName($orderLine->getName())
                ->setQuantity($orderLine->getQuantity())
                ->setPrice($orderLine->getPrice())
                ->setVat($orderLine->getVat())
            ;
            $payment->addOrderLine($orderLineEntity);
        }

        return $payment;
    }

    /**
     * @param PaymentInterface $payment
     */
    public function deletePayment(PaymentInterface $payment)
    {
        $this->objectManager->remove($payment);
        $this->objectManager->flush();
    }

    /**
     * @param PaymentInterface $payment
     * @param bool $flush
     */
    public function updatePayment(PaymentInterface $payment, bool $flush = true)
    {
        $this->objectManager->persist($payment);

        if($flush) {
            $this->objectManager->flush();
        }
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
}