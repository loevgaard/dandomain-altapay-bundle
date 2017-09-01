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
     * @param $terminal
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws PaymentException
     */
    public function newAction($terminal, Request $request)
    {
        // @todo log the raw http request

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

        $terminalEntity = $terminalManager->findTerminalBySlug($terminal);
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

        foreach ($dandomainPaymentRequest->getOrderLines() as $orderLine) {
            $orderLinePayload = new OrderLinePayload(
                $orderLine->getName(),
                $orderLine->getProductNumber(),
                $orderLine->getQuantity(),
                $orderLine->getPrice(),
                $orderLine->getVat()
            );

            $paymentRequestPayload->addOrderLine($orderLinePayload);
        }

        $configPayload = new ConfigPayload(
            $this->generateUrl('loevgaard_dandomain_altapay_callback_form', [],
                UrlGeneratorInterface::ABSOLUTE_URL),
            $this->generateUrl('loevgaard_dandomain_altapay_callback_ok', [], UrlGeneratorInterface::ABSOLUTE_URL),
            $this->generateUrl('loevgaard_dandomain_altapay_callback_fail', [],
                UrlGeneratorInterface::ABSOLUTE_URL),
            $this->generateUrl('loevgaard_dandomain_altapay_callback_redirect', [],
                UrlGeneratorInterface::ABSOLUTE_URL),
            $this->generateUrl('loevgaard_dandomain_altapay_callback_open', [],
                UrlGeneratorInterface::ABSOLUTE_URL),
            $this->generateUrl('loevgaard_dandomain_altapay_callback_notification', [],
                UrlGeneratorInterface::ABSOLUTE_URL)
        );
        $paymentRequestPayload->setConfig($configPayload);

        // @todo the payment_id should be a const somewhere
        $paymentRequestPayload
            ->setCookiePart('payment_id', $paymentEntity->getId())
            ->setCookiePart('checksum_complete', $handler->getChecksum2())
        ;

        $altapay = $this->container->get('loevgaard_dandomain_altapay.altapay_client');
        $response = $altapay->createPaymentRequest($paymentRequestPayload);

        if (!$response->isSuccessful()) {
            throw AltapayPaymentRequestException::create('An error occured during payment request. Try again.', $request, $paymentEntity);
        }

        echo $response->getUrl();
        exit;

        return new RedirectResponse($response->getUrl());
    }
}
