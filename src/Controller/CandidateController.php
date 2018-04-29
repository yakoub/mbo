<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

use App\Entity\Person;
use App\Entity\ObjectiveManagement;
use App\Entity\ObjectiveReport;
use App\Repository\PersonRepository;
use App\Repository\ObjectiveEntryRepository;
use App\Repository\ObjectiveManagementRepository;

class CandidateController extends Controller
{
    public function candidate_tree(PersonRepository $personRepository, Person $person): Response
    {
        $people = $personRepository->getTree($person);
        return $this->render('candidate/tree.html.twig', ['people' => $people, 'head' => $person]);
    }

    public function candidates(PersonRepository $personRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $person = $this->getUser();
        $people = $personRepository->getTree($person);
        return $this->render('candidate/tree.html.twig', ['people' => $people, 'head' => $person]);
    }

    public function people_admin(PersonRepository $personRepository): Response
    {
        $people = $personRepository->getRoot();
        return $this->render('candidate/tree.html.twig', ['people' => $people, 'head' => false]);
    }
}
