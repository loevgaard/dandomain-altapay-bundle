<?php

namespace Loevgaard\DandomainAltapayBundle\Controller;

use Loevgaard\DandomainAltapayBundle\Entity\SiteSetting;
use Loevgaard\DandomainAltapayBundle\Form\SiteSettingType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/site-setting")
 */
class SiteSettingController extends Controller
{
    /**
     * @Method("GET")
     * @Route("", name="loevgaard_dandomain_altapay_site_setting_index")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $repos = $this->get('loevgaard_dandomain_altapay.site_setting_repository');
        $siteSettings = $repos->findAllWithPaging($request->query->getInt('page', 1));

        return $this->render('@LoevgaardDandomainAltapay/site_setting/index.html.twig', [
            'siteSettings' => $siteSettings,
        ]);
    }

    /**
     * @Method("GET")
     * @Route("/{id}/show", name="loevgaard_dandomain_altapay_site_setting_show")
     *
     * @param SiteSetting $siteSetting
     *
     * @return Response
     */
    public function showAction(SiteSetting $siteSetting)
    {
        return $this->render('@LoevgaardDandomainAltapay/site_setting/show.html.twig', [
            'siteSetting' => $siteSetting,
        ]);
    }

    /**
     * @Method({"GET", "POST"})
     * @Route("/new", name="loevgaard_dandomain_altapay_site_setting_new")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function newAction(Request $request)
    {
        $siteSetting = new SiteSetting();
        $form = $this->getForm($siteSetting);
        $res = $this->handleUpdate($form, $siteSetting, $request);
        if ($res) {
            return $res;
        }

        return $this->updateResponse($siteSetting, $form);
    }

    /**
     * @Method({"GET", "POST"})
     * @Route("/{id}/edit", name="loevgaard_dandomain_altapay_site_setting_edit")
     *
     * @param SiteSetting $siteSetting
     * @param Request     $request
     *
     * @return Response
     */
    public function editAction(SiteSetting $siteSetting, Request $request)
    {
        $form = $this->getForm($siteSetting);
        $res = $this->handleUpdate($form, $siteSetting, $request);
        if ($res) {
            return $res;
        }

        return $this->updateResponse($siteSetting, $form);
    }

    /**
     * @param FormInterface $form
     * @param SiteSetting   $siteSetting
     * @param Request       $request
     *
     * @return null|RedirectResponse
     */
    private function handleUpdate(FormInterface $form, SiteSetting $siteSetting, Request $request)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($siteSetting);
            $em->flush();

            $translator = $this->get('translator');

            $this->addFlash('success', $translator->trans('site_setting.edit.updated', [], 'LoevgaardDandomainAltapayBundle'));

            return $this->redirectToRoute('loevgaard_dandomain_altapay_site_setting_edit', [
                'id' => $siteSetting->getId(),
            ]);
        }

        return null;
    }

    /**
     * @param SiteSetting   $siteSetting
     * @param FormInterface $form
     *
     * @return Response
     */
    private function updateResponse(SiteSetting $siteSetting, FormInterface $form): Response
    {
        return $this->render('@LoevgaardDandomainAltapay/site_setting/edit.html.twig', [
            'siteSetting' => $siteSetting,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param SiteSetting $siteSetting
     *
     * @return FormInterface
     */
    private function getForm(SiteSetting $siteSetting): FormInterface
    {
        return $form = $this->createForm(SiteSettingType::class, $siteSetting);
    }
}
