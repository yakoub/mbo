<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Person;
use App\Repository\PersonRepository;

class PeopleController extends Controller
{
    public function people_admin(PersonRepository $personRepository, ?Person $person = NULL)
        : Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($person) {
            $people = $personRepository->getTree($person);
        }
        else {
            $people = $personRepository->getRoot();
        }
        $context = ['people' => $people, 'head' => $person];
        return $this->render('people_admin/tree.html.twig', $context);
    }

    public function activate_children(Person $person) {
        $employees = $person->getEmployees();
        if ($employees->isEmpty()) {
            return $this->json($person->getId(), 204);
        }
        foreach ($employees as $employee) {
            $employee->setActive(true);
        }
        try {
            $this->getDoctrine()->getManager()->flush();
        }
        catch (\Exception $e) {
            return $this->json($person->getId(), 500);
        }
        return $this->json($person->getId(), 200);
    }
}
