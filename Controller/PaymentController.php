<?php

namespace Loevgaard\DandomainAltapayBundle\Controller;

use Loevgaard\Dandomain\Pay\Handler;
use Loevgaard\DandomainAltapayBundle\Annotation\LogHttpTransaction;
use Loevgaard\DandomainAltapayBundle\Entity\Payment;
use Loevgaard\DandomainAltapayBundle\Exception\AltapayPaymentRequestException;
use Loevgaard\DandomainAltapayBundle\Exception\ChecksumMismatchException;
use Loevgaard\DandomainAltapayBundle\Exception\PaymentException;
use Loevgaard\DandomainAltapayBundle\Exception\TerminalNotFoundException;
use Loevgaard\DandomainAltapayBundle\Handler\PaymentHandler;
use Loevgaard\DandomainAltapayBundle\PayloadGenerator\PaymentRequestPayloadGenerator;
use Loevgaard\DandomainAltapayBundle\PsrHttpMessage\DiactorosTrait;
use Loevgaard\DandomainAltapayBundle\Translation\TranslatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/payment")
 */
class PaymentController extends Controller
{
    use TranslatorTrait;
    use DiactorosTrait;

    /**
     * @Method("GET")
     * @Route("", name="loevgaard_dandomain_altapay_payment_index")
     *
     * @return Response
     */
    public function indexAction()
    {
        $paymentManager = $this->container->get('loevgaard_dandomain_altapay.payment_manager');

        /** @var Payment[] $payments */
        $payments = $paymentManager->getRepository()->findAll();

        return $this->render('@LoevgaardDandomainAltapay/payment/index.html.twig', [
            'payments' => $payments,
        ]);
    }

    /**
     * @Method("GET")
     * @Route("/{paymentId}/show", name="loevgaard_dandomain_altapay_payment_show")
     *
     * @param int $paymentId
     *
     * @return Response
     */
    public function showAction(int $paymentId)
    {
        $payment = $this->getPaymentFromId($paymentId);
        if (!$payment) {
            throw $this->createNotFoundException('Payment with id `'.$paymentId.'` not found');
        }

        return $this->render('@LoevgaardDandomainAltapay/payment/show.html.twig', [
            'payment' => $payment,
        ]);
    }

    /**
     * Payment flow
     * 1. The Dandomain payment API POSTs to this page with the terminal slug in the URL
     * 2. After validating all input, we create a payment request to the Altapay API
     * 3. Finally we redirect the user to the URL given by the Altapay API.
     *
     * @Method("POST")
     * @Route("/{terminal}", name="loevgaard_dandomain_altapay_payment_new")
     *
     * @LogHttpTransaction()
     *
     * @param string  $terminal
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws PaymentException
     */
    public function newAction(string $terminal, Request $request)
    {
        $terminalManager = $this->container->get('loevgaard_dandomain_altapay.terminal_manager');
        $paymentManager = $this->container->get('loevgaard_dandomain_altapay.payment_manager');

        $psrRequest = $this->createPsrRequest($request);

        $handler = new Handler(
            $psrRequest,
            $this->container->getParameter('loevgaard_dandomain_altapay.shared_key_1'),
            $this->container->getParameter('loevgaard_dandomain_altapay.shared_key_2')
        );

        $dandomainPaymentRequest = $handler->getPaymentRequest();

        $paymentEntity = $paymentManager->createPaymentFromDandomainPaymentRequest($dandomainPaymentRequest);
        $paymentManager->update($paymentEntity);

        $terminalEntity = $terminalManager->findTerminalBySlug($terminal, true);
        if (!$terminalEntity) {
            throw TerminalNotFoundException::create($this->trans('payment.exception.terminal_not_found', ['%terminal%' => $terminal]), $request, $paymentEntity);
        }

        if (!$handler->checksumMatches()) {
            throw ChecksumMismatchException::create($this->trans('payment.exception.checksum_mismatch'), $request, $paymentEntity);
        }

        $paymentRequestPayloadGenerator = new PaymentRequestPayloadGenerator($this->container, $dandomainPaymentRequest, $terminalEntity, $paymentEntity, $handler);
        $paymentRequestPayload = $paymentRequestPayloadGenerator->generate();

        $altapay = $this->container->get('loevgaard_dandomain_altapay.altapay_client');
        $response = $altapay->createPaymentRequest($paymentRequestPayload);

        if (!$response->isSuccessful()) {
            throw AltapayPaymentRequestException::create($this->trans('payment.exception.altapay_payment_request', ['%gateway_message%' => $response->getErrorMessage()]), $request, $paymentEntity);
        }

        return $this->redirect($response->getUrl());
    }

