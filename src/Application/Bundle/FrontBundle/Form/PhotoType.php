<?php

namespace Application\Bundle\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PhotoType extends AbstractType {

    private $id;

    public function __construct($recordId = null) {
        $this->id = $recordId;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('photo', 'file', array('attr' => array(
                        'accept' => 'image/*',
                        'multiple' => 'multiple'
            )))
                ->add("recordId", "hidden", array(
                    'data' => $this->id
        ));
    }

    public function getName() {
        return 'application_bundle_frontbundle_photo';  
    }

}
