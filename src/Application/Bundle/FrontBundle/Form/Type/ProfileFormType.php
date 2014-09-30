<?php

// src/Application/Bundle/FrontBundle/Form/Type/ProfileFormType.php

namespace Application\Bundle\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileFormType extends AbstractType
{
   /**
    * Form overrided to show name field
    * 
    * @param \Symfony\Component\Form\FormBuilderInterface $builder
    * @param array $options
    */  
   public function buildForm(FormBuilderInterface $builder, array $options)
    {
       parent::buildForm($builder, $options);
        $builder
            ->add('name', 'text', array('label' => 'form.name', 'translation_domain' => 'FOSUserBundle'))
        ;
    }
    
   /**
    * Returns parent form type
    * 
    * @return string
    */
    public function getParent()
    {
        return 'fos_user_profile';
    }
    
    /**
     * Returns new form type 
     * 
     * @return string
     */
    public function getName()
    {
        return 'application_user_profile';
    }

   
}