    /**
     * @Method("GET")
     * @Route("/{paymentId}/capture", name="loevgaard_dandomain_altapay_payment_capture")
     *
     * @param int     $paymentId
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function captureAction(int $paymentId, Request $request)
    {
        $payment = $this->getPaymentFromId($paymentId);

        if ($payment) {
            $paymentHandler = $this->getPaymentHandler();
            $res = $paymentHandler->capture($payment, $request->query->get('amount'));

            if ($res->isSuccessful()) {
                $this->addFlash('success', 'The payment for order '.$payment->getOrderId().' was captured.'); // @todo fix translation
            } else {
                $this->addFlash('danger', 'An error occurred during capture of the payment: '.$res->getErrorMessage()); // @todo fix translation
            }
        }

        $redirect = $request->headers->get('referer') ? $request->headers->get('referer') : $this->generateUrl('loevgaard_dandomain_altapay_payment_index');

        return $this->redirect($redirect);
    }

    /**
     * @Method({"POST", "GET"})
     * @Route("/{paymentId}/refund", name="loevgaard_dandomain_altapay_payment_refund")
     *
     * @param int     $paymentId
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function refundAction(int $paymentId, Request $request)
    {
        $payment = $this->getPaymentFromId($paymentId);

        if ($payment) {
            $paymentHandler = $this->getPaymentHandler();
            $res = $paymentHandler->refund($payment, null, $request->query->get('amount'));

            if ($res->isSuccessful()) {
                $this->addFlash('success', 'The payment for order '.$payment->getOrderId().' was refunded.'); // @todo fix translation
            } else {
                $this->addFlash('danger', 'An error occurred during refund of the payment: '.$res->getErrorMessage()); // @todo fix translation
            }
        }

        $redirect = $request->headers->get('referer') ?: $this->generateUrl('loevgaard_dandomain_altapay_payment_index');

        return $this->redirect($redirect);
    }

    /**
     * @Method("GET")
     * @Route("/{paymentId}/redirectToAltapay", name="loevgaard_dandomain_altapay_redirect_to_altapay_payment")
     *
     * @param int     $paymentId
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function redirectToAltapayPaymentAction(int $paymentId, Request $request)
    {
        $payment = $this->getPaymentFromId($paymentId);

        $url = $this->getParameter('loevgaard_dandomain_altapay.altapay_url').'/merchant/transactions/paymentDetails/'.$payment->getAltapayId();

        return $this->redirect($url);
    }

    /**
     * @param int $paymentId
     *
     * @return Payment
     */
    private function getPaymentFromId(int $paymentId): Payment
    {
        $paymentManager = $this->get('loevgaard_dandomain_altapay.payment_manager');

        /** @var Payment $payment */
        $payment = $paymentManager->getRepository()->find($paymentId);

        if (!$payment) {
            throw $this->createNotFoundException('Payment with id `'.$paymentId.'` not found');
        }

        return $payment;
    }

    /**
     * @return PaymentHandler
     */
    private function getPaymentHandler(): PaymentHandler
    {
        return $this->get('loevgaard_dandomain_altapay.payment_handler');
    }
}
