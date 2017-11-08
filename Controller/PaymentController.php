<?php

namespace Loevgaard\DandomainAltapayBundle\Controller;

use Loevgaard\Dandomain\Pay\Helper\ChecksumHelper;
use Loevgaard\DandomainAltapayBundle\Annotation\LogHttpTransaction;
use Loevgaard\DandomainAltapayBundle\Entity\Payment;
use Loevgaard\DandomainAltapayBundle\Event\PaymentCreated;
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
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $paymentRepository = $this->container->get('loevgaard_dandomain_altapay.payment_repository');

        /** @var Payment[] $payments */
        $payments = $paymentRepository->findAllWithPaging($request->query->getInt('page', 1));

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
        $terminalRepository = $this->container->get('loevgaard_dandomain_altapay.terminal_repository');
        $paymentRepository = $this->container->get('loevgaard_dandomain_altapay.payment_repository');
        $eventRepository = $this->container->get('loevgaard_dandomain_altapay.event_repository');
        $translator = $this->getTranslator();

        $psrRequest = $this->createPsrRequest($request);
        /** @var Payment $paymentEntity */
        $paymentEntity = Payment::createFromRequest($psrRequest);

        $checksumHelper = new ChecksumHelper(
            $paymentEntity,
            $this->container->getParameter('loevgaard_dandomain_altapay.shared_key_1'),
            $this->container->getParameter('loevgaard_dandomain_altapay.shared_key_2')
        );

        $paymentRepository->save($paymentEntity);

        $eventRepository->saveEvent(new PaymentCreated($paymentEntity));

        $terminalEntity = $terminalRepository->findTerminalBySlug($terminal, true);
        if (!$terminalEntity) {
            throw TerminalNotFoundException::create($translator->trans('payment.exception.terminal_not_found', ['%terminal%' => $terminal], 'LoevgaardDandomainAltapayBundle'), $request, $paymentEntity);
        }

        if (!$checksumHelper->checksumMatches()) {
            throw ChecksumMismatchException::create($translator->trans('payment.exception.checksum_mismatch', [], 'LoevgaardDandomainAltapayBundle'), $request, $paymentEntity);
        }

        $paymentRequestPayloadGenerator = new PaymentRequestPayloadGenerator($this->container, $paymentEntity, $terminalEntity, $paymentEntity, $checksumHelper);
        $paymentRequestPayload = $paymentRequestPayloadGenerator->generate();

        $altapay = $this->container->get('loevgaard_dandomain_altapay.altapay_client');
        $response = $altapay->createPaymentRequest($paymentRequestPayload);

        if (!$response->isSuccessful()) {
            throw AltapayPaymentRequestException::create($translator->trans('payment.exception.altapay_payment_request', ['%gateway_message%' => $response->getErrorMessage()], 'LoevgaardDandomainAltapayBundle'), $request, $paymentEntity);
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
     * @param int $paymentId
     *
     * @return RedirectResponse
     */
    public function redirectToAltapayPaymentAction(int $paymentId)
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
        $paymentRepository = $this->get('loevgaard_dandomain_altapay.payment_repository');

        /** @var Payment $payment */
        $payment = $paymentRepository->find($paymentId);

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
