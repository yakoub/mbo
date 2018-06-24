<?php

namespace App\Form;

use App\Entity\ObjectiveEntry;
// use App\Form\QuadWeightType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

class ObjectiveEntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('year');
        $choices = array(
            'Direct' => 'Direct',
            'Indirect' => 'Indirect',
            'Infrastructure' => 'Infrastructure'
        );
        $builder->add('Type', Type\ChoiceType::class, ['choices' => $choices]);
        $builder
            ->add('subject', Type\TextType::class, ['required' => true])
            ->add('description')
            ->add('weight', Type\NumberType::class, ['required' => false])
            ->add('quad_weight', QuadWeightType::class)
            ->add('achieve', Type\NumberType::class, ['required' => false, 'disabled' => true])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ObjectiveEntry::class,
            'validation_groups' => ['Default', 'single_update'],
        ]);
    }
}
