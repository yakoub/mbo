<?php

namespace App\Controller;

use App\Entity\ObjectiveEntry;
use App\Entity\Person;
use App\Form\ObjectiveEntryType;
use App\Repository\ObjectiveEntryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/objective")]
class ObjectiveEntryController extends AbstractController
{
    #[Route("/", name: "objective_entry_index", methods: "GET")]
    public function index(ObjectiveEntryRepository $ObjectiveEntryRepository): Response
    {
        return $this->render('objective_entry/index.html.twig', ['objective_entries' => $ObjectiveEntryRepository->findAll()]);
    }

    #[Route("/new/{employee}", name: "objective_entry_new", methods: "GET|POST")]
    public function new(
        Request $request, 
        Person $employee, 
        ObjectiveEntryRepository $oe_repository,
        EntityManagerInterface $entityManager
        ): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $ObjectiveEntry = new ObjectiveEntry();
        $ObjectiveEntry->setForEmployee($employee);

        $ObjectiveEntry->setByManager($this->getUser());
        if ($request->query->has('year')) {
          $ObjectiveEntry->setYear($request->query->get('year'));
        }
        $other_years = $oe_repository->employeeYears($employee);
        $form = $this->createForm(ObjectiveEntryType::class, $ObjectiveEntry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ObjectiveEntry);
            $entityManager->flush();
            $param = ['year' => $ObjectiveEntry->getyear(),'employee' => $employee->getId()];
            return $this->redirectToRoute('mbo', $param);
        }

        return $this->render('objective_entry/new.html.twig', [
            'objective_entry' => $ObjectiveEntry,
            'form' => $form->createView(),
            'other_years' => $other_years,
        ]);
    }

    #[Route("/{id}", name: "objective_entry_show", methods: "GET")]
    public function show(ObjectiveEntry $ObjectiveEntry, ObjectiveEntryRepository $repository): Response
    {
        $year = $ObjectiveEntry->getYear();
        $employee = $ObjectiveEntry->getForEmployee();
        $context['total_weight'] = $repository->aggregateYearForEmployee($year, $employee);
        $context['objective_entry'] = $ObjectiveEntry;
        return $this->render('objective_entry/show.html.twig', $context);
    }

    #[Route("/{id}/edit", name: "objective_entry_edit", methods: "GET|POST")]
    public function edit(
      Request $request, 
      ObjectiveEntry $ObjectiveEntry,
      EntityManagerInterface $entityManager
    ): Response
    {
        $form = $this->createForm(ObjectiveEntryType::class, $ObjectiveEntry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $param = ['year' => $ObjectiveEntry->getyear(),'employee' => $ObjectiveEntry->getForEmployee()->getId()];

            return $this->redirectToRoute('mbo', $param);
        }

        return $this->render('objective_entry/edit.html.twig', [
            'objective_entry' => $ObjectiveEntry,
            'form' => $form->createView(),
        ]);
    }

    #[Route("/{id}", name: "objective_entry_delete", methods: "DELETE")]
    public function delete(
      Request $request, 
      ObjectiveEntry $ObjectiveEntry,
      EntityManagerInterface $entityManager
    ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ObjectiveEntry->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ObjectiveEntry);
            $entityManager->flush();
        }

        return $this->redirectToRoute('objective_entry_index');
    }
}
