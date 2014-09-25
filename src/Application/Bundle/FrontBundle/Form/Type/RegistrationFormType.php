<?php

// src/Application/Bundle/FrontBundle/Form/Type/RegistrationFormType.php

namespace Application\Bundle\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add your custom field
        $builder->add('name', 'text', array('label' => '', 'attr' => array('class' => 'form-control', 'placeholder' => 'Name')))
            ->add('username', 'text', array('label' => null, 'attr' => array('class' => 'form-control', 'placeholder' => 'Username')))
            ->add('email', 'email', array('label' => null, 'translation_domain' => 'FOSUserBundle', 'attr' => array('class' => 'form-control', 'placeholder' => 'Email')))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'form.password', 'attr' => array('class' => 'form-control', 'placeholder' => 'Password')),
                'second_options' => array('label' => 'form.password_confirmation', 'attr' => array('class' => 'form-control', 'placeholder' => 'Password')),
                'invalid_message' => 'fos_user.password.mismatch',
                )
        );
    }

    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'application_user_registration';
    }

}
