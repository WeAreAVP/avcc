<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\UserSettings;
use Application\Bundle\FrontBundle\Helper\DefaultFields as DefaultFields;

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

        $entities = $em->getRepository('ApplicationFrontBundle:UserSettings')->findOneBy(array('user' => $this->getUser()->getId()));

        if (!$entities) {
            $f_obj = new DefaultFields();
            $view_settings = $f_obj->getDefaultOrder();
            
        } else {
            $view_settings = $entities->getViewSetting();
        }
        $user_view_settings = json_decode($view_settings, true);
//        print_r($user_view_settings);
//        exit;
        return array(
            'entities' => $user_view_settings,
        );
    }

    /**
     * 
     *
     * @Route("/update", name="field_settings_update")
     * @Method("POST")
     * @Template()
     * @return array
     */
    public function updateAction(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $settings = $this->get('request')->request->get('settings');
            $user_setting = json_encode($settings);
            $em = $this->getDoctrine()->getManager();
            $user_entity = $em->getRepository('ApplicationFrontBundle:UserSettings')->findOneBy(array('user' => $this->getUser()->getId()));
            if ($user_entity) {
                $user_entity->setViewSetting($user_setting);
                $user_entity->setUpdatedOnValue(date('Y-m-d h:i:s'));
                $em->persist($user_entity);
                $em->flush();
            } else {
                $user_entity = new UserSettings();
                $user_entity->setUser($this->getUser());
                $user_entity->setViewSetting($user_setting);
                $user_entity->setCreatedOnValue(date('Y-m-d h:i:s'));
                $em->persist($user_entity);
                $em->flush();
            }
        }
        exit;
    }

}
