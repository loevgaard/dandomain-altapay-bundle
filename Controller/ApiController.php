<?php

namespace Loevgaard\DandomainAltapayBundle\Controller;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use Loevgaard\DandomainAltapayBundle\Entity\Payment;
use Loevgaard\DandomainAltapayBundle\Handler\PaymentHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends FOSRestController
{
    /**
     * @Annotations\Get("/api/payment/{id}/capture")
     *
     * @param Request    $request
     * @param string|int $id      Can be both altapay id or order id
     *
     * @return Response
     */
    public function paymentCaptureAction(Request $request, $id)
    {
        $data = [
            'error' => false,
            'captured_amount' => null,
            'refunded_amount' => null,
        ];

        $payment = $this->findPaymentByOrderIdOrAltapayId($id);
        $paymentHandler = $this->getPaymentHandler();
        $res = $paymentHandler->capture($payment, $request->query->get('amount'));

        if ($res->isSuccessful()) {
            $transactions = $res->getTransactions();
            if (count($transactions)) {
                $data['captured_amount'] = $transactions[0]->getCapturedAmount();
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
     * @Annotations\Post("/api/payment/{id}/refund")
     *
     * @param Request    $request
     * @param string|int $id      Can be both altapay id or order id
     *
     * @return Response
     */
    public function paymentRefundAction(Request $request, $id)
    {
        $data = [
            'error' => false,
            'captured_amount' => null,
            'refunded_amount' => null,
        ];

        $payment = $this->findPaymentByOrderIdOrAltapayId($id);
        $paymentHandler = $this->getPaymentHandler();
        $res = $paymentHandler->refund($payment, $request->query->get('amount'));

        if ($res->isSuccessful()) {
            $transactions = $res->getTransactions();
            if (count($transactions)) {
                $data['captured_amount'] = $transactions[0]->getCapturedAmount();
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
     *
     * @return Payment
     */
    private function findPaymentByOrderIdOrAltapayId($id): Payment
    {
        $paymentRepository = $this->get('loevgaard_dandomain_altapay.payment_repository');
        $payment = $paymentRepository->findByOrderIdOrAltapayId($id);
        if (!$payment) {
            throw $this->createNotFoundException();
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
