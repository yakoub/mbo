<?php

namespace App\Controller;

use App\Entity\ObjectiveManagement;
use App\Form\ObjectiveManagementType;
use App\Repository\ObjectiveManagementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/objective-management")
 */
class ObjectiveManagementController extends AbstractController
{
    /**
     * @Route("/", name="objective_management_index", methods="GET")
     */
    public function index(ObjectiveManagementRepository $objectiveManagementRepository): Response
    {
        return $this->render('objective_management/index.html.twig', ['objective_managements' => $objectiveManagementRepository->findAll()]);
    }

    /**
     * @Route("/new", name="objective_management_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $objectiveManagement = new ObjectiveManagement();
        $form = $this->createForm(ObjectiveManagementType::class, $objectiveManagement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($objectiveManagement);
            $em->flush();

            return $this->redirectToRoute('objective_management_index');
        }

        return $this->render('objective_management/new.html.twig', [
            'objective_management' => $objectiveManagement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="objective_management_show", methods="GET")
     */
    public function show(ObjectiveManagement $objectiveManagement): Response
    {
        return $this->render('objective_management/show.html.twig', ['objective_management' => $objectiveManagement]);
    }

    /**
     * @Route("/{id}/edit", name="objective_management_edit", methods="GET|POST")
     */
    public function edit(Request $request, ObjectiveManagement $objectiveManagement): Response
    {
        $form = $this->createForm(ObjectiveManagementType::class, $objectiveManagement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('objective_management_edit', ['id' => $objectiveManagement->getId()]);
        }

        return $this->render('objective_management/edit.html.twig', [
            'objective_management' => $objectiveManagement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="objective_management_delete", methods="DELETE")
     */
    public function delete(Request $request, ObjectiveManagement $objectiveManagement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$objectiveManagement->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($objectiveManagement);
            $em->flush();
        }

        return $this->redirectToRoute('objective_management_index');
    }
}
