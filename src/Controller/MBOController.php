<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Person;
use App\Repository\PersonRepository;
use App\Repository\MBOYearlyRepository;
use App\Form\YearType;

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

    public function by_year(MBOYearlyRepository $mbo_repository, Request $request): Response
    {
        $year = 2018;
        $form = $this->createForm(YearType::class, ['year' => $year]);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $year = $form->get('year')->getData();
        }
        $mbo_yearlies = $mbo_repository->findBy(['year' => $year]);
        $context = array(
            'form' => $form->createView(),
            'year' => 2018,
            'mbo_yearlies' => $mbo_yearlies,
        );
        return $this->render('mbo/mbo_browse.html.twig', $context);
    }
}
