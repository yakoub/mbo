<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MBOController extends Controller
{
    public function index(PersonRepository $personRepository, Person $person): Response
    {
        $people = $personRepository->getTree($person);
        return $this->render('mbo/tree.html.twig', ['people' => $people, 'head' => $person]);
    }

    public function root(PersonRepository $personRepository): Response
    {
        $people = $personRepository->getRoot();
        return $this->render('mbo/tree.html.twig', ['people' => $people, 'head' => false]);
    }
}
