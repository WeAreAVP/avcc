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
 * Description of PlansType
 *
 * @author rimsha
 */
class PlansType extends AbstractType {

    private $id = 0;

    public function __construct($data = null) {
        $this->id = $data;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        if ($this->id > 0) {
            $builder
                    ->add('name')
                    ->add('planId')
                    ->add('amount')
                    ->add('records')
                    ->add('description')
            ;
        } else {
            $builder
                    ->add('name')
                    ->add('planId')
                    ->add('amount')
                    ->add('records')
                    ->add('description')
                    ->add('planInterval', 'choice', array('choices' => array(
                            'month' => 'Month',
                            'year' => 'Year'
            )));
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {

        $resolver->setDefaults(array(
            'data_class' => 'Application\Bundle\FrontBundle\Entity\Plans',
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'application_bundle_frontbundle_plan';
    }

}
