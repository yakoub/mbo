<?php

namespace App\Form;

use App\Entity\ObjectiveEntry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;
use App\EventSubscriber\MBOReportWeightSubscriber;

class ObjectiveWeightType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('weight', Type\NumberType::class, ['required' => false])
            ->add('achieve', Type\NumberType::class, ['required' => false, 'disabled' => TRUE])
        ;

        $builder->addEventSubscriber(new MBOReportWeightSubscriber());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ObjectiveEntry::class,
        ]);
    }
}
