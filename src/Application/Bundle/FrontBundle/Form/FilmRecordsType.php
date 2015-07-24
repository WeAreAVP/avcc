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
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class FilmRecordsType extends AbstractType {

    private $data;
    private $em;

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
                ->add('footage')
                ->add('mediaDiameter')
                ->add('shrinkage')
                ->add('printType', 'entity', array(
                    'class' => 'ApplicationFrontBundle:PrintTypes',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->orderBy('u.order', 'ASC');
                    },
                    'empty_data' => '',
                    'required' => false
                ))
                ->add('reelCore', 'entity', array(
                    'class' => 'ApplicationFrontBundle:ReelCore',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->orderBy('u.order', 'ASC');
                    },
                    'empty_data' => '',
                    'required' => false
                ))
                ->add('bases')
                ->add('colors', 'entity', array(
                    'class' => 'ApplicationFrontBundle:Colors',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->orderBy('u.order', 'ASC');
                    },
                    'empty_data' => '',
                    'required' => false
                ))
                ->add('sound', 'entity', array(
                    'class' => 'ApplicationFrontBundle:Sounds',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->orderBy('u.order', 'ASC');
                    },
                    'empty_data' => '',
                    'required' => false
                ))
                ->add('edgeCodeYear')
                ->add('frameRate', 'entity', array(
                    'class' => 'ApplicationFrontBundle:FrameRates',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->orderBy('u.order', 'ASC');
                    },
                    'empty_data' => '',
                    'required' => false
                ))
                ->add('acidDetectionStrip', 'entity', array(
                    'class' => 'ApplicationFrontBundle:AcidDetectionStrips',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->orderBy('u.order', 'ASC');
                    },
                    'empty_data' => '',
                    'required' => false
                ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Application\Bundle\FrontBundle\Entity\FilmRecords',
            'cascade_validation' => true
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'application_bundle_frontbundle_filmrecords';
    }

}
