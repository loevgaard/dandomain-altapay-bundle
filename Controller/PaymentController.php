<?php

namespace Loevgaard\DandomainAltapayBundle\Controller;

use Loevgaard\AltaPay\Payload\OrderLine as OrderLinePayload;
use Loevgaard\AltaPay\Payload\PaymentRequest as PaymentRequestPayload;
use Loevgaard\AltaPay\Payload\PaymentRequest\Config as ConfigPayload;
use Loevgaard\Dandomain\Pay\Handler;
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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Loevgaard\DandomainAltapayBundle\Annotation\LogHttpTransaction;

/**
 * @Route("/payment")
 */
class PaymentController extends Controller
{
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
        $paymentManager = $this->container->get('loevgaard_dandomain_foundation.payment_manager');

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
            throw TerminalNotFoundException::create('Terminal `'.$terminal.'` does not exist', $request, $paymentEntity);
        }

        if (!$handler->checksumMatches()) {
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
            throw AltapayPaymentRequestException::create('An error occured during payment request. Try again.', $request, $paymentEntity);
        }

        return new RedirectResponse($response->getUrl());
    }
}
