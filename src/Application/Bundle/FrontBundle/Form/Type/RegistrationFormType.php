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
        $builder->add('name');
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
