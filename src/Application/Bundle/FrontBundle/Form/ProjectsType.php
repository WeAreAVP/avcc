<?php

namespace Application\Bundle\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectsType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')            
            ->addEventListener(
                        FormEvents::POST_SET_DATA, array($this, 'onPreSetData'))     
            ->addEventListener(
                        FormEvents::POST_SUBMIT, array($this, 'onPostSubmitData'));    
        ;
    }
    
        public function onPreSetData(FormEvent $event)
    {
        $form = $event->getForm();
       
        $loggedInUserRole = $this->user->getRoles();

        if ($loggedInUserRole[self::$DEFAULT_ROLE_INDEX] == self::$DEFAULT_SUPER_ADMIN_ROLE) {
            $form->add('organizations');
        } 
    }
    
    public function onPostSubmitData(FormEvent $event)
    {
        $projectInfo = $event->getData();

        $loggedInUserRole = $this->user->getRoles();
        if ($loggedInUserRole[self::$DEFAULT_ROLE_INDEX] !== self::$DEFAULT_SUPER_ADMIN_ROLE) {

            if ($this->user->getOrganizations() instanceof \Application\Bundle\FrontBundle\Entity\Organizations)
                $projectInfo->setOrganizations($this->user->getOrganizations());
        }
    }
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\Bundle\FrontBundle\Entity\Projects'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'application_bundle_frontbundle_projects';
    }
}
