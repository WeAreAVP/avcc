<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Application\Bundle\FrontBundle\Helper\DefaultFields;
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
        $fieldsObj = new DefaultFields();
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        if (!in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles())) {
            $session = $this->getRequest()->getSession();
            $orgId = $user->getOrganizations()->getId();
            $activeRecord = $em->getRepository('ApplicationFrontBundle:TermsOfService')->findBy(array('status' => 1));
            if (count($activeRecord) > 0) {
                $orgTerms = $em->getRepository('ApplicationFrontBundle:OrganizationTerms')->findBy(array('organizationId' => $orgId, 'termsOfServiceId' => $activeRecord[0]->getId()));
                if (empty($orgTerms) || !$orgTerms[0]->getIsAccepted()) {
                    $session->set('termsStatus', 0);
                }
            }
            $paidOrg = $fieldsObj->paidOrganizations($orgId);
            if ($paidOrg && $this->container->getParameter("enable_stripe")) {
                $free = 2500;
                $freePlan = $em->getRepository('ApplicationFrontBundle:Plans')->findOneBy(array("amount" => 0));
                if (!empty($freePlan)) {
                    $free = $freePlan->getRecords();
                }

                $organization = $em->getRepository('ApplicationFrontBundle:Organizations')->find($orgId);
                $creator = $organization->getUsersCreated();
                $customerId = $creator->getStripeCustomerId();
                $org_records = $em->getRepository('ApplicationFrontBundle:Records')->countOrganizationRecords($orgId);
                $counter = $org_records['total'];
                if (($counter > $free && ($organization->getIsPaid() == 0 || ($customerId == NULL || $customerId == ""))) || $organization->getCancelSubscription() == 1) {
                    $session->set('limitExceed', 0);
                }
            }
        }
    }

    

}
