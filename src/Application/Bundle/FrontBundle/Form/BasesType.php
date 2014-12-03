<?php

namespace Application\Bundle\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class BasesType extends AbstractType
{

    public $formats;

    public function __construct()
    {
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $isNew = true;
        if ($options['data']->getId())
            $isNew = false;
        if ($isNew) {
            $builder
                    ->add('name')
                    ->add('score')
                    ->add('baseFormat', 'entity', array(
                        'class' => 'ApplicationFrontBundle:Formats',
                        'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('f')
                                    ->orderBy('f.name', 'ASC');
                        },
                        'multiple' => true,
                        'mapped' => false
                    ))
            ;
        } else {
            $builder
                    ->add('name')
                    ->add('score')
                    ->add('baseFormat')
            ;
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\Bundle\FrontBundle\Entity\Bases'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'application_bundle_frontbundle_bases';
    }

}
