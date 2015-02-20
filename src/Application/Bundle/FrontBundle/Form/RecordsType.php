<?php

namespace Application\Bundle\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
//use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class RecordsType extends AbstractType {

    private $selectedOptions;
    private $em;
    private $mediaTyp;
    private $proj;
    private $user;

    public function __construct(EntityManager $em, $selectedOptions = null) {
        $this->selectedOptions = $selectedOptions;
        $this->em = $em;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        if ($this->selectedOptions['recordId']) {
            $builder
                    ->add('uniqueId')
                    ->add('location')
                    ->add('format', 'entity', array(
                        'class' => 'ApplicationFrontBundle:Formats',
                        'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('f')
                                    ->orderBy('f.name', 'ASC');
                        },
                        'required' => true,
                        'empty_data' => ''
                    ))
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
                    ->addEventListener(
                            FormEvents::PRE_SUBMIT, array($this, 'onPreSubmitData'))
                    ->addEventListener(
                            FormEvents::POST_SUBMIT, array($this, 'onPostSubmitData'))
            ;
        } else {
            $builder
                    ->add('uniqueId')
                    ->add('location')
                    ->add('format', 'entity', array(
                        'class' => 'ApplicationFrontBundle:Formats',
                        'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('f')
                                    ->orderBy('f.name', 'ASC');
                        }
                    ))
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
                    ->add('mediaType')
                    ->add('reelDiameters')
                    ->addEventListener(
                            FormEvents::PRE_SUBMIT, array($this, 'onPreSubmitData'))
                    ->addEventListener(
                            FormEvents::POST_SUBMIT, array($this, 'onPostSubmitData'))
            ;
        }
    }

    public function onPreSetData(FormEvent $event) {
        
    }

    public function onPreSubmitData(FormEvent $event) {
        $record = $event->getData();
        echo '<pre>';
        print_r($record);
//		$projectId = $record['projectHidden'];
        if (isset($record['mediaTypeHidden'])) {
            $mediaTypeId = $record['mediaTypeHidden'];
            $this->mediaTyp = $this->em->getRepository('ApplicationFrontBundle:MediaTypes')->findOneBy(array('id' => $mediaTypeId));
            $record['mediaType'] = $this->mediaTyp;
        }
        $userId = $record['userId'];

//		if ($projectId)
//		{
//			$this->proj = $this->em->getRepository('ApplicationFrontBundle:Projects')->findOneBy(array('id' => $projectId));
//		}
        $this->user = $this->em->getRepository('ApplicationFrontBundle:Users')->findOneBy(array('id' => $userId));
        echo $this->user->getOrganizations()->getId();
        echo '<br>' . $record['uniqueId'];
        $em = $this->getDoctrine()->getManager();
        $records = $em->getRepository('ApplicationFrontBundle:Records')->findOrganizationUniqueidRecords($this->user->getOrganizations()->getId(), $record['uniqueId']);

        echo count($records);

        if (count($records) == 0) {
            echo 'no error here';
        } else {
            $this->get('uniqueId')->addError(new FormError('the unique id must b unique'));
            echo 'error here';
        }
        exit;
    }

    public function onPostSubmitData(FormEvent $event) {
        echo '<br>hererererer........';
        $record = $event->getData();
        echo $record['uniqueId'];
        //   $this->get('uniqueId')->addError(new FormError('error message'));
        $records = $this->em->getRepository('ApplicationFrontBundle:Records')->findOrganizationUniqueidRecords($this->user->getUser()->getOrganizations()->getId(), $record['uniqueId']);
        if (count($records) == 0) {
            echo 'no error here';
        } else {
            echo 'error here';
        }
        exit;
        if ($record->getId()) {
            $record->setEditor($this->user);
            $record->setUpdatedOnValue();
            $record->setMediaType($this->mediaTyp);
        } else {
            $record->setUser($this->user);
        }
//		if ($this->proj)
//		{
//			$record->setProject($this->proj);
//		}
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Application\Bundle\FrontBundle\Entity\Records',
            'intention' => 'records'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'application_bundle_frontbundle_records';
    }

}
