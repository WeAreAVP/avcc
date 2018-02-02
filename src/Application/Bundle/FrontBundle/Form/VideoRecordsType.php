<?php
/**
 * AVCC
 * 
 * @category AVCC
 * @package  Application
 * @author   Nouman Tayyab <nouman@weareavp.com>
 * @author   Rimsha Khalid <rimsha@weareavp.com>
 * @license  AGPLv3 http://www.gnu.org/licenses/agpl-3.0.txt
 * @copyright Audio Visual Preservation Solutions, Inc
 * @link     http://avcc.weareavp.com
 */
namespace Application\Bundle\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class VideoRecordsType extends AbstractType {

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
                ->add('mediaDuration')
                ->add('cassetteSize', 'entity', array(
                    'class' => 'ApplicationFrontBundle:CassetteSizes',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                                ->orderBy('u.order', 'ASC');
                    },
                    'empty_data' => '',
                    'required' => false
                ))
                ->add('formatVersion')
                ->add('recordingSpeed')
                ->add('recordingStandard', 'entity', array(
                    'class' => 'ApplicationFrontBundle:RecordingStandards',
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
            'data_class' => 'Application\Bundle\FrontBundle\Entity\VideoRecords',
            'cascade_validation' => true
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'application_bundle_frontbundle_videorecords';
    }
 
}
