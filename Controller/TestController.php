<?php

namespace Loevgaard\DandomainAltapayBundle\Controller;

use Loevgaard\Dandomain\Pay\Handler;
use Loevgaard\Dandomain\Pay\PaymentRequest;
use Loevgaard\DandomainAltapayBundle\Entity\TerminalInterface;
use Loevgaard\DandomainAltapayBundle\Form\TestType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/test")
 */
class TestController extends Controller
{
    /**
     * @Method("GET")
     * @Route("", name="loevgaard_dandomain_altapay_test_index")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        /** @var TerminalInterface[] $terminals */
        $terminals = $this->get('loevgaard_dandomain_altapay.terminal_manager')->getRepository()->findAll();

        $form = $this->createForm(TestType::class);

        return $this->render('@LoevgaardDandomainAltapay/test/index.html.twig', [
            'form' => $form->createView(),
            'shared_key_1' => $this->getParameter('loevgaard_dandomain_altapay.shared_key_1'),
            'shared_key_2' => $this->getParameter('loevgaard_dandomain_altapay.shared_key_2'),
            'terminals' => $terminals,
        ]);
    }

    /**
     * @Method("GET")
     * @Route("/checksum1/{orderId}/{amount}/{sharedKey}/{currency}", name="loevgaard_dandomain_altapay_test_checksum_1")
     *
     * @param int    $orderId
     * @param string $amount
     * @param string $sharedKey
     * @param int    $currency
     *
     * @return JsonResponse
     */
    public function checksum1Action($orderId, $amount, $sharedKey, $currency)
    {
        $amount = PaymentRequest::currencyStringToFloat($amount);

        return new JsonResponse(Handler::generateChecksum1((int) $orderId, $amount, $sharedKey, (int) $currency));
    }
}
