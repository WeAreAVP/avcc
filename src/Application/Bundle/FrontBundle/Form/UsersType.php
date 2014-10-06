<?php

namespace Application\Bundle\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraint\UserPassword as OldUserPassword;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class UsersType extends AbstractType
{

    public $roles;
    public $userRole;

    public function __construct($options = array())
    {
        $this->roles = $options['roles'];
        $this->userRole = isset($options['user_role']) ? $options['user_role'] : null;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (class_exists('Symfony\Component\Security\Core\Validator\Constraints\UserPassword')) {
            $constraint = new UserPassword();
        } else {
            $constraint = new OldUserPassword();
        }
        $data = array();
        $required = true;
        $role = 'ROLE_USER';
        if (count($this->userRole) > 0)
            $role = $this->userRole[0];

        $builder
        ->add('name')
        ->add('username')
        ->add('email')
        ->add('organizations')
        ->add('plainPassword', 'repeated', array(
            'type' => 'password',
            'required' => $required,
            'options' => array('translation_domain' => 'FOSUserBundle'),
            'first_options' => array('label' => '', 'attr' => array('class' => 'form-control', 'placeholder' => 'Password')),
            'second_options' => array('label' => ' ', 'attr' => array('class' => 'form-control', 'placeholder' => 'Confirm Password')),
            'invalid_message' => 'fos_user.password.mismatch',
        )
        )
        ->add('roles', 'choice', array(
            'choices' => $this->roles,
            'multiple' => false,
            'data' => $role
        ))
        ->addEventListener(
        FormEvents::PRE_SUBMIT, array($this, 'onPreSubmitData'));
    }

    /**
     * Add or remove field before validation.
     * Change value before binding.
     *
     * @param FormEvent $event
     */
    public function onPreSubmitData(FormEvent $event)
    {
        $form = $event->getForm();
        $userInfo = $event->getData();
        $form->remove('roles');
        $form->add('roles', 'choice', array(
            'choices' => $this->roles,
            'multiple' => true,
        ));
        $userInfo['roles'] = array($userInfo['roles']);
        $event->setData($userInfo);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\Bundle\FrontBundle\Entity\Users'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'application_bundle_frontbundle_users';
    }

}
