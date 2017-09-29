<?php

namespace Loevgaard\DandomainAltapayBundle\Controller;

use Loevgaard\AltaPay\Payload\CaptureReservation as CaptureReservationPayload;
use Loevgaard\AltaPay\Payload\RefundCapturedReservation as RefundCapturedReservationPayload;
use Loevgaard\AltaPay\Payload\OrderLine as OrderLinePayload;
use Loevgaard\AltaPay\Payload\PaymentRequest as PaymentRequestPayload;
use Loevgaard\AltaPay\Payload\PaymentRequest\Config as ConfigPayload;
use Loevgaard\AltaPay\Payload\PaymentRequest\CustomerInfo as CustomerInfoPayload;
use Loevgaard\Dandomain\Pay\Handler;
use Loevgaard\DandomainAltapayBundle\Annotation\LogHttpTransaction;
use Loevgaard\DandomainAltapayBundle\Entity\Payment;
use Loevgaard\DandomainAltapayBundle\Exception\AltapayPaymentRequestException;
use Loevgaard\DandomainAltapayBundle\Exception\ChecksumMismatchException;
use Loevgaard\DandomainAltapayBundle\Exception\PaymentException;
use Loevgaard\DandomainAltapayBundle\Exception\TerminalNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/payment")
 */
class PaymentController extends Controller
{
    /**
     * @Method("GET")
     * @Route("", name="loevgaard_dandomain_altapay_payment_index")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $paymentManager = $this->container->get('loevgaard_dandomain_altapay.payment_manager');

        /** @var Payment[] $payments */
        $payments = $paymentManager->getRepository()->findAll();

