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
use Application\Bundle\FrontBundle\Entity\MonthlyCharges;
use Application\Bundle\FrontBundle\Entity\MonthlyChargesRepository;
use Application\Bundle\FrontBundle\Form\MonthlyChargesType;

/**
 * MonthlyCharges controller.
 *
 * @Route("/monthly_charges")
 */
class MonthlyChargesController  extends MyController {
    
    /**
     * Lists all Monthly charges entities.
     *
     * @Route("/", name="monthly_charges")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('ApplicationFrontBundle:MonthlyCharges')->findAll();
        return array(
            'entities' => $entities,
        );
    }

     /**
     * Creates a new Bases entity.
     *
     * @param Request $request
     *
     * @Route("/", name="monthly_charges_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:MonthlyCharges:new.html.twig") 
     * @return array
     */
    public function createAction(Request $request) {
        $entity = new MonthlyCharges();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Monthly Charges added succesfully.');

            return $this->redirect($this->generateUrl('monthly_charges'));
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
    private function createCreateForm(MonthlyCharges $entity) {
        $form = $this->createForm(new MonthlyChargesType(), $entity, array(
            'action' => $this->generateUrl('monthly_charges_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Save'));

        return $form;
    }

    /**
     * Displays a form to create a new help_guide entity.
     *
     * @Route("/new", name="monthly_charges_new")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function newAction() {
        if (!in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles())) {
            throw new AccessDeniedException('Access Denied.');
        }
        $entity = new MonthlyCharges();
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
     * @Route("/{id}", name="monthly_charges_show")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:MonthlyCharges')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Monthly Charges.');
        }
        return array(
            'entity' => $entity,
        );
    }

    /**
     * Deletes a Monthly Charges.
     *
     * @param Request $request
     * @param type    $id
     *
     * @Route("/delete/{id}", name="monthly_charges_delete")
     * @Method("GET")
     * @return redirect
     */
    public function deleteAction(Request $request, $id) {
        if (!in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles())) {
            throw new AccessDeniedException('Access Denied.');
        }
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationFrontBundle:MonthlyCharges')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Monthly Charges entity.');
        }

        $em->remove($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('monthly_charges'));
    }

    /**
     * Displays a form to edit an existing Bases entity.
     *
     * @param integer $id
     *
     * @Route("/{id}/edit", name="monthly_charges_edit")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function editAction($id) {
        if (!in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles())) {
            throw new AccessDeniedException('Access Denied.');
        }
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:MonthlyCharges')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Monthly Charges entity.');
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
    private function createEditForm(MonthlyCharges $entity) {
        $form = $this->createForm(new MonthlyChargesType(), $entity, array(
            'action' => $this->generateUrl('monthly_charges_update', array('id' => $entity->getId())),
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
     * @Route("/{id}", name="monthly_charges_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:MonthlyCharges:edit.html.twig")
     * @return array
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:MonthlyCharges')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Monthly Charges entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Monthly Charges updated succesfully.');

            return $this->redirect($this->generateUrl('monthly_charges'));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView()
        );
    }
    
}

?>
