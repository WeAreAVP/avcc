<?php

namespace Application\Bundle\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RecordsType extends AbstractType
{

	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('uniqueId')
		->add('location')
		->add('format')
		->add('title')
		->add('collectionName')
		->add('description')
		->add('commercial')
		->add('contentDuration')
		->add('creationDate')
		->add('contentDate')
		->add('isReview')
		->add('genreTerms')
		->add('contributor')
		->add('generation')
		->add('part')
		->add('copyrightRestrictions')
		->add('duplicatesDerivatives')
		->add('relatedMaterial')
		->add('conditionNote')
//		->add('project')
//		->add('user')
//		->add('mediaType')
		
		
		
		->add('reelDiameters');
	}

	/**
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Application\Bundle\FrontBundle\Entity\Records'
		));
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'application_bundle_frontbundle_records';
	}

}
