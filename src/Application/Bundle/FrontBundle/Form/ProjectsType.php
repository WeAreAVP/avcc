<?php

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
                    'empty_data' => '',
                    'required' => false
                            ))
                ->addEventListener(
                        FormEvents::POST_SET_DATA, array($this, 'onPreSetData'))
                ->addEventListener(
                        FormEvents::POST_SUBMIT, array($this, 'onPostSubmitData'));
    }

    public function onPreSetData(FormEvent $event) {
        $form = $event->getForm();

        $loggedInUserRole = $this->user->getRoles();

        if ($loggedInUserRole[self::$DEFAULT_ROLE_INDEX] == self::$DEFAULT_SUPER_ADMIN_ROLE) {
            $form->add('organization');
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
