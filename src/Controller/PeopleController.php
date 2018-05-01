<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
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
        return $this->render('people_admin/tree.html.twig', ['people' => $people, 'head' => $person]);
    }
}
