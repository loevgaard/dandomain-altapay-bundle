<?php
namespace Loevgaard\DandomainAltapayBundle\Controller;

use Loevgaard\AltaPay\Payload\PaymentRequest as PaymentRequestPayload;
use Loevgaard\AltaPay\Payload\OrderLine as OrderLinePayload;
use Loevgaard\AltaPay\Payload\PaymentRequest\Config as ConfigPayload;
use Loevgaard\Dandomain\Pay\Handler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends Controller {
    /**
     * @Method("POST")
     * @Route("/{terminal}")
     *
     * @param string $terminal
     * @param Request $request
     * @return RedirectResponse
     */
    public function newAction($terminal, Request $request)
    {
        $terminalManager = $this->container->get('loevgaard_dandomain_altapay.terminal_manager');
        $paymentManager = $this->container->get('loevgaard_dandomain_altapay.payment_manager');
        $terminalEntity = $terminalManager->findTerminalBySlug($terminal);

        if(!$terminalEntity) {
            throw new \RuntimeException('Terminal `'.$terminal.'` not found');
        }

        $handler = new Handler(
            $request,
            $this->container->getParameter('loevgaard_dandomain_altapay.shared_key_1'),
            $this->container->getParameter('loevgaard_dandomain_altapay.shared_key_2')
        );

        if(!$handler->checksumMatches()) {
            // @todo what should happen here?
            throw new \RuntimeException('Checksum mismatch. Try again');
        }

        $paymentRequest = $handler->getPaymentRequest();

        $paymentEntity = $paymentManager->createPaymentFromDandomainPaymentRequest($paymentRequest);
        $paymentManager->updatePayment($paymentEntity);

        $paymentRequestPayload = new PaymentRequestPayload(
            $terminalEntity->getTitle(),
            $paymentRequest->getOrderId(),
            $paymentRequest->getTotalAmount(),
            $paymentRequest->getCurrencySymbol()
        );

        foreach ($paymentRequest->getOrderLines() as $orderLine) {
            $orderLinePayload = new OrderLinePayload(
                $orderLine->getName(),
                $orderLine->getProductNumber(),
                $orderLine->getQuantity(),
                $orderLine->getPrice(),
                $orderLine->getVat()
            );

            $paymentRequestPayload->addOrderLine($orderLinePayload);
        }

        $configPayload = new ConfigPayload($this->generateUrl('loevgaard_dandomain_altapay_callback_form', [], UrlGeneratorInterface::ABSOLUTE_URL)); // @todo set the callback urls
        $paymentRequestPayload->setConfig($configPayload);

        //$paymentRequestPayload->setCookie(); @todo set payment request entity id in the cookie so we can use this in callbacks


        $altapay = $this->container->get('loevgaard_dandomain_altapay.altapay_client');
        $response = $altapay->createPaymentRequest($paymentRequestPayload);

        if(!$response->isSuccessful()) {
            // @todo fix this
            throw new \RuntimeException('An error occurred during payment request.');
        }

        echo $response->getUrl();exit;
        return new RedirectResponse($response->getUrl());
    }
}