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

    /**
     * List of roles.
     *
     * @var array
     */
    public $roles;

    /**
     * Role of user that is being added or edited.
     *
     * @var string
     */
    public $userRole;

    /**
     * Current logged in user data.
     *
     * @var string
     */
    public $user;

    /**
     * Role value that was submitted in form.
     *
     * @var string
     */
    public $formRole;
    static $DEFAULT_ROLE = 'ROLE_USER';
    static $DEFAULT_SUPER_ADMIN_ROLE = 'ROLE_SUPER_ADMIN';
    static $DEFAULT_ROLE_INDEX = 0;

    public function __construct($options = array())
    {
        $this->roles = $options['roles'];
        $this->userRole = isset($options['userRole']) ? $options['userRole'] : null;
        $this->user = $options['currentUser'];
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
        $isRequired = true;
        if ($options['data']->getId())
            $isRequired = false;
        $builder
                ->add('name')
                ->add('username')
                ->add('email')
                ->add('plainPassword', 'repeated', array(
                    'type' => 'password',
                    'required' => $isRequired,
                    'options' => array('translation_domain' => 'FOSUserBundle'),
                    'first_options' => array('label' => '', 'attr' => array('class' => 'form-control', 'placeholder' => 'Password')),
                    'second_options' => array('label' => ' ', 'attr' => array('class' => 'form-control', 'placeholder' => 'Confirm Password')),
                    'invalid_message' => 'fos_user.password.mismatch',
                        )
                )->add('userProjects')
                ->addEventListener(
                        FormEvents::POST_SET_DATA, array($this, 'onPreSetData'))
                ->addEventListener(
                        FormEvents::PRE_SUBMIT, array($this, 'onPreSubmitData'))
                ->addEventListener(
                        FormEvents::POST_SUBMIT, array($this, 'onPostSubmitData'));
    }

    /**
     * Initialize form values based on roles.
     *
     * @param FormEvent $event
     */
    public function onPreSetData(FormEvent $event)
    {
        $userInfo = $event->getData();
        $form = $event->getForm();
        $role = self::$DEFAULT_ROLE;

        if (count($this->userRole) > 0)
            $role = $this->userRole[self::$DEFAULT_ROLE_INDEX];
        $loggedInUserRole = $this->user->getRoles();

        if ($loggedInUserRole[self::$DEFAULT_ROLE_INDEX] == self::$DEFAULT_SUPER_ADMIN_ROLE) {
            $form->add('organizations');
        } else {
            unset($this->roles[self::$DEFAULT_SUPER_ADMIN_ROLE]);
        }
        
        $array = $this->user->getRoles();
        
        foreach($this->roles as $key => $value){
            if($key == $array[0]){
                $newRoles[$key] = $value;
                break;
            }else{
                $newRoles[$key] = $value;
            }
        }
        $form->add('roles', 'choice', array(
            'choices' => $newRoles,
            'multiple' => false,
            'mapped' => false,
            'data' => $role
        ));
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
        $this->formRole = $userInfo['roles'];
    }

    public function onPostSubmitData(FormEvent $event)
    {
        $userInfo = $event->getData();

        $loggedInUserRole = $this->user->getRoles();
        if ($loggedInUserRole[self::$DEFAULT_ROLE_INDEX] !== self::$DEFAULT_SUPER_ADMIN_ROLE) {

            if ($this->user->getOrganizations() instanceof \Application\Bundle\FrontBundle\Entity\Organizations)
                $userInfo->setOrganizations($this->user->getOrganizations());
        }

        $userInfo->setRoles(array($this->formRole));
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
