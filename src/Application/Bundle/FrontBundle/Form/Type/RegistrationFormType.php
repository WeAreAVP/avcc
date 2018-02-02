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
// src/Application/Bundle/FrontBundle/Form/Type/RegistrationFormType.php

namespace Application\Bundle\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Application\Bundle\FrontBundle\Form\OrganizationsType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class RegistrationFormType extends AbstractType {

    /**
     * registration form fields modified to apply bootstrap and new field added
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array                                        $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        // add your custom field
        $builder->add('name', 'text', array('label' => '', 'attr' => array('class' => 'form-control', 'placeholder' => 'Name')))
                ->add('username', 'text', array('label' => '', 'attr' => array('class' => 'form-control', 'placeholder' => 'Username')))
                ->add('email', 'email', array('label' => '', 'translation_domain' => 'FOSUserBundle', 'attr' => array('class' => 'form-control', 'placeholder' => 'Email')))
                ->add('plainPassword', 'repeated', array(
                    'type' => 'password',
                    'options' => array('translation_domain' => 'FOSUserBundle'),
                    'first_options' => array('label' => '', 'attr' => array('class' => 'form-control', 'placeholder' => 'Password'), 'label_attr' => array('style' => 'visibility:hidden;display:none')),
                    'second_options' => array('label' => ' ', 'attr' => array('class' => 'form-control', 'placeholder' => 'Confirm Password'), 'label_attr' => array('style' => 'visibility:hidden;display:none')),
                    'invalid_message' => 'fos_user.password.mismatch',
                        )
                )->add('organizations', new OrganizationsType(array('from_registration' => true)), array(
            'data_class' => 'Application\Bundle\FrontBundle\Entity\Organizations'
        ))->add('termStatus', 'checkbox', array('label' => '', 'mapped' => false, 'required' => true))
//                ->addEventListener(
//                FormEvents::POST_SUBMIT, array($this, 'onPostSubmitData'));
        ;
    }

    public function onPostSubmitData(FormEvent $event) {
        $formData = $event->getData();
        if ($formData->getOrganizations() instanceof \Application\Bundle\FrontBundle\Entity\Organizations) {
            
        }
    }

    /**
     * Returns parent form type
     *
     * @return string
     */
    public function getParent() {
        return 'fos_user_registration';
    }

    /**
     * Returns new form type
     *
     * @return string
     */
    public function getName() {
        return 'application_user_registration';
    }

}
