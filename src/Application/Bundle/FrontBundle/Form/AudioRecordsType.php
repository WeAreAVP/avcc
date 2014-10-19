<?php

namespace Application\Bundle\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Application\Bundle\FrontBundle\Form\RecordsType;

class AudioRecordsType extends AbstractType
{

	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('record', new RecordsType(), array(
			'data_class' => 'Application\Bundle\FrontBundle\Entity\Records'))
		->add('mediaDuration')
		->add('diskDiameters')
		->add('mediaDiameters')
		->add('bases')
		->add('recordingSpeed')
		->add('tapeThickness')
		->add('slides')
		->add('trackTypes')
		->add('monoStereo')
		->add('noiceReduction')
//		->add('nextStep', 'submit')
//    ->add('previousStep', 'submit')

		;
	}

	/**
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Application\Bundle\FrontBundle\Entity\AudioRecords'
		));
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'application_bundle_frontbundle_audiorecords';
	}

}
