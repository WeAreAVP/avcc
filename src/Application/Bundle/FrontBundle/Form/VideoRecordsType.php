<?php

namespace Application\Bundle\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;

class VideoRecordsType extends AbstractType
{
    private $data;
    private $em;

    public function __construct(EntityManager $em, $data = null)
    {
        $this->data = $data;
        $this->em = $em;
    }
     /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('record', new RecordsType($this->em, $this->data), array(
                        'data_class' => 'Application\Bundle\FrontBundle\Entity\Records'))
            ->add('mediaDuration')
            ->add('cassetteSize')
            ->add('formatVersion')
            ->add('recordingSpeed')
            ->add('recordingStandard')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\Bundle\FrontBundle\Entity\VideoRecords'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'application_bundle_frontbundle_videorecords';
    }
}
