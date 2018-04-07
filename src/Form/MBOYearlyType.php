<?php

namespace App\Form;

use App\Entity\MBOYearly;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MBOYearlyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('year')
            ->add('Type')
            ->add('subject')
            ->add('description')
            ->add('weight')
            ->add('status')
            ->add('score')
            ->add('by_manager')
            ->add('for_employee')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MBOYearly::class,
        ]);
    }
}
