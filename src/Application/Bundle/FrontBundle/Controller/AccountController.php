<?php

/**
 * AVCC
 * 
 * @category AVCC
 * @package  Application
 * @author   Nouman Tayyab <nouman@avpreserve.com>
 * @author   Rimsha Khalid <rimsha@avpreserve.com>
 * @license  AGPLv3 http://www.gnu.org/licenses/agpl-3.0.txt
 * @copyright Audio Visual Preservation Solutions, Inc
 * @link     http://avcc.avpreserve.com
 */

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Helper\StripeHelper;
use Application\Bundle\FrontBundle\SphinxSearch\SphinxSearch;
use Symfony\Component\Form\FormError;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Application\Bundle\FrontBundle\Controller\MyController;
use Application\Bundle\FrontBundle\Entity\AccountClosure;
use Application\Bundle\FrontBundle\Form\AccountClosureType;

/**
 * AccountController controller.
 *
 * @Route("/account")
 */
class AccountController extends MyController {

    /**
     * Lists all AudioRecords entities.
     *
     * @Route("/", name="account")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function indexAction() {
        $helper = new StripeHelper($this->container);
        $em = $this->getDoctrine()->getManager();
        $id = $this->getUser()->getOrganizations()->getId();
        $organization = $em->getRepository('ApplicationFrontBundle:Organizations')->find($id);
        $data["plan"] = "";
        $org_records = $em->getRepository('ApplicationFrontBundle:Records')->countOrganizationRecords($id);
        $data["org_total"] = $org_records['total'];
        $creator = $organization->getUsersCreated();
        $upgrade = FALSE;
        if ($creator->getId() == $this->getUser()->getId()) {
            $upgrade = TRUE;
        }
        $plan_id = $creator->getStripePlanId();
        $data["card"] = '';
        $data["customer_email"] = '';
        $data["plan"] = '';
        $data["history"] = $this->getBillingHistory($id);
        if ($plan_id != NULL && $plan_id != "") {
            $data["recipients"] = $creator->getReceiptRecipients();
            $plan = $em->getRepository('ApplicationFrontBundle:Plans')->findBy(array("planId" => $plan_id));
            $customer = $helper->getCustomer($creator->getStripeCustomerId());
            $data["card"] = $customer["sources"]["data"][0];
            $data["customer_email"] = $customer["email"];
            $data["plan"] = $plan;
            $data["history"] = $this->getBillingHistory($id);
        }

        return array(
            'entities' => $data,
            'upgrade' => $upgrade
        );
    }

    /**
     * Subscribe
     *
     * @param Request $request
     *
     * @Route("/update_recipients", name="update_recipients") 
     * @Method("POST")
     * @Template()
     * @return array
     */
    public function updateRecipientsAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $org_id = $request->request->get('org_id');
        $emails = $request->request->get('recipients');
        $organization = $em->getRepository('ApplicationFrontBundle:Organizations')->find($org_id);
        $entity = $organization->getUsersCreated();
        $entity->setReceiptRecipients(trim($emails));
        $em->persist($entity);
        $em->flush($entity);
        return $this->redirect($this->generateUrl('account'));
    }

    public function getBillingHistory($id) {
        $em = $this->getDoctrine()->getManager();
        $_history = array();
        $entities = $em->getRepository('ApplicationFrontBundle:MonthlyChargeReport')->findBy(array('organizationId' => $id), array('id' => 'DESC'), 3);
//        
        foreach ($entities as $entity) {
//            echo "<pre>";
//            print_r($entity->getPlans()->getId());
//            exit;
            $data[] = $entity->getCreatedOn()->format('d M Y');
//            if ($entity->getPlans()) {
            $data[] = "Plan: " . $entity->getPlans()->getName();
            $data[] = "Records: " . $entity->getPlans()->getRecords();
            $data[] = "Amount: $" . $entity->getChargeAmount();
//            } else {
//                $data[] = "Plan: ";
//                $data[] = "Records: ";
//                $data[] = "Amount: $" . $entity->getChargeAmount();
//            }
            $_history[] = implode(", ", $data);
            unset($data);
        }
        return implode("<br/>", $_history);
    }

    /**
     * Creates a new AcidDetectionStrips entity.
     *
     * @param Request $request
     *
     * @Route("/", name="account_closure")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:Account:new.html.twig")
     * @return array
     */
    public function createAction(Request $request) {
        $entity = new AccountClosure();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $helper = new StripeHelper($this->container);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $organization = $em->getRepository('ApplicationFrontBundle:Organizations')->find($this->getUser()->getOrganizations()->getId());
            // deactiva account + store org
            $organization->setStatus(0);
            $creator = $organization->getUsersCreated();
            if (in_array("ROLE_ADMIN", $creator->getRoles())) {
                $cus_id = $creator->getStripeCustomerId();
                if ($cus_id != NULL && $cus_id != "") {
                    $helper->deleteCustomer($cus_id);
                    $creator->setStripePlanId(NULL);
                    $creator->setStripeSubscribeId(NULL);
                }
            }
            $users = $em->getRepository('ApplicationFrontBundle:Users')->findBy(array('organizations' => $organization->getId()));

            foreach ($users as $user) {
                $_user = $em->getRepository('ApplicationFrontBundle:Users')->find($user->getId());
                $_user->setEnabled(0);
            }

            $projects = $em->getRepository('ApplicationFrontBundle:Projects')->findBy(array('organization' => $organization->getId()));
            foreach ($projects as $project) {
                $_user = $em->getRepository('ApplicationFrontBundle:Projects')->find($project->getId());
                $_user->setStatus(0);
            }
            $em->persist($organization);
            $entity->setOrganization($organization);
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('fos_user_security_logout'));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a AcidDetectionStrips entity.
     *
     * @param AcidDetectionStrips $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(AccountClosure $entity) {
        $form = $this->createForm(new AccountClosureType(), $entity, array(
            'action' => $this->generateUrl('account_closure'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Permanently Close This Account'));

        return $form;
    }

    /**
     * Displays a form to create a new AcidDetectionStrips entity.
     *
     * @Route("/close", name="account_close")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function newAction() {
        $entity = new AccountClosure();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

}
