<?php

namespace Loevgaard\DandomainAltapayBundle\Controller;

use Loevgaard\AltaPay\Callback\Xml as XmlCallback;
use Loevgaard\AltaPay\Entity\Transaction;
use Loevgaard\DandomainAltapayBundle\Annotation\LogHttpTransaction;
use Loevgaard\DandomainAltapayBundle\Entity\Payment;
use Loevgaard\DandomainAltapayBundle\Exception\CallbackException;
use Loevgaard\DandomainAltapayBundle\Exception\NotAllowedIpException;
use Loevgaard\DandomainAltapayBundle\Exception\PaymentException;
use Loevgaard\DandomainAltapayBundle\Manager\PaymentManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @return RedirectResponse
     */
    public function okAction(Request $request)
    {
        $payment = $this->handleCallback($request);

        $url = $payment->getFullCallBackOkUrl()
            .'&PayApiCompleteOrderChecksum='.$request->cookies->getAlnum(
                $this->getParameter('loevgaard_dandomain_altapay.cookie_checksum_complete')
            );

        return $this->redirect($url);
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
        $callbackHandler = $this->get('loevgaard_dandomain_altapay.altapay_callback_handler');

        // convert symfony request to PSR7 request
        $psr7Factory = new DiactorosFactory();
        $psrRequest = $psr7Factory->createRequest($request);
        $callback = $callbackHandler->handleCallback($psrRequest);

        if ($callback instanceof XmlCallback) {
            $transactions = $callback->getTransactions();
            if (isset($transactions[0])) {
                /** @var Transaction $transaction */
                $transaction = $transactions[0];

                $paymentManager = $this->getPaymentManager();

                $payment
                    ->setAltapayId($transaction->getPaymentId())
                    ->setCardStatus($transaction->getCardStatus())
                    ->setCreditCardToken($transaction->getCreditCardToken())
                    ->setCreditCardMaskedPan($transaction->getCreditCardMaskedPan())
                    ->setThreeDSecureResult($transaction->getThreeDSecureResult())
                    ->setLiableForChargeback($transaction->getLiableForChargeback())
                    ->setBlacklistToken($transaction->getBlacklistToken())
                    ->setShop($transaction->getShop())
                    ->setTerminal($transaction->getTerminal())
                    ->setTransactionStatus($transaction->getTransactionStatus())
                    ->setReasonCode($transaction->getReasonCode())
                    ->setMerchantCurrency($transaction->getMerchantCurrency())
                    ->setMerchantCurrencyAlpha($transaction->getMerchantCurrencyAlpha())
                    ->setCardHolderCurrency($transaction->getCardHolderCurrency())
                    ->setCardHolderCurrencyAlpha($transaction->getCardHolderCurrencyAlpha())
                    ->setReservedAmount($transaction->getReservedAmount())
                    ->setCapturedAmount($transaction->getCapturedAmount())
                    ->setRefundedAmount($transaction->getRefundedAmount())
                    ->setRecurringDefaultAmount($transaction->getRecurringDefaultAmount())
                    ->setCreatedDate($transaction->getCreatedDate())
                    ->setUpdatedDate($transaction->getUpdatedDate())
                    ->setPaymentNature($transaction->getPaymentNature())
                    ->setSupportsRefunds($transaction->getPaymentNatureService()->isSupportsRefunds())
                    ->setSupportsRelease($transaction->getPaymentNatureService()->isSupportsRelease())
                    ->setSupportsMultipleCaptures($transaction->getPaymentNatureService()->isSupportsMultipleCaptures())
                    ->setSupportsMultipleRefunds($transaction->getPaymentNatureService()->isSupportsMultipleRefunds())
                    ->setFraudRiskScore($transaction->getFraudRiskScore())
                    ->setFraudExplanation($transaction->getFraudExplanation())
                ;
                $paymentManager->update($payment);
            }
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
