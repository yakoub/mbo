<?php

namespace App\Controller;

use App\Entity\MBOYearly;
use App\Form\MBOYearlyType;
use App\Repository\MBOYearlyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mbo_yearly")
 */
class MBOYearlyController extends Controller
{
    /**
     * @Route("/", name="mbo_yearly_index", methods="GET")
     */
    public function index(MBOYearlyRepository $mBOYearlyRepository): Response
    {
        return $this->render('mbo_yearly/index.html.twig', ['mbo_yearlies' => $mBOYearlyRepository->findAll()]);
    }

    /**
     * @Route("/new", name="mbo_yearly_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $mBOYearly = new MBOYearly();
        $form = $this->createForm(MBOYearlyType::class, $mBOYearly);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($mBOYearly);
            $em->flush();

            return $this->redirectToRoute('mbo_yearly_index');
        }

        return $this->render('mbo_yearly/new.html.twig', [
            'mbo_yearly' => $mBOYearly,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="mbo_yearly_show", methods="GET")
     */
    public function show(MBOYearly $mBOYearly): Response
    {
        return $this->render('mbo_yearly/show.html.twig', ['mbo_yearly' => $mBOYearly]);
    }

    /**
     * @Route("/{id}/edit", name="mbo_yearly_edit", methods="GET|POST")
     */
    public function edit(Request $request, MBOYearly $mBOYearly): Response
    {
        $form = $this->createForm(MBOYearlyType::class, $mBOYearly);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mbo_yearly_edit', ['id' => $mBOYearly->getId()]);
        }

        return $this->render('mbo_yearly/edit.html.twig', [
            'mbo_yearly' => $mBOYearly,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="mbo_yearly_delete", methods="DELETE")
     */
    public function delete(Request $request, MBOYearly $mBOYearly): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mBOYearly->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($mBOYearly);
            $em->flush();
        }

        return $this->redirectToRoute('mbo_yearly_index');
    }
}