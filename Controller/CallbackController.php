<?php

namespace Loevgaard\DandomainAltapayBundle\Controller;

use Loevgaard\DandomainAltapayBundle\Annotation\LogHttpTransaction;
use Loevgaard\DandomainAltapayBundle\Entity\Payment;
use Loevgaard\DandomainAltapayBundle\Exception\CallbackException;
use Loevgaard\DandomainAltapayBundle\Exception\NotAllowedIpException;
use Loevgaard\DandomainAltapayBundle\Exception\PaymentException;
use Loevgaard\DandomainAltapayBundle\Manager\PaymentManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/callback")
 */
class CallbackController extends Controller
{
    /**
     * @Method("POST")
     * @Route("/form", name="loevgaard_dandomain_altapay_callback_form")
     *
     * @LogHttpTransaction()
     *
     * @param Request $request
     *
     * @return Response
     */
    public function formAction(Request $request)
    {
        $payment = $this->handleCallback($request);

        return $this->render('@LoevgaardDandomainAltapay/callback/form.html.twig', [
            'payment' => $payment,
        ]);
    }

    /**
     * @Method("POST")
     * @Route("/ok", name="loevgaard_dandomain_altapay_callback_ok")
     *
     * @LogHttpTransaction()
     *
     * @param Request $request
     *
     * @return Response
     */
    public function okAction(Request $request)
    {
        $payment = $this->handleCallback($request);

        return $this->render('@LoevgaardDandomainAltapay/callback/ok.html.twig');
    }

    /**
     * @Method("POST")
     * @Route("/fail", name="loevgaard_dandomain_altapay_callback_fail")
     *
     * @LogHttpTransaction()
     *
     * @param Request $request
     *
     * @return Response
     */
    public function failAction(Request $request)
    {
        $payment = $this->handleCallback($request);

        return $this->render('@LoevgaardDandomainAltapay/callback/fail.html.twig');
    }

    /**
     * @Method("POST")
     * @Route("/redirect", name="loevgaard_dandomain_altapay_callback_redirect")
     *
     * @LogHttpTransaction()
     *
     * @param Request $request
     *
     * @return Response
     */
    public function redirectAction(Request $request)
    {
        $payment = $this->handleCallback($request);

        return $this->render('@LoevgaardDandomainAltapay/callback/redirect.html.twig');
    }

    /**
     * @Method("POST")
     * @Route("/open", name="loevgaard_dandomain_altapay_callback_open")
     *
     * @LogHttpTransaction()
     *
     * @param Request $request
     *
     * @return Response
     */
    public function openAction(Request $request)
    {
        return $this->render('@LoevgaardDandomainAltapay/callback/open.html.twig');
    }

    /**
     * @Method("POST")
     * @Route("/notification", name="loevgaard_dandomain_altapay_callback_notification")
     *
     * @LogHttpTransaction()
     *
     * @param Request $request
     *
     * @return Response
     */
    public function notificationAction(Request $request)
    {
        $payment = $this->handleCallback($request);

        return new Response('OK');
    }

    /**
     * @Method("POST")
     * @Route("/verify-order", name="loevgaard_dandomain_altapay_callback_verify_order")
     *
     * @LogHttpTransaction()
     *
     * @param Request $request
     *
     * @return Response
     */
    public function verifyOrderAction(Request $request)
    {
        $payment = $this->handleCallback($request);

        return new Response('OK');
    }

