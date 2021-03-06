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
use Symfony\Component\Form\FormEvent;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManager;

class ProjectsType extends AbstractType {

    /**
     * Current logged in user data.
     *
     * @var string
     */
    public $user;
    static $DEFAULT_ROLE = 'ROLE_USER';
    static $DEFAULT_SUPER_ADMIN_ROLE = 'ROLE_SUPER_ADMIN';
    static $DEFAULT_ROLE_INDEX = 0;

    public function __construct($options = array()) {
        $this->user = $options['currentUser'];
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name')
                ->add('projectUsers', 'entity', array(
                    'by_reference' => false,
                    'class' => 'ApplicationFrontBundle:Users',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u');
                    },
                    'required' => false,
                    'multiple' => true,
//                    'mapped' => false,
                ))
                ->add('hidden_projectUsers', 'hidden', array(
                    'mapped' => false,
                    'required' => false,
                ))
                ->add('audioFilesize')
                ->add('videoFilesize')
                ->add('filmFilesize') 
                ->addEventListener(
                        FormEvents::POST_SET_DATA, array($this, 'onPreSetData'))
                ->addEventListener(
                        FormEvents::POST_SUBMIT, array($this, 'onPostSubmitData'));
    }

    public function onPreSetData(FormEvent $event) {
        $form = $event->getForm();

        $loggedInUserRole = $this->user->getRoles();

        if ($loggedInUserRole[self::$DEFAULT_ROLE_INDEX] == self::$DEFAULT_SUPER_ADMIN_ROLE) {
            $form->add('organization', 'entity', array(
                'class' => 'ApplicationFrontBundle:Organizations',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                                    ->where('u.status = 1');
                },
                'empty_data' => '',
                'required' => false,
            ));
        }
    }

    public function onPostSubmitData(FormEvent $event) {
        $projectInfo = $event->getData();

        $loggedInUserRole = $this->user->getRoles();
        if ($loggedInUserRole[self::$DEFAULT_ROLE_INDEX] !== self::$DEFAULT_SUPER_ADMIN_ROLE) {

            if ($this->user->getOrganizations() instanceof \Application\Bundle\FrontBundle\Entity\Organizations)
                $projectInfo->setOrganization($this->user->getOrganizations());
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Application\Bundle\FrontBundle\Entity\Projects'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'application_bundle_frontbundle_projects';
    }

}
