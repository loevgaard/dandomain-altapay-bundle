<?php

namespace Loevgaard\DandomainAltapayBundle\Controller;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use Loevgaard\AltaPay\Client;
use Loevgaard\AltaPay\Payload\CaptureReservation;
use Loevgaard\AltaPay\Payload\RefundCapturedReservation;
use Loevgaard\DandomainAltapayBundle\Entity\Payment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends FOSRestController
{
    /**
     * @Annotations\Get("/payment/{id}/capture")
     *
     * @param string|int $id Can be both altapay id or order id
     * @return Response
     */
    public function paymentCaptureAction($id)
    {
        $data = ['error' => false];

        $payment = $this->findPaymentByOrderIdOrAltapayId($id);

        $altapay = $this->getAltapayClient();

        $payload = new CaptureReservation($payment->getAltapayId());
        $res = $altapay->captureReservation($payload);

        if($res->isSuccessful()) {
            $transactions = $res->getTransactions();
            if(count($transactions)) {
                $data['captured_amount'] = $transactions[0]->getCapturedAmount();
            }
        } else {
            $data['error'] = true;
        }

        $view = $this->view($data);
        $view->setTemplate('@LoevgaardDandomainAltapay/api/payment_capture.html.twig');

        return $this->handleView($view);
    }

    /**
     * @Annotations\Post("/payment/{id}/refund")
     *
     * @param Request $request
     * @param string|int $id Can be both altapay id or order id
     * @return Response
     */
    public function paymentRefundAction(Request $request, $id)
    {
        $data = ['error' => false];

        $payment = $this->findPaymentByOrderIdOrAltapayId($id);

        $altapay = $this->getAltapayClient();

        $payload = new RefundCapturedReservation($payment->getAltapayId());
        $res = $altapay->refundCapturedReservation($payload);

        if($res->isSuccessful()) {
            $transactions = $res->getTransactions();
            if(count($transactions)) {
                $data['refunded_amount'] = $transactions[0]->getRefundedAmount();
            }
        } else {
            $data['error'] = true;
        }

        $view = $this->view($data);
        $view->setTemplate('@LoevgaardDandomainAltapay/api/payment_capture.html.twig');

        return $this->handleView($view);
    }

    /**
     * @param string|int $id
     * @return Payment
     */
    protected function findPaymentByOrderIdOrAltapayId($id) : Payment
    {
        $paymentManager = $this->get('loevgaard_dandomain_altapay.payment_manager');
        $payment = $paymentManager->findByOrderIdOrAltapayId($id);
        if(!$payment) {
            throw $this->createNotFoundException();
        }

        return $payment;
    }

    /**
     * @return Client
     */
    protected function getAltapayClient() : Client
    {
        return $this->get('loevgaard_dandomain_altapay.altapay_client');
    }
}
