<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\UserSettings;

/**
 * UserSettings controller.
 *
 * @Route("/fieldsettings")
 */
class UserSettingsController extends Controller
{

    /**
     * 
     *
     * @Route("/", name="field_settings")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = '';
//        $entities = $em->getRepository('ApplicationFrontBundle:UserSettings')->findBy(array('user_id' => $this->getUser()->getId()));
        
        if (! $entities) {
            $entities = $this->container->getParameter('field_settings');
        } 
//        print_r($entities);exit;
        return array(
                'entities' => $entities,
            );
    }
    
}
