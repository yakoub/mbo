<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ObjectiveReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $collection_options = ['entry_type' => ObjectiveWeightType::class];
        $builder->add('objectivesDirect', CollectionType::class, $collection_options);
        $builder->add('objectivesIndirect', CollectionType::class, $collection_options);
        $builder->add('objectivesInfrastructure', CollectionType::class, $collection_options);

        $builder->add('management', ObjectiveManagementWeightType::class);

        $builder->add('save', SubmitType::class, ['label' => 'Save']);
        $builder->add('status_prev', SubmitType::class, ['label' => '<']);
        $builder->add('status_next', SubmitType::class, ['label' => '>']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => ['report'],
        ]);
    }
}
