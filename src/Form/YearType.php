<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

class YearType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('year', Type\IntegerType::class, array(
                'required' => true,
            ))
            ->add('select', Type\SubmitType::class, array(
                'label' => 'select',
            ));
        ;
    }
}
