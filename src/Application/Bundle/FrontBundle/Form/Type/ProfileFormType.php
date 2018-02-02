<?php
/**
 * AVCC
 * 
 * @category AVCC
 * @package  Application
 * @author   Nouman Tayyab <nouman@weareavp.com>
 * @author   Rimsha Khalid <rimsha@weareavp.com>
 * @license  AGPLv3 http://www.gnu.org/licenses/agpl-3.0.txt
 * @copyright Audio Visual Preservation Solutions, Inc
 * @link     http://avcc.weareavp.com
 */
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
