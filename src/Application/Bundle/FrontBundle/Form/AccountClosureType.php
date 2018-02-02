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

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HelpGuideType
 *
 * @author rimsha
 */
class AccountClosureType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('reason', 'choice', array(
                    'choices' => array(
                        '1' => 'AVCC is missing a key feature',
                        '2' => 'It didn\'t meet our expectations',
                        '3' => 'All of our work with AVCC is completed and we no longer need it',
                        '4' => 'We can not get the funding to continue paying for AVCC',
                        '5' => 'Other'
                    ),
                    'multiple' => false,
                    'expanded' => true,
                    'required' => true,
                ))
                ->add('explanation', 'textarea', array(
                    'required' => false)
                )
                ->add('otherService')
                ->add('feedback', 'textarea', array(
                    'required' => false)
        );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {

        $resolver->setDefaults(array(
            'data_class' => 'Application\Bundle\FrontBundle\Entity\AccountClosure',
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'application_bundle_frontbundle_accountclosure';
    }

}
