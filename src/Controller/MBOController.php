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
use App\Form\YearType;
use App\Form\ObjectiveReportType;

class MBOController extends Controller
{
    public function by_year(ObjectiveEntryRepository $oe_repository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $person = $this->getUser();

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
        $employees = $oe_repository->getMyYear($year, $person);
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
        ObjectiveEntryRepository $oe_repository,
        ObjectiveManagementRepository $om_repository,
        Request $request
    ) {
        $objectives = $oe_repository->findBy(['for_employee' => $employee, 'year' => $year]);
        
        $report = new ObjectiveReport();
        $report->management = $this->getManagement($employee, $year, $om_repository);
        foreach ($objectives as $objective) {
            $property = 'objectives' . $objective->getType();
            $objective->status = $report->management->getStatus();
            $report->{$property}[] = $objective;
        }

        $form = $this->createForm(ObjectiveReportType::class, $report);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            if ($form->get('status_next')->isClicked()) {
                $report->management->statusNext();
            }
            elseif ($form->get('status_prev')->isClicked()) {
                $report->management->statusPrev();
            }
            $this->getDoctrine()->getManager()->flush();
            $param = ['year' => $year,'employee' => $employee->getId()];
            return $this->redirectToRoute('mbo', $param);
        }

        $context = array(
            'form' => $form->createView(),
            'employee' => $employee,
            'year' => $year,
            'status' => $report->management->getStatusWithLabel(),
        );

        return $this->render('mbo/mbo_report.html.twig', $context);
    }

    function getManagement($employee, $year, ObjectiveManagementRepository $repository) {
        $management = $repository->findOneBy(['for_employee' => $employee, 'year' => $year]);
        if (!$management) {
            $management = new ObjectiveManagement();
            $management->setStatus('work_in_progress');
            $management->setForEmployee($employee);
            $management->setByManager($this->getUser());
            $management->setYear($year);
            $this->getDoctrine()->getManager()->persist($management);
        }
        return $management;
    }
}