        return $this->render('@LoevgaardDandomainAltapay/payment/index.html.twig', [
            'payments' => $payments,
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
     * @param $terminal
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws PaymentException
     */
    public function newAction($terminal, Request $request)
    {
        $terminalManager = $this->container->get('loevgaard_dandomain_altapay.terminal_manager');
        $paymentManager = $this->container->get('loevgaard_dandomain_altapay.payment_manager');

        // convert symfony request to PSR7 request
        $psr7Factory = new DiactorosFactory();
        $psrRequest = $psr7Factory->createRequest($request);

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
            // @todo fix translation
            throw TerminalNotFoundException::create('Terminal `'.$terminal.'` does not exist', $request, $paymentEntity);
        }

        if (!$handler->checksumMatches()) {
            // @todo fix translation
            throw ChecksumMismatchException::create('Checksum mismatch. Try again', $request, $paymentEntity);
        }

        $paymentRequestPayload = new PaymentRequestPayload(
            $terminalEntity->getTitle(),
            $dandomainPaymentRequest->getOrderId(),
            $dandomainPaymentRequest->getTotalAmount(),
            $dandomainPaymentRequest->getCurrencySymbol()
        );

        foreach ($dandomainPaymentRequest->getPaymentLines() as $paymentLine) {
            $orderLinePayload = new OrderLinePayload(
                $paymentLine->getName(),
                $paymentLine->getProductNumber(),
                $paymentLine->getQuantity(),
                $paymentLine->getPrice()
            );
            $orderLinePayload->setTaxPercent($paymentLine->getVat());

            $paymentRequestPayload->addOrderLine($orderLinePayload);
        }

        $customerInfoPayload = new CustomerInfoPayload();
        $customerNames = explode(' ', $dandomainPaymentRequest->getCustomerName(), 2);
        $shippingNames = explode(' ', $dandomainPaymentRequest->getDeliveryName(), 2);
        $customerInfoPayload
            ->setBillingFirstName($customerNames[0] ?? '')
            ->setBillingLastName($customerNames[1] ?? '')
            ->setBillingAddress(
                $dandomainPaymentRequest->getCustomerAddress().
                ($dandomainPaymentRequest->getCustomerAddress2() ? "\r\n".$dandomainPaymentRequest->getCustomerAddress2() : '')
            )
            ->setBillingPostal($dandomainPaymentRequest->getCustomerZipCode())
            ->setBillingCity($dandomainPaymentRequest->getCustomerCity())
            ->setBillingCountry($dandomainPaymentRequest->getCustomerCountry())
            ->setShippingFirstName($shippingNames[0] ?? '')
            ->setShippingLastName($shippingNames[1] ?? '')
            ->setShippingAddress(
                $dandomainPaymentRequest->getDeliveryAddress().
                ($dandomainPaymentRequest->getDeliveryAddress2() ? "\r\n".$dandomainPaymentRequest->getDeliveryAddress2() : '')
            )
            ->setShippingPostal($dandomainPaymentRequest->getDeliveryZipCode())
            ->setShippingCity($dandomainPaymentRequest->getDeliveryCity())
            ->setShippingCountry($dandomainPaymentRequest->getDeliveryCountry())
        ;
        $paymentRequestPayload->setCustomerInfo($customerInfoPayload);

        $configPayload = new ConfigPayload();
        $configPayload
            ->setCallbackForm($this->generateUrl('loevgaard_dandomain_altapay_callback_form', [], UrlGeneratorInterface::ABSOLUTE_URL))
            ->setCallbackOk($this->generateUrl('loevgaard_dandomain_altapay_callback_ok', [], UrlGeneratorInterface::ABSOLUTE_URL))
            ->setCallbackFail($this->generateUrl('loevgaard_dandomain_altapay_callback_fail', [], UrlGeneratorInterface::ABSOLUTE_URL))
            ->setCallbackRedirect($this->generateUrl('loevgaard_dandomain_altapay_callback_redirect', [], UrlGeneratorInterface::ABSOLUTE_URL))
            ->setCallbackOpen($this->generateUrl('loevgaard_dandomain_altapay_callback_open', [], UrlGeneratorInterface::ABSOLUTE_URL))
            ->setCallbackNotification($this->generateUrl('loevgaard_dandomain_altapay_callback_notification', [], UrlGeneratorInterface::ABSOLUTE_URL))
        ;
        $paymentRequestPayload->setConfig($configPayload);

        $paymentRequestPayload
            ->setCookiePart($this->getParameter('loevgaard_dandomain_altapay.cookie_payment_id'), $paymentEntity->getId())
            ->setCookiePart($this->getParameter('loevgaard_dandomain_altapay.cookie_checksum_complete'), $handler->getChecksum2())
        ;

        $altapay = $this->container->get('loevgaard_dandomain_altapay.altapay_client');
        $response = $altapay->createPaymentRequest($paymentRequestPayload);

        if (!$response->isSuccessful()) {
            // @todo fix translation
            throw AltapayPaymentRequestException::create('An error occured during payment request. Try again. Message from gateway: '.$response->getErrorMessage(), $request, $paymentEntity);
        }

        return new RedirectResponse($response->getUrl());
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
            $altapayClient = $this->get('loevgaard_dandomain_altapay.altapay_client');

            $payload = new CaptureReservationPayload($payment->getAltapayId());
            $res = $altapayClient->captureReservation($payload);

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
            $altapayClient = $this->get('loevgaard_dandomain_altapay.altapay_client');

            $payload = new RefundCapturedReservationPayload($payment->getAltapayId());
            $res = $altapayClient->refundCapturedReservation($payload);

            if ($res->isSuccessful()) {
                $this->addFlash('success', 'The payment for order '.$payment->getOrderId().' was refunded.'); // @todo fix translation
            } else {
                $this->addFlash('danger', 'An error occurred during refund of the payment: '.$res->getErrorMessage()); // @todo fix translation
            }
        }

        $redirect = $request->headers->get('referer') ? $request->headers->get('referer') : $this->generateUrl('loevgaard_dandomain_altapay_payment_index');

        return $this->redirect($redirect);
    }

    /**
     * @param int $paymentId
     * @return Payment|null
     */
    private function getPaymentFromId(int $paymentId) : ?Payment
    {
        $paymentManager = $this->get('loevgaard_dandomain_altapay.payment_manager');

        /** @var Payment $payment */
        $payment = $paymentManager->getRepository()->find($paymentId);

        return $payment;
    }
}
