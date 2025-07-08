<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ObjectiveEntryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/person")]
class PersonController extends AbstractController
{
    #[Route("/", name: "person_index", methods: "GET")]
    public function index(PersonRepository $personRepository): Response
    {
        $people = $personRepository->getRoot();
        return $this->render('person/index.html.twig', ['people' => $people]);
    }

    #[Route("/new", name: "person_new", methods: "GET|POST")]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $entityManager->getManager();
            $em->persist($person);
            $em->flush();

            return $this->redirectToRoute('person_index');
        }

        return $this->render('person/new.html.twig', [
            'person' => $person,
            'form' => $form->createView(),
        ]);
    }

    #[Route("/{id}", name: "person_show", methods: "GET")]
    public function show(Person $person, ObjectiveEntryRepository $objectiveRepository): Response
    {
        $years = $objectiveRepository->employeeYears($person);
        return $this->render('person/show.html.twig', ['person' => $person, 'years' => $years]);
    }

    #[Route("/{id}/edit", name: "person_edit", methods: "GET|POST")]
    public function edit(Request $request, Person $person, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('person_show', ['id' => $person->getId()]);
        }

        return $this->render('person/edit.html.twig', [
            'person' => $person,
            'form' => $form->createView(),
        ]);
    }

    #[Route("/{id}", name: "person_delete", methods: "DELETE")]
    public function delete(Request $request, Person $person, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$person->getId(), $request->request->get('_token'))) {
            $entityManager->remove($person);
            $entityManager->flush();
        }

        return $this->redirectToRoute('person_index');
    }
}
