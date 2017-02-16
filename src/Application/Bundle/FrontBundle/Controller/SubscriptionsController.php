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
use Application\Bundle\FrontBundle\Controller\MyController;

/**
 * Plans controller.
 *
 * @Route("/subscription")
 */
class SubscriptionsController extends MyController {

    /**
     * Lists all AudioRecords entities.
     *
     * @Route("/", name="subscription")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function indexAction() {
        $organizations = array();
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('ApplicationFrontBundle:Users')->getSubscribedUsers();
        return array(
            'entities' => $entities
        );
    }

    /**
     * Cancel subscription.
     *
     * @param Request $request
     * @param type    $id
     *
     * @Route("/{id}", name="sub_cancel")
     * @Method("GET")
     * @return redirect
     */
    public function cancelAction(Request $request, $id) {
        $helper = new StripeHelper($this->container);
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationFrontBundle:Users')->find($id);
        $organization = $em->getRepository('ApplicationFrontBundle:Organizations')->find($entity->getOrganizations()->getId());
        $free = 2500;
        $freePlan = $em->getRepository('ApplicationFrontBundle:Plans')->findOneBy(array("amount" => 0));
        if (!empty($freePlan)) {
            $free = $freePlan->getRecords();
        }        
        $org_records = $em->getRepository('ApplicationFrontBundle:Records')->countOrganizationRecords($entity->getOrganizations()->getId());
        $counter = $org_records['total'];
        $cus_id = $entity->getStripeCustomerId();
        $res = $helper->deleteCustomer($cus_id);
        if ($res == TRUE) {
            if ($counter > $free) {
                $organization->setCancelSubscription(1);
            }else{
                $entity->setStripeCustomerId(NULL);
            }
            $organization->setIsPaid(0);
            $organization->setStatus(0);
            $em->persist($organization);
            $em->flush();
            $entity->setStripePlanId(NULL);
            $entity->setStripeSubscribeId(NULL);
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Successfully unsubscribed.');
        } else {
            $this->get('session')->getFlashBag()->add('error', $res);
        }
        return $this->redirect($this->generateUrl('subscription'));
    }

}
