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
use Doctrine\ORM\EntityRepository;

class RecordingSpeedType extends AbstractType
{

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
                    ->add('recSpeedFormat', 'entity', array(
                        'class' => 'ApplicationFrontBundle:Formats',
                        'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('f')
                                    ->orderBy('f.name', 'ASC');
                        },
                        'multiple' => true,
                        'mapped' => false,
                        'required'=> false
                    ))
            ;
        } else {
            $builder
                    ->add('name')
                    ->add('score')
                    ->add('recSpeedFormat')
//            ->add('organization')
            ;
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\Bundle\FrontBundle\Entity\RecordingSpeed'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'application_bundle_frontbundle_recordingspeed';
    }

}
