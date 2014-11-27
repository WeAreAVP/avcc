<?php

namespace Application\Bundle\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManager;

class RecordsType extends AbstractType
{

	private $selectedOptions;
	private $em;
	private $mediaTyp;
	private $proj;
	private $user;

	public function __construct(EntityManager $em, $selectedOptions = null)
	{
		$this->selectedOptions = $selectedOptions;
		$this->em = $em;
	}

	/**
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		if ($this->selectedOptions['projectId'])
		{
			$builder
			->add('uniqueId')
			->add('location')
			->add('format')
			->add('title')
			->add('collectionName')
			->add('description')
			->add('commercial')
			->add('contentDuration', 'text', array('required' => false))
			->add('creationDate', 'text', array('required' => false))
			->add('contentDate', 'text', array('required' => false))
			->add('isReview')
			->add('genreTerms')
			->add('contributor')
			->add('generation')
			->add('part')
			->add('copyrightRestrictions')
			->add('duplicatesDerivatives')
			->add('relatedMaterial')
			->add('conditionNote')
			->add('project')
			->add('project', 'choice', array(
				'choices' => $this->selectedOptions['projectsArr'],
				'data' => $this->selectedOptions['projectId'],
				'attr' => array('disabled' => 'disabled'),
			))
			->add('userId', 'hidden', array(
				'data' => $this->selectedOptions['userId'],
				'mapped' => false,
				'required' => false,
			))
			->add('mediaType', 'choice', array(
				'choices' => $this->selectedOptions['mediaTypesArr'],
				'data' => $this->selectedOptions['mediaTypeId'],
				'attr' => array('disabled' => 'disabled'),
				'mapped' => false,
			))
			->add('reelDiameters')
			->add('mediaTypeHidden', 'hidden', array(
				'data' => $this->selectedOptions['mediaTypeId'],
				'mapped' => false,
				'required' => false,
			))
			->add('projectHidden', 'hidden', array(
				'data' => $this->selectedOptions['projectId'],
				'mapped' => false,
				'required' => false,
			))
			->addEventListener(
			FormEvents::PRE_SUBMIT, array($this, 'onPreSubmitData'))
			->addEventListener(
			FormEvents::POST_SUBMIT, array($this, 'onPostSubmitData'))
			;
		}
		else
		{
			$builder
			->add('uniqueId')
			->add('location')
			->add('format')
			->add('title')
			->add('collectionName')
			->add('description')
			->add('commercial')
			->add('contentDuration', 'text', array('required' => false))
			->add('creationDate', 'text', array('required' => false))
			->add('contentDate', 'text', array('required' => false))
			->add('isReview')
			->add('genreTerms')
			->add('contributor')
			->add('generation')
			->add('part')
			->add('copyrightRestrictions')
			->add('duplicatesDerivatives')
			->add('relatedMaterial')
			->add('conditionNote')
			->add('project')
			->add('userId', 'hidden', array(
				'data' => $this->selectedOptions['userId'],
				'mapped' => false,
				'required' => false,
			))
			->add('mediaType', 'choice', array(
				'choices' => $this->selectedOptions['mediaTypesArr'],
				'data' => $this->selectedOptions['mediaTypeId'],
				'attr' => array('disabled' => 'disabled'),
				'mapped' => false,
			))
			->add('reelDiameters')
			->add('mediaTypeHidden', 'hidden', array(
				'data' => $this->selectedOptions['mediaTypeId'],
				'mapped' => false,
				'required' => false,
			))
			->add('projectHidden', 'hidden', array(
				'data' => null,
				'mapped' => false,
				'required' => false,
			))
			->addEventListener(
			FormEvents::PRE_SUBMIT, array($this, 'onPreSubmitData'))
			->addEventListener(
			FormEvents::POST_SUBMIT, array($this, 'onPostSubmitData'))
			;
		}
	}

	public function onPreSetData(FormEvent $event)
	{
		
	}

	public function onPreSubmitData(FormEvent $event)
	{
		$record = $event->getData();

		$projectId = $record['projectHidden'];
		$mediaTypeId = $record['mediaTypeHidden'];
		$userId = $record['userId'];
		$this->mediaTyp = $this->em->getRepository('ApplicationFrontBundle:MediaTypes')->findOneBy(array('id' => $mediaTypeId));
		if ($projectId)
		{
			$this->proj = $this->em->getRepository('ApplicationFrontBundle:Projects')->findOneBy(array('id' => $projectId));
		}
		$this->user = $this->em->getRepository('ApplicationFrontBundle:Users')->findOneBy(array('id' => $userId));
		$record['mediaType'] = $this->mediaTyp;
	}

	public function onPostSubmitData(FormEvent $event)
	{

		$record = $event->getData();
		if ($record->getId())
		{
			$record->setEditor($this->user);
			$record->setUpdatedOnValue();
		}
		else
			$record->setUser($this->user);
		$record->setMediaType($this->mediaTyp);
		if ($this->proj)
		{
			$record->setProject($this->proj);
		}
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
