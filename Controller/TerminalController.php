<?php

namespace Loevgaard\DandomainAltapayBundle\Controller;

use Loevgaard\DandomainAltapayBundle\Entity\Terminal;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/terminal")
 */
class TerminalController extends Controller
{
    /**
     * @Method("GET")
     * @Route("", name="loevgaard_dandomain_altapay_terminal_index")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $terminalRepository = $this->container->get('loevgaard_dandomain_altapay.terminal_repository');

        /** @var Terminal[] $terminals */
        $terminals = $terminalRepository->findAllWithPaging($request->query->getInt('page', 1));

        return $this->render('@LoevgaardDandomainAltapay/terminal/index.html.twig', [
            'terminals' => $terminals,
        ]);
    }
}
