<?php

namespace Application\Bundle\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

class AudioRecordsType extends AbstractType {

    private $data;
    private $em;
    private $sphinxParam;

    public function __construct(EntityManager $em, $data = null) {
        $this->data = $data;
        $this->em = $em;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('record', new RecordsType($this->em, $this->data), array(
                    'data_class' => 'Application\Bundle\FrontBundle\Entity\Records'))
                ->add('mediaDuration', 'text', array('required' => false))
                ->add('diskDiameters', 'entity', array(
                    'class' => 'ApplicationFrontBundle:DiskDiameters',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->orderBy('u.order', 'ASC');
                    },
                    'empty_data' => '',
                    'required' => false,
                ))
                ->add('mediaDiameters', 'entity', array(
                    'class' => 'ApplicationFrontBundle:MediaDiameters',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->orderBy('u.order', 'ASC');
                    },
                    'empty_data' => '',
                    'required' => false
                ))
                ->add('bases')
                ->add('recordingSpeed')
                ->add('tapeThickness', 'entity', array(
                    'class' => 'ApplicationFrontBundle:TapeThickness',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->orderBy('u.order', 'ASC');
                    },
                    'empty_data' => '',
                    'required' => false
                ))
                ->add('slides', 'entity', array(
                    'class' => 'ApplicationFrontBundle:Slides',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->orderBy('u.order', 'ASC');
                    },
                    'empty_data' => '',
                    'required' => false,
                ))
                ->add('trackTypes', 'entity', array(
                    'class' => 'ApplicationFrontBundle:TrackTypes',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->orderBy('u.order', 'ASC');
                    },
                    'empty_data' => '',
                    'required' => false,))
                ->add('monoStereo', 'entity', array(
                    'class' => 'ApplicationFrontBundle:MonoStereo',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->orderBy('u.order', 'ASC');
                    },
                    'empty_data' => '',
                    'required' => false,
                ))
                ->add('noiceReduction', 'entity', array(
                    'class' => 'ApplicationFrontBundle:NoiceReduction',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->orderBy('u.order', 'ASC');
                    },
                    'empty_data' => '',
                    'required' => false,
        ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Application\Bundle\FrontBundle\Entity\AudioRecords',
			'intention' => 'audioRecords',
            'cascade_validation' => true 
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'application_bundle_frontbundle_audiorecords';
    }

}
