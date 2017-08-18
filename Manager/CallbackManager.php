<?php
namespace Loevgaard\DandomainAltapayBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Loevgaard\DandomainAltapayBundle\Entity\CallbackInterface;
use Loevgaard\DandomainAltapayBundle\Entity\OrderLineInterface;
use Loevgaard\DandomainAltapayBundle\Entity\TerminalInterface;
use Loevgaard\DandomainFoundationBundle\Manager\Manager;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CallbackInterface create()
 * @method delete(CallbackInterface $obj)
 * @method update(CallbackInterface $obj, $flush = true)
 */
class CallbackManager extends Manager
{
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
        $obj = $this->create();
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
}