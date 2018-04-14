<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

use App\Entity\Person;
use App\Repository\PersonRepository;
use App\Repository\ObjectiveEntryRepository;
use App\Form\YearType;
use App\Form\ObjectiveReportType;

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

    public function by_year(ObjectiveEntryRepository $oe_repository, Request $request): Response
    {
        if ($request->cookies->has('mbo_year')) {
            $year = $request->cookies->get('mbo_year');
        }
        else {
            $year = intval(date('Y'));
        }
        $form = $this->createForm(YearType::class, ['year' => $year]);
        $form->handleRequest($request);
        $set_cookie = false;
        if ($form->isSubmitted() and $form->isValid()) {
            $set_cookie = $year = $form->get('year')->getData();
        }
        $employees = $oe_repository->getMyYear($year);
        $context = array(
            'form' => $form->createView(),
            'year' => $year,
            'employees' => $employees,
        );
        $response = $this->render('mbo/mbo_browse.html.twig', $context);
        if ($set_cookie) {
            $expire = new \DateTime();
            $expire->add(new \DateInterval('P1Y'));
            $response->headers->setCookie(new Cookie('mbo_year', $set_cookie, $expire, '/'));
        }
        return $response;
    }

    public function mbo(
        $year, 
        Person $employee, 
        ObjectiveEntryRepository $or_repository,
        Request $request
    ) {
        $objectives = $or_repository->findBy(['for_employee' => $employee, 'year' => $year]);
        
        $defautls = [];
        foreach ($objectives as $objective) {
            $variable = 'objectives' . $objective->getType();
            if (!isset($defaults[$variable])) {
                $defaults[$variable] = [];
            }
            $defaults[$variable][] = $objective;
        }

        $form = $this->createForm(ObjectiveReportType::class, $defaults);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
        }
        $context = array(
            'form' => $form->createView(),
            'employee' => $employee,
            'year' => $year,
        );

        return $this->render('mbo/mbo_report.html.twig', $context);
    }
}
