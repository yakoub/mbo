<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type as Type;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    // currently not used 
        $builder
            ->add('_username')
            ->add('_password', Type\PasswordType::class)
            ->add('_remember_me', Type\CheckboxType::class, array(
                'label' => 'Stay logged in for one week',
                'data' => true,
            ))
            ->add('login', Type\SubmitType::class, array(
                'label' => 'Login',
            ))
        ;
    }
}
