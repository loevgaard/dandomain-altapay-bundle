<?php

namespace Loevgaard\DandomainAltapayBundle\Controller;

use Loevgaard\DandomainAltapayBundle\Exception\NotAllowedIpException;
use Loevgaard\DandomainAltapayBundle\Exception\PaymentException;
use Loevgaard\DandomainFoundationBundle\Manager\PaymentManager;
use Loevgaard\DandomainFoundationBundle\Model\Payment;
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
     * @param Request $request
     *
     * @return Response
     */
    public function okAction(Request $request)
    {
        $payment = $this->handleCallback($request);

        return new Response('OK');
    }

    /**
     * @Method("POST")
     * @Route("/fail", name="loevgaard_dandomain_altapay_callback_fail")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function failAction(Request $request)
    {
        $payment = $this->handleCallback($request);

        return new Response('OK');
    }

    /**
     * @Method("POST")
     * @Route("/redirect", name="loevgaard_dandomain_altapay_callback_redirect")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function redirectAction(Request $request)
    {
        $payment = $this->handleCallback($request);

        return new Response('OK');
    }

    /**
     * @Method("POST")
     * @Route("/open", name="loevgaard_dandomain_altapay_callback_open")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function openAction(Request $request)
    {
        $payment = $this->handleCallback($request);

        return new Response('OK');
    }

    /**
     * @Method("POST")
     * @Route("/notification", name="loevgaard_dandomain_altapay_callback_notification")
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
        $callbackManager = $this->container->get('loevgaard_dandomain_altapay.callback_manager');
        $callback = $callbackManager->createCallbackFromRequest($request);
        $callback->setPayment($payment);

        $callbackManager->update($callback);

        $allowedIps = $this->container->getParameter('loevgaard_dandomain_altapay.altapay_ips');
        if ($this->container->get('kernel')->getEnvironment() === 'prod' && !in_array($request->getClientIp(), $allowedIps)) {
            throw NotAllowedIpException::create('IP `'.$request->getClientIp().'` is not an allowed IP.', $request, $payment);
        }

        return $payment;
    }

    /**
     * @param Request $request
     *
     * @return Payment
     */
    protected function getPaymentFromRequest(Request $request)
    {
        $paymentId = $request->cookies->getInt('payment_id');
        $paymentManager = $this->getPaymentManager();

        /** @var Payment $payment */
        $payment = $paymentManager->getRepository()->find($paymentId);

        if (!$payment) {
            // @todo fix exception
            throw new \RuntimeException('Payment '.$paymentId.' does not exist');
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
        return $this->container->get('loevgaard_dandomain_foundation.payment_manager');
    }
}
