<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * My Controller
 *
 * @Route("/mycontroller")
 *
 */
class MyController extends Controller {

    public function setContainer(ContainerInterface $container = null) {
        parent::setContainer($container);
//        echo  $this->redirect($this->generateUrl('dashboard'));
        $this->validateUserAction();
//        exit;
    }

    private function validateUserAction() {
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        if (!in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles())) {
            $orgId = $user->getOrganizations()->getId();
            $activeRecord = $em->getRepository('ApplicationFrontBundle:TermsOfService')->findBy(array('status' => 1));
            if (count($activeRecord) > 0) {
                $orgTerms = $em->getRepository('ApplicationFrontBundle:OrganizationTerms')->findBy(array('organizationId' => $orgId, 'termsOfServiceId' => $activeRecord[0]->getId()));
                if (empty($orgTerms) || !$orgTerms[0]->getIsAccepted()) {
                    $session = $this->getRequest()->getSession();
                    $session->set('termsStatus', 0);
//                    echo $this->redirect($this->generateUrl('dashboard'));
//                    exit;
                }
            }
        }
    }

//    public function index() {
//        return $this->redirect($this->generateUrl('dashboard'));
//    }

}

