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
        $role = $report->getUserRole();
        switch ($report->management->getStatus()) {
            case 'work_in_progress':
                $disabled = ($role != 'manager');
                break;
            case 'under_review':
                $disabled = ($role != 'reviewer');
                break;
            case 'require_approval':
            case 'approved':
                $disabled = ($role != 'ceo');
                break;
        }
        $form->add('save', Type\SubmitType::class, ['label' => 'Save', 'disabled' => $disabled]);
        $form->add('status_prev', Type\SubmitType::class, ['label' => '<', 'disabled' => $disabled]);
        $form->add('status_next', Type\SubmitType::class, ['label' => '>', 'disabled' => $disabled]);
    }

    public static function getSubscribedEvents()
    {
        return [FormEvents::PRE_SET_DATA => 'onFormEventsPRESETDATA'];
    }
}
