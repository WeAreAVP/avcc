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
use Application\Bundle\FrontBundle\Entity\Plans;
use Application\Bundle\FrontBundle\Entity\PlansRepository;
use Application\Bundle\FrontBundle\Form\PlansType;
use Application\Bundle\FrontBundle\Helper\StripeHelper;
use Symfony\Component\Form\FormError;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Application\Bundle\FrontBundle\Controller\MyController;

/**
 * Plans controller.
 *
 * @Route("/plan")
 */
class PlansController extends MyController {

    public $proration_date = "";

    /**
     * Lists all AudioRecords entities.
     *
     * @Route("/", name="plan")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function indexAction() {
        $entities = '';
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('ApplicationFrontBundle:Plans')->findAll();
        return array(
            'entities' => $entities,
        );
    }

    /**
     * Lists all AudioRecords entities.
     *
     * @param Request $request
     * 
     * @Route("/upgrade/{id}", name="plan_list")
     * @Method("GET")
     * @Template("ApplicationFrontBundle:Plans:list.html.php")
     * @return array
     */
    public function upgradeAction(Request $request, $id) {
        $email = 'avcc@avpreserve.com'; //$this->container->getParameter('google_client_id');
        $users = '';
        $plan_id = '';
        $notification = FALSE;
        $has_admin = FALSE;
        $reactive = false;
        $admins = array();
        $helper = new StripeHelper($this->container);
        $em = $this->getDoctrine()->getManager(); //
        $card = '';
        if (in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles())) {
            $organization = $em->getRepository('ApplicationFrontBundle:Organizations')->find($id);
            $creator = $organization->getUsersCreated();
            if (in_array("ROLE_ADMIN", $creator->getRoles())) {
                $plan_id = $creator->getStripePlanId();
                $has_admin = TRUE;
                if (!empty($plan_id) && $plan_id != NULL) {
                    $customer = $helper->getCustomer($creator->getStripeCustomerId());
                    if ($customer["subscriptions"]["data"][0]["cancel_at_period_end"]) {
                        $reactive = true;
                    }
                    $card = $customer["sources"]["data"][0];
                }
            } else {
                $entities = $em->getRepository('ApplicationFrontBundle:Users')->getUsersWithoutCurentLoggedIn($this->getUser()->getId(), $id);
                foreach ($entities as $entity) {
                    if (in_array("ROLE_ADMIN", $entity->getRoles())) {
                        $admins[] = $entity;
                        $has_admin = TRUE;
                    }
                }
            }
        } else if (in_array("ROLE_ADMIN", $this->getUser()->getRoles())) {
            $session = $this->getRequest()->getSession();
            if (($session->has('termsStatus') && $session->get('termsStatus') == 0) || ($session->has('limitExceed') && $session->get('limitExceed') == 0) && $this->getUser()->getOrganizations()->getCancelSubscription() == 1) {
                return $this->redirect($this->generateUrl('dashboard'));
            }
            $org_creator = $this->getUser()->getOrganizations()->getUsersCreated();
            $id = $this->getUser()->getId();
            if ($org_creator->getId() != $id) {
                $email = $org_creator->getEmail();
                $notification = TRUE;
            } else {
                $has_admin = TRUE;
                $plan_id = $this->getUser()->getStripePlanId();
                if (!empty($plan_id) && $plan_id != NULL) {
                    $customer = $helper->getCustomer($org_creator->getStripeCustomerId());
                    if ($customer["subscriptions"]["data"][0]["cancel_at_period_end"]) {
                        $reactive = true;
                    }
                    $card = $customer["sources"]["data"][0];
                }
            }
        }
        $entities = $em->getRepository('ApplicationFrontBundle:Plans')->findBy(array(), array('records' => 'ASC'));
        $_data = array(
            'entities' => $entities,
            'plan_id' => $plan_id,
            'users' => $admins,
            'notification' => $notification,
            'email' => $email,
            'has_admin' => $has_admin,
            'card' => $card,
            'reactive' => $reactive
        );
//        echo '<pre>';
//                print_r($_data);exit;
        return $_data;
    }

    /**
     * Creates a new Bases entity.
     *
     * @param Request $request
     *
     * @Route("/", name="plan_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:Plans:new.html.twig")
     * @return array
     */
    public function createAction(Request $request) {
        $entity = new Plans();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $helper = new StripeHelper($this->container);
            $data = array(
                'amount' => $entity->getAmount(),
                'id' => $entity->getPlanId(),
                'name' => $entity->getName(),
                'desc' => $entity->getDescription()
            );
            $res = $helper->createPlan($data);
            if ($res == TRUE) {
                $em = $this->getDoctrine()->getManager();
                $entity->setCurrency("usd");
                $entity->setPlanInterval("month");
                $em->persist($entity);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'Plan added succesfully.');
                return $this->redirect($this->generateUrl('plan'));
            } else {
                $this->get('session')->getFlashBag()->add('error', $res);
            }
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a AudioRecords entity.
     *
     * @param AudioRecords  $entity The entity
     * @param EntityManager $em
     * @param form          $data
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Plans $entity) {
        $form = $this->createForm(new PlansType(), $entity, array(
            'action' => $this->generateUrl('plan_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Save'));

        return $form;
    }

    /**
     * Displays a form to create a new plan entity.
     *
     * @Route("/new", name="plan_new")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function newAction() {
        if (!in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles())) {
            throw new AccessDeniedException('Access Denied.');
        }
        $entity = new Plans();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Bases entity.
     *
     * @param integer $id
     *
     * @Route("/{id}", name="plan_show")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:Plans')->findOneBy(array('id' => $id));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plan.');
        }
        return array(
            'entity' => $entity,
        );
    }

    /**
     * Deletes a Help Guide.
     *
     * @param Request $request
     * @param type    $id
     *
     * @Route("/delete/{id}", name="plan_delete")
     * @Method("GET")
     * @return redirect
     */
    public function deleteAction(Request $request, $id) {
        if (!in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles())) {
            throw new AccessDeniedException('Access Denied.');
        }
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationFrontBundle:Plans')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Help Guide entity.');
        }

        $em->remove($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('plan'));
    }

    /**
     * Displays a form to edit an existing Bases entity.
     *
     * @param integer $id
     *
     * @Route("/{id}/edit", name="plan_edit")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function editAction($id) {
        if (!in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles())) {
            throw new AccessDeniedException('Access Denied.');
        }
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:Plans')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plan entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView()
        );
    }

    /**
     * Creates a form to edit a Bases entity.
     *
     * @param Bases $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Plans $entity) {
        $form = $this->createForm(new PlansType(), $entity, array(
            'action' => $this->generateUrl('plan_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Bases entity.
     *
     * @param Request $request
     * @param type    $id
     *
     * @Route("/{id}", name="plan_update") 
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:Plans:edit.html.twig")
     * @return array
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:Plans')->find($id);
        $plan_id = $entity->getPlanId();
        $amount = $entity->getAmount();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Help Guide entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entity->setPlanId($plan_id);
            $entity->setAmount($amount);
            $helper = new StripeHelper($this->container);
            $data = array(
                'id' => $entity->getPlanId(),
                'name' => $entity->getName(),
                'desc' => $entity->getDescription()
            );
            $res = $helper->updatePlan($data);
            if ($res == TRUE) {
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'Plan updated succesfully.');

                return $this->redirect($this->generateUrl('plan'));
            } else {
                $this->get('session')->getFlashBag()->add('error', $res);
            }
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView()
        );
    }

    /**
     * Subscribe
     *
     * @param Request $request
     *
     * @Route("/subscribe", name="plan_sub") 
     * @Method("POST")
     * @Template()
     * @return array
     */
    public function subscribeAction(Request $request) {
        $stripeToken = $request->request->get('stripeToken');
        $planID = $request->request->get('plan_id');
        $org_id = $request->request->get('org_id');
        $user_id = $request->request->get('user_id');
        $em = $this->getDoctrine()->getManager();
        $organization = $em->getRepository('ApplicationFrontBundle:Organizations')->find($org_id);

        $helper = new StripeHelper($this->container);

        if (empty($planID) || $planID == "") {
            $user = $organization->getUsersCreated();
            $res = $helper->updateCardInfo($user->getStripeCustomerId(), $stripeToken);
            if (is_array($res)) {
                $this->get('session')->getFlashBag()->add('success', 'Successfully updated card info.');
                return $this->redirect($this->generateUrl('plan_list', array('id' => $org_id)));
            } else {
                $this->get('session')->getFlashBag()->add('error', $res);
                return $this->redirect($this->generateUrl('plan_list', array('id' => $org_id)));
            }
        }
        if ($user_id) {
            $user = $em->getRepository('ApplicationFrontBundle:Users')->find($user_id);
            $email = $user->getEmail();
            $cusId = $user->getStripeCustomerId();
            $subId = $user->getStripeSubscribeId();
        } else {
            $user = $organization->getUsersCreated();
            $email = $user->getEmail();
            $cusId = $user->getStripeCustomerId();
            $subId = $user->getStripeSubscribeId();
        }

        if (!empty($stripeToken)) {
            $data = array(
                'plan_id' => $planID,
                'token' => $stripeToken,
                'email' => $email,
                'customerId' => $cusId,
                'subId' => $subId
            );
        } else {
            $data = array(
                'plan_id' => $planID,
                'email' => $email,
                'customerId' => $cusId,
                'subId' => $subId
            );
        }

        $data["proration_date"] = $this->proration_date;
        $res = $helper->createAndSubscribeCustomer($data);
        if (is_array($res)) {
            $session = $this->getRequest()->getSession();
            if ($session->has('limitExceed') && $session->get('limitExceed') == 0) {
                $session->set('limitExceed', 1);
            }
            if ($user_id) {
                $organization->setUsersCreated($user);
            }
            $organization->setIsPaid(1);
            $em->persist($organization);
            $em->flush();
            $user->setStripePlanId($planID);
            $user->setStripeSubscribeId($res["subscriptions"]["data"][0]["id"]);
            $user->setStripeCustomerId($res["id"]);
            $em->persist($user);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Successfully subscribed.');
            return $this->redirect($this->generateUrl('plan_list', array('id' => $org_id)));
        } else {
            $this->get('session')->getFlashBag()->add('error', $res);
            return $this->redirect($this->generateUrl('plan_list', array('id' => $org_id)));
        }
    }

    /**
     * update field order
     *
     * @param Request $request
     *
     * @Route("/org_admins", name="org_admins")
     * @Method("POST")
     * @return array
     */
    public function getOrganizationAdmins(Request $request) {
        // code to update
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $organization = $em->getRepository('ApplicationFrontBundle:Organizations')->find($id);
        $org_creator = $organization->getUsersCreated()->getRoles();
        if (in_array("ROLE_ADMIN", $org_creator)) {
            echo json_encode(array('success' => 'true'));
            exit;
        }
        $entities = $em->getRepository('ApplicationFrontBundle:Users')->getUsersWithoutCurentLoggedIn($this->getUser()->getId(), $id);
        $html = '';
        foreach ($entities as $entity) {
            if (in_array("ROLE_ADMIN", $entity->getRoles())) {
                $html .= '<option value="' . $entity->getId() . '">' . $entity->getName() . '</option>';
            }
        }
        if (!empty($html)) {
            $view = '<option value=""></option>' . $html;
            echo json_encode(array('success' => 'true', 'html' => $view));
        } else {
            echo json_encode(array('success' => 'true'));
        }
        exit;
    }

    /**
     * update field order
     *
     * @param Request $request
     *
     * @Route("/validate", name="validate_plan")
     * @Method("POST")
     * @return array
     */
    public function validateSubscriptionPlan(Request $request) {
        // code to update
        $id = $request->request->get('plan_id');
        $org_id = $request->request->get('org_id');
        $em = $this->getDoctrine()->getManager();
        $organization = $em->getRepository('ApplicationFrontBundle:Organizations')->find($org_id);

        $org_records = $em->getRepository('ApplicationFrontBundle:Records')->countOrganizationRecords($organization->getId());
        $counter = $org_records['total'];

        $entity = $em->getRepository('ApplicationFrontBundle:Plans')->findBy(array("planId" => $id));


        if ($counter >= $entity[0]->getRecords()) {
            echo json_encode(array('success' => 'false', "org_count" => $counter, "plan_count" => $entity[0]->getRecords()));
            exit;
        }

        echo json_encode(array('success' => 'true'));
        exit;
    }

    /**
     * Subscribe
     *
     * @param Request $request
     *
     * @Route("/unsub", name="plan_unsub") 
     * @Method("POST")
     * @Template()
     * @return array
     */
    public function unsubscribeAction(Request $request) {
        $org_id = $request->request->get('org_id');
        $reactive = $request->request->get('reactive');
        $em = $this->getDoctrine()->getManager();

        $organization = $em->getRepository('ApplicationFrontBundle:Organizations')->find($org_id);
        $entity = $organization->getUsersCreated();
        $helper = new StripeHelper($this->container);
        if ($reactive == 1) {
            $res = $helper->updateSubscription($entity->getStripeSubscribeId(), $entity->getStripePlanId());
        } else {
            $res = $helper->cancelSubscription($entity->getStripeSubscribeId(), true);
        }
        if (is_array($res)) {
            if ($reactive == 1) {
                $this->get('session')->getFlashBag()->add('success', 'Your subscription is successfully re-activated');
            } else {
                $end_at = date('d M, Y', $res['current_period_end']);
                $this->get('session')->getFlashBag()->add('success', 'Your subscription will be canceled at the end of the billing period(' . $end_at . ')');
            }
        } else {
            $this->get('session')->getFlashBag()->add('error', $res);
        }
        return $this->redirect($this->generateUrl('plan_list', array('id' => $org_id)));
    }

    /**
     * Subscribe
     *
     * @param Request $request
     *
     * @Route("/confirm_upgrade", name="plan_confirm_upgrade") 
     * @Method("POST")
     * @Template()
     * @return array
     */
    public function confirmUpgradeAction(Request $request) {
        $this->proration_date = time();
        $em = $this->getDoctrine()->getManager();
        $planID = $request->request->get('plan_id');
        $org_id = $request->request->get('org_id');
        $organization = $em->getRepository('ApplicationFrontBundle:Organizations')->find($org_id);
        $entity = $organization->getUsersCreated();
        $data["cutomerId"] = $entity->getStripeCustomerId();
        $data["subId"] = $entity->getStripeSubscribeId();
        $data["planId"] = $planID;
        $data['proration_date'] = $this->proration_date;
        $helper = new StripeHelper($this->container);
        $res = $helper->prorationPreview($data);
        echo json_encode(array('success' => 'true', 'cost' => $res));
        exit;
    }

}
