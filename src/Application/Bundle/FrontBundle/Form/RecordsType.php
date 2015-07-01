<?php
/**
 * AVCC
 * 
 * @category AVCC
 * @package  Application
 * @author   Nouman Tayyab <nouman@avpreserve.com>
 * @author   Rimsha Khalid <rimsha@avpreserve.com>
 * @license  AGPLv3 http://www.gnu.org/licenses/agpl-3.0.txt
 * @copyright Audio Visual Preservation Solutions, Inc
 * @link     http://avcc.avpreserve.com
 */
namespace Application\Bundle\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
//            echo '<pre>';print_r($this->selectedOptions['mediaTypesArr'][$this->selectedOptions['mediaTypeId']]);exit;
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
                    ->add('collectionName', 'text', array('required' => false))
                    ->add('description')
                    ->add('commercial', 'entity', array(
                        'class' => 'ApplicationFrontBundle:Commercial',
                        'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('u')
                                    ->orderBy('u.order', 'ASC');
                        },
                        'empty_data' => '',
                        'required' => false
                    ))
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
                    ->add('generalNote')
                    ->add('project')
                    ->add('userId', 'hidden', array(
                        'data' => $this->selectedOptions['userId'],
                        'mapped' => false,
                        'required' => false,
                    ))
                    ->add('mediaType', 'choice', array(
                        'choices' => array($this->selectedOptions['mediaTypeId'] => $this->selectedOptions['mediaTypesArr'][$this->selectedOptions['mediaTypeId']]),
                        'data' => $this->selectedOptions['mediaTypeId'],
//                        'attr' => array('disabled' => 'disabled'),
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
                            FormEvents::POST_SUBMIT, array($this, 'onPostSubmitData'));
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
                    ->add('collectionName', 'text', array('required' => false))
                    ->add('description')
                    ->add('commercial', 'entity', array(
                        'class' => 'ApplicationFrontBundle:Commercial',
                        'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('u')
                                    ->orderBy('u.order', 'ASC');
                        },
                        'empty_data' => '',
                        'required' => false
                    ))
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
                    ->add('generalNote')
                    ->add('project')
                    ->add('userId', 'hidden', array(
                        'data' => $this->selectedOptions['userId'],
                        'mapped' => false
                    ))
                    ->add('mediaType', 'entity', array(
                        'class' => 'ApplicationFrontBundle:MediaTypes',
                        'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('u')
                                    ->orderBy('u.order', 'ASC');
                        },
                        'empty_value' => '',
                        'empty_data' => '',
                        'required' => false
                    ))
                    ->add('reelDiameters')
                    ->addEventListener(
                            FormEvents::PRE_SUBMIT, array($this, 'onPreSubmitData'))
                    ->addEventListener(
                            FormEvents::POST_SUBMIT, array($this, 'onPostSubmitData'));
        }
    }

    public function onPreSetData(FormEvent $event) {
        
    }

    public function onPreSubmitData(FormEvent $event) {
        $record = $event->getData();
        if (isset($record['mediaTypeHidden'])) {
            $mediaTypeId = $record['mediaTypeHidden'];
            $this->mediaTyp = $this->em->getRepository('ApplicationFrontBundle:MediaTypes')->findOneBy(array('id' => $mediaTypeId));
            $record['mediaType'] = $this->mediaTyp;
        }
        $userId = $record['userId'];
        $this->user = $this->em->getRepository('ApplicationFrontBundle:Users')->findOneBy(array('id' => $userId));
    }

    public function onPostSubmitData(FormEvent $event) {
        $record = $event->getData();

        if ($record->getId()) {
            $record->setEditor($this->user);
            $record->setUpdatedOnValue();
            $record->setMediaType($this->mediaTyp);
        } else {
            $record->setUser($this->user);
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Application\Bundle\FrontBundle\Entity\Records',
            'intention' => 'records',
            'cascade_validation' => true
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'application_bundle_frontbundle_records';
    }

}
