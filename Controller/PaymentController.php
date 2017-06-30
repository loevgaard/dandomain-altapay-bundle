<?php
namespace Loevgaard\DandomainAltapayBundle\Controller;

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
        $handler = new Handler($request, $this->container->getParameter('loevgaard_dandomain_altapay.shared_key_1'), $this->container->getParameter('loevgaard_dandomain_altapay.shared_key_2'));

        // @todo create service for altapay sdk client
    }
}