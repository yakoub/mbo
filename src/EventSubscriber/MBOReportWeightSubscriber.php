<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type;

class MBOReportWeightSubscriber implements EventSubscriberInterface
{
    public function onFormEventsPRESETDATA(FormEvent $event)
    {
        $form = $event->getForm(); 
        $objective_report = $event->getData();
        if ($objective_report->status != 'work_in_progress') {
            $options = ['required' => false, 'disabled' => TRUE];
            $form->add('weight', Type\NumberType::class, $options);
        }
        if ($objective_report->status == 'under_review') {
            $options = ['required' => false, 'disabled' => FALSE];
            $form->add('achieve', Type\NumberType::class, $options);
        }
    }

    public static function getSubscribedEvents()
    {
        return [FormEvents::PRE_SET_DATA => 'onFormEventsPRESETDATA'];
    }
}
