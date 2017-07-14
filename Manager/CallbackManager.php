<?php
namespace Loevgaard\DandomainAltapayBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Loevgaard\DandomainAltapayBundle\Entity\CallbackInterface;
use Loevgaard\DandomainAltapayBundle\Entity\OrderLineInterface;
use Loevgaard\DandomainAltapayBundle\Entity\TerminalInterface;
use Symfony\Component\HttpFoundation\Request;

class CallbackManager
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var string
     */
    protected $class;

    public function __construct(ObjectManager $objectManager, string $class)
    {
        $this->objectManager = $objectManager;
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
     * @return CallbackInterface
     */
    public function createCallback() : CallbackInterface
    {
        $class = $this->getClass();
        $obj = new $class();
        return $obj;
    }

    /**
     * @param Request $request
     * @return CallbackInterface
     */
    public function createCallbackFromRequest(Request $request) : CallbackInterface
    {
        $fields = [
            'shop_orderid' => 'orderId',
            'amount' => 'amount',
            'currency' => 'currency',
            'language' => 'language',
            'transaction_info' => 'transactionInfo',
            'status' => 'status',
            'error_message' => 'errorMessage',
            'merchant_error_message' => 'merchantErrorMessage',
            'cardholder_message_must_be_shown' => 'cardholderMessageMustBeShown',
            'transaction_id' => 'transactionId',
            'type' => 'type',
            'payment_status' => 'paymentStatus',
            'masked_credit_card' => 'maskedCreditCard',
            'blacklist_token' => 'blacklistToken',
            'credit_card_token' => 'creditCardToken',
            'nature' => 'nature',
            'require_capture' => 'requireCapture',
            'xml' => 'xml',
            'checksum' => 'checksum',
            'fraud_risk_score' => 'fraudRiskScore',
            'fraud_explanation' => 'fraudExplanation',
            'fraud_recommendation' => 'fraudRecommendation',
            'avs_code' => 'avsCode',
            'avs_text' => 'avsText',
        ];
        $obj = $this->createCallback();
        $obj->setRequest((string)$request);

        foreach ($fields as $key => $field) {
            $val = $request->get($key);
            if($val) {
                $setter = 'set'.ucfirst($field);
                $obj->{$setter}($val);
            }
        }

        return $obj;
    }

    /**
     * @param CallbackInterface $callback
     */
    public function deleteCallback(CallbackInterface $callback)
    {
        $this->objectManager->remove($callback);
        $this->objectManager->flush();
    }

    /**
     * @param CallbackInterface $callback
     * @param bool $flush
     */
    public function updateCallback(CallbackInterface $callback, bool $flush = true)
    {
        $this->objectManager->persist($callback);

        if($flush) {
            $this->objectManager->flush();
        }
    }
}