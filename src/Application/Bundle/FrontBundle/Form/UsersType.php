<?php

namespace Application\Bundle\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraint\UserPassword as OldUserPassword;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class UsersType extends AbstractType
{

    public $roles;
    public $user_role;

    public function __construct($options = array())
    {
        $this->roles = $options['roles'];
        $this->user_role = isset($options['user_role']) ? $options['user_role'] : null;
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
            // Symfony 2.1 support with the old constraint class
            $constraint = new OldUserPassword();
        }
        $data = array();
        $required = true;
        if (is_array($this->user_role) && !$this->user_role) {
            $data = array('ROLE_USER');
            $required = FALSE;
        } else {
            $data = $this->user_role;
        }
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
                    'multiple' => true,
                    'data' => $data
        ));
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
