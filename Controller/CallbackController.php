<?php
namespace Loevgaard\DandomainAltapayBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/callback")
 */
class CallbackController extends Controller {
    /**
     * @Method("POST")
     * @Route("/form", name="loevgaard_dandomain_altapay_callback_form")
     *
     * @param Request $request
     * @return Response
     */
    public function formAction(Request $request)
    {
        return $this->render('@LoevgaardDandomainAltapay/callback/form.html.twig');
    }
}