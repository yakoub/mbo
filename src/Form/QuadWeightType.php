<?php

namespace App\Form;

use App\Entity\QuadWeight;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

class QuadWeightType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first', Type\NumberType::class, ['required' => false])
            ->add('second', Type\NumberType::class, ['required' => false])
            ->add('third', Type\NumberType::class, ['required' => false])
            ->add('fourth', Type\NumberType::class, ['required' => false])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => QuadWeight::class,
        ]);
    }
}
