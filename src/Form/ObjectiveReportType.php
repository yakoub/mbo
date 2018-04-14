<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ObjectiveReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = ['entry_type' => ObjectiveWeightType::class];
        $builder->add('objectivesDirect', CollectionType::class, $options);
        $builder->add('objectivesIndirect', CollectionType::class, $options);
        $builder->add('objectivesInfrastructure', CollectionType::class, $options);

        $builder->add('management', ObjectiveManagementWeightType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => false,
        ]);
    }
}
