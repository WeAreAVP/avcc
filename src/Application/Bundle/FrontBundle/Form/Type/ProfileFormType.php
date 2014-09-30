<?php

// src/Application/Bundle/FrontBundle/Form/Type/ProfileFormType.php

namespace Application\Bundle\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileFormType extends AbstractType
{
   public function buildForm(FormBuilderInterface $builder, array $options)
    {
       parent::buildForm($builder, $options);
        $builder
            ->add('name', 'text', array('label' => 'form.name', 'translation_domain' => 'FOSUserBundle'))
        ;
    }

    public function getParent()
    {
        return 'fos_user_profile';
    }
    
    public function getName()
    {
        return 'application_user_profile';
    }

   
}
