<?php
namespace Loevgaard\DandomainAltapayBundle\Controller;

use Loevgaard\AltaPay\Payload\OrderLine as OrderLinePayload;
use Loevgaard\AltaPay\Payload\PaymentRequest as PaymentRequestPayload;
use Loevgaard\Dandomain\Pay\Handler;
use Loevgaard\DandomainAltapayBundle\Entity\TerminalInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class PaymentController extends Controller {
    /**
     * @Route("/{terminal}")
     * @ParamConverter("terminal", options={"mapping": {"terminal": "canonicalTitle"}})
     *
     * @param TerminalInterface $terminal
     * @param Request $request
     */
    public function newAction(TerminalInterface $terminal, Request $request) {
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

        $paymentRequestPayload = new PaymentRequestPayload(
            $terminal->getTitle(),
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


        $altapay = $this->container->get('loevgaard_dandomain_altapay.altapay_client');
        $altapay->createPaymentRequest($paymentRequestPayload);
    }
}