<?php

namespace App\Form;

use App\Entity\ObjectiveEntry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
        $builder->add('Type', ChoiceType::class, ['choices' => $choices]);
        $builder
            ->add('subject')
            ->add('description')
            ->add('weight', Type\NumberType::class, ['required' => false])
            ->add('achieve', Type\NumberType::class, ['required' => false])
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
