<?php

namespace Application\Bundle\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraint\UserPassword as OldUserPassword;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Application\Bundle\FrontBundle\Form\DataTransformer\StringToArrayTransformer;

class UsersType extends AbstractType
{

	public $roles;
	

	public function __construct($options = array())
	{
		$this->roles = $options['roles'];
		
	}

	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		if (class_exists('Symfony\Component\Security\Core\Validator\Constraints\UserPassword'))
		{
			$constraint = new UserPassword();
		}
		else
		{
			// Symfony 2.1 support with the old constraint class
			$constraint = new OldUserPassword();
		}

		$transformer = new StringToArrayTransformer();
		$builder
		->add('name')
		->add('username')
		->add('email')
		->add('organizations')
		->add('plainPassword', 'repeated', array(
			'type' => 'password',
			'required' => false,
			'options' => array('translation_domain' => 'FOSUserBundle'),
			'first_options' => array('label' => '', 'attr' => array('class' => 'form-control', 'placeholder' => 'Password')),
			'second_options' => array('label' => ' ', 'attr' => array('class' => 'form-control', 'placeholder' => 'Confirm Password')),
			'invalid_message' => 'fos_user.password.mismatch',
		)
		)
		->add('roles', 'choice', array(
			'choices' => $this->roles,
			'multiple' => true,
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
