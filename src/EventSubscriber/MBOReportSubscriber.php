<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type;

class MBOReportSubscriber implements EventSubscriberInterface
{
    public function onFormEventsPRESETDATA(FormEvent $event)
    {
        $form = $event->getForm(); 
        $report = $event->getData();
        switch ($report->status) {
            case 'work_in_progress':
                break;
            case 'under_review':
                break;
            case 'require_approval':
            case 'approved':
                break;
        }
        $form->add('save', SubmitType::class, ['label' => 'Save']);
        $form->add('status_prev', SubmitType::class, ['label' => '<']);
        $form->add('status_next', SubmitType::class, ['label' => '>']);
    }

    public static function getSubscribedEvents()
    {
        return [FormEvents::PRE_SET_DATA => 'onFormEventsPRESETDATA'];
    }
}
