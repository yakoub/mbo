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
        PersonRepository $p_repository,
        ObjectiveEntryRepository $oe_repository,
        ObjectiveManagementRepository $om_repository,
        \Swift_Mailer $mailer,
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
                $this->notify($report->management, $p_repository, $mailer);
            }
            elseif ($form->get('status_prev')->isClicked()) {
                $report->management->statusPrev();
                $this->notify($report->management, $p_repository, $mailer);
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

    function notify($management, PersonRepository $p_repository, $mailer) {
        $manager_mail = $management->getByManager()->getEmail();
        $reviewer_mail = $management->getByManager()->getReviewer()->getEmail();
        $ceo = $p_repository->findOneBy(['name' => 'guy_s']);
        $ceo_mail = $ceo ? $ceo->getEmail() : $reviewer_mail;
        $config = [];
        switch($management->getStatus()) {
            case 'work_in_progress':
                $config['subject'] = 'mbo requires more work';
                $config['template'] = 'emails/mbo-in-progress';
                $config['from'] = $reviewer_mail;
                $config['to'] = $manager_mail; 
                break;
            case 'under_review':
                $config['subject'] = 'mbo requires review';
                $config['template'] ='emails/mbo-review';
                $config['from'] = $manager_mail;
                $config['to'] = $reviewer_mail;
                break;
            case 'require_approval':
                $config['subject'] = 'mbo ready for approval';
                $config['template'] ='emails/mbo-require-approval';
                $config['from'] = $reviewer_mail;
                $config['to'] = $ceo_mail;
                $config['cc'] = [$manager_mail, $reviewer_mail];
                break;
            case 'approved':
                $config['subject'] = 'mbo approved';
                $config['template'] ='emails/mbo-approved';
                $config['from'] = $ceo_mail;
                $config['to'] = $manager_mail;
                $config['cc'] = $reviewer_mail;
                break;
        }
        $this->swift($management, $config, $mailer);
    }

    function swift($management, $config, $mailer) {
        $message = new \Swift_Message($config['subject']);
        $message->setFrom($config['from']);
        $message->setTo($config['to']);
        if (isset($config['cc'])) {
            $message->setCc($config['cc']);
        }
        $content = $this->renderView($config['template'] . '.html.twig', ['management' => $management]);
        $message->setBody($content, 'text/html');

        $mailer->send($message);
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
