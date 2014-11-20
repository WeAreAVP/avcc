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
     * User settings
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
            $fObj = new DefaultFields();
            $viewSettings = $fObj->getDefaultOrder();
        } else {
            $viewSettings = $entities->getViewSetting();
        }
        $userViewSettings = json_decode($viewSettings, true);

        return array(
            'entities' => $userViewSettings,
        );
    }

    /**
     * Update user settings
     *
     * @param Request $request
     *
     * @Route("/update", name="field_settings_update")
     * @Method("POST")
     * @Template()
     * @return array
     */
    public function updateAction(Request $request)
    {
        $success = FALSE;
        $reload = FALSE;
        if ($request->getMethod() == 'POST') {
            $settings = $this->get('request')->request->get('settings');
            $userSetting = json_encode($settings);
            $em = $this->getDoctrine()->getManager();
            $userEntity = $em->getRepository('ApplicationFrontBundle:UserSettings')->findOneBy(array('user' => $this->getUser()->getId()));
            if ($userEntity) {
                $userEntity->setViewSetting($userSetting);
                $userEntity->setUpdatedOnValue(date('Y-m-d h:i:s'));
                $em->persist($userEntity);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'Settings updated succesfully.');
                $success = TRUE;
                $reload = TRUE;
            } else {
                $userEntity = new UserSettings();
                $userEntity->setUser($this->getUser());
                $userEntity->setViewSetting($userSetting);
                $userEntity->setCreatedOnValue(date('Y-m-d h:i:s'));
                $em->persist($userEntity);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'Settings added succesfully.');
                $success = TRUE;
                $reload = TRUE;
            }
        }
        echo json_encode(array('success' => $success, 'reload' => $reload));
        exit;
    }

}
