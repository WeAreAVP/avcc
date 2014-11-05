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

    public function __construct(EntityManager $em, $selectedOptions = null)
    {
        $this->selectedOptions = $selectedOptions;
        $this->em = $em;
//        print_r($this->selectedOptions['mediaTypeId']);exit;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
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
                ->add('project', 'choice', array(
                    'choices' => $this->selectedOptions['projectsArr'],
                    'data' => $this->selectedOptions['projectId'],
                    'attr' => array('disabled' => 'disabled'),
                ))
//		->add('user')
                ->add('mediaType', 'choice', array(
                    'choices' => $this->selectedOptions['mediaTypesArr'],
                    'data' => $this->selectedOptions['mediaTypeId'],
                    'attr' => array('disabled' => 'disabled'),
                ))
                ->add('reelDiameters')
                ->addEventListener(
                        FormEvents::POST_SET_DATA, array($this, 'onPreSetData'))
                ->addEventListener(
                        FormEvents::PRE_SUBMIT, array($this, 'onPreSubmitData'))
                ->addEventListener(
                        FormEvents::POST_SUBMIT, array($this, 'onPostSubmitData'))
            ;
    }

    public function onPreSetData(FormEvent $event)
    {
        $mediaType = $this->em->getRepository('ApplicationFrontBundle:MediaTypes')->findOneBy(array('id' => $this->selectedOptions['mediaTypeId']));
        $this->mediaTyp = $mediaType;
        
        $project = $this->em->getRepository('ApplicationFrontBundle:Projects')->findOneBy(array('id' => $this->selectedOptions['projectId']));
        $this->proj = $project;
//        print_r($this->proj);exit;
    }

    public function onPreSubmitData(FormEvent $event)
    {
//        $mediaType = $this->em->getRepository('ApplicationFrontBundle:MediaTypes')->findOneBy(array('id' => $this->selectedOptions['mediaTypeId']));
//        $this->mediaTyp = $mediaType;
//        
//        $project = $this->em->getRepository('ApplicationFrontBundle:Projects')->findOneBy(array('id' => $this->selectedOptions['projectId']));
//        $this->proj = $project;
//        print_r($this->selectedOptions['mediaTypeId']);exit;
    }

    public function onPostSubmitData(FormEvent $event)
    {
        $recordFields = $event->getData();
        print_r($this->mediaTyp);exit;
        if ($this->mediaTyp) {
            $recordFields->getRecords()->setMediaType($this->mediaTyp);
        }
        
        if ($this->proj) {
            $recordFields->setProject($this->proj);
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
