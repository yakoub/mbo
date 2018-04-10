<?php

namespace App\Form;

use App\Entity\ObjectiveEntry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObjectiveEntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('year')
            ->add('Type')
            ->add('subject')
            ->add('description')
            ->add('weight')
            ->add('achieve')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ObjectiveEntry::class,
        ]);
    }
}
