<?php

namespace App\Form;

use App\Entity\ObjectiveManagement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObjectiveManagementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array(
            'Work in progress' => 'work_in_progress',
            'Under review' => 'under_review',
            'Require approval' => 'require_approval',
        );
        $builder
            ->add('status', ChoiceType::class, ['choices' => $choices])
            ->add('vp_weight')
            ->add('ceo_weight')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ObjectiveManagement::class,
        ]);
    }
}