    /**
     * @param Request $request
     *
     * @return Payment
     *
     * @throws PaymentException
     */
    protected function handleCallback(Request $request)
    {
        $payment = $this->getPaymentFromRequest($request);

        // @todo this should be placed somewhere in the altapay php sdk
        $transaction = null;

        if($request->request->has('xml')) {
            $xml = new \SimpleXMLElement($request->request->get('xml'));
            if(isset($xml->Body->Transactions->Transaction) && !empty($xml->Body->Transactions->Transaction)) {
                foreach ($xml->Body->Transactions->Transaction as $transactionXml) {
                    $transaction = $transactionXml;
                    break;
                }
            }
        }

        if($transaction) {
            $paymentManager = $this->getPaymentManager();

            $createdDate = \DateTime::createFromFormat('Y-m-d H:i:s', $transaction->CreatedDate);
            if($createdDate === false) {
                $createdDate = null;
            }

            $updatedDate = \DateTime::createFromFormat('Y-m-d H:i:s', $transaction->UpdatedDate);
            if($updatedDate === false) {
                $updatedDate = null;
            }

            $supportsRefunds = $supportsRelease = $supportsMultipleCaptures = $supportsMultipleRefunds = null;
            if(isset($transaction->PaymentNatureService)) {
                if(isset($transaction->PaymentNatureService->SupportsRefunds)) {
                    $supportsRefunds = (string)$transaction->PaymentNatureService->SupportsRefunds === 'true';
                }

                if(isset($transaction->PaymentNatureService->SupportsRelease)) {
                    $supportsRelease = (string)$transaction->PaymentNatureService->SupportsRelease === 'true';
                }

                if(isset($transaction->PaymentNatureService->SupportsMultipleCaptures)) {
                    $supportsMultipleCaptures = (string)$transaction->PaymentNatureService->SupportsMultipleCaptures === 'true';
                }

                if(isset($transaction->PaymentNatureService->SupportsMultipleRefunds)) {
                    $supportsMultipleRefunds = (string)$transaction->PaymentNatureService->SupportsMultipleRefunds === 'true';
                }
            }

            $payment
                ->setAltapayId($transaction->PaymentId ?? null)
                ->setCardStatus($transaction->CardStatus ?? null)
                ->setCreditCardToken($transaction->CreditCardToken ?? null)
                ->setCreditCardMaskedPan($transaction->CreditCardMaskedPan ?? null)
                ->setThreeDSecureResult($transaction->ThreeDSecureResult ?? null)
                ->setLiableForChargeback($transaction->LiableForChargeback ?? null)
                ->setBlacklistToken($transaction->BlacklistToken ?? null)
                ->setShop($transaction->Shop ?? null)
                ->setTerminal($transaction->Terminal ?? null)
                ->setTransactionStatus($transaction->TransactionStatus ?? null)
                ->setReasonCode($transaction->ReasonCode ?? null)
                ->setMerchantCurrency(isset($transaction->MerchantCurrency) ? (int)$transaction->MerchantCurrency : null)
                ->setMerchantCurrencyAlpha($transaction->MerchantCurrencyAlpha ?? null)
                ->setCardHolderCurrency(isset($transaction->CardHolderCurrency) ? (int)$transaction->CardHolderCurrency : null)
                ->setCardHolderCurrencyAlpha($transaction->CardHolderCurrencyAlpha ?? null)
                ->setReservedAmount(isset($transaction->ReservedAmount) ? (float)$transaction->ReservedAmount : null)
                ->setCapturedAmount(isset($transaction->CapturedAmount) ? (float)$transaction->CapturedAmount : null)
                ->setRefundedAmount(isset($transaction->RefundedAmount) ? (float)$transaction->RefundedAmount : null)
                ->setRecurringDefaultAmount(isset($transaction->RecurringDefaultAmount) ? (float)$transaction->RecurringDefaultAmount : null)
                ->setCreatedDate($createdDate)
                ->setUpdatedDate($updatedDate)
                ->setPaymentNature($transaction->PaymentNature ?? null)
                ->setSupportsRefunds($supportsRefunds)
                ->setSupportsRelease($supportsRelease)
                ->setSupportsMultipleCaptures($supportsMultipleCaptures)
                ->setSupportsMultipleRefunds($supportsMultipleRefunds)
                ->setFraudRiskScore(isset($transaction->FraudRiskScore) ? (float)$transaction->FraudRiskScore : null)
                ->setFraudExplanation($transaction->FraudExplanation ?? null)
            ;
            $paymentManager->update($payment);
        }

        $callbackManager = $this->container->get('loevgaard_dandomain_altapay.callback_manager');
        $callback = $callbackManager->createCallbackFromRequest($request);
        $callback->setPayment($payment);

        $callbackManager->update($callback);

        $allowedIps = $this->container->getParameter('loevgaard_dandomain_altapay.altapay_ips');
        if ('prod' === $this->container->get('kernel')->getEnvironment() && !in_array($request->getClientIp(), $allowedIps)) {
            throw NotAllowedIpException::create('IP `'.$request->getClientIp().'` is not an allowed IP.', $request, $payment);
        }

        return $payment;
    }

    /**
     * @param Request $request
     *
     * @return Payment
     *
     * @throws CallbackException
     */
    protected function getPaymentFromRequest(Request $request)
    {
        $paymentId = $request->cookies->getInt($this->getParameter('loevgaard_dandomain_altapay.cookie_payment_id'));
        $paymentManager = $this->getPaymentManager();

        /** @var Payment $payment */
        $payment = $paymentManager->getRepository()->find($paymentId);

        if (!$payment) {
            throw new CallbackException('Payment '.$paymentId.' does not exist');
        }

        return $payment;
    }

    /**
     * Add a callback request to the payment for logging purposes.
     *
     * @param Payment $payment
     * @param Request $request
     */
    protected function logCallback($payment, Request $request)
    {
        $callbackManager = $this->container->get('loevgaard_dandomain_altapay.callback_manager');
        $callback = $callbackManager->create();
        $callback->setPayment($payment)
            ->setRequest((string) $request);

        $callbackManager->update($callback);
    }

    /**
     * @return PaymentManager
     */
    protected function getPaymentManager()
    {
        return $this->container->get('loevgaard_dandomain_altapay.payment_manager');
    }
}
