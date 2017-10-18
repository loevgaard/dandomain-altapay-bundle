<?php

namespace Loevgaard\DandomainAltapayBundle\Controller;

use Loevgaard\DandomainAltapayBundle\Entity\Terminal;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @return Response
     */
    public function indexAction()
    {
        $terminalManager = $this->container->get('loevgaard_dandomain_altapay.terminal_manager');

        /** @var Terminal[] $terminals */
        $terminals = $terminalManager->getRepository()->findAll();

        return $this->render('@LoevgaardDandomainAltapay/terminal/index.html.twig', [
            'terminals' => $terminals,
        ]);
    }
}
