<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\HelpGuide;
use Application\Bundle\FrontBundle\Form\HelpGuideType;
use Application\Bundle\FrontBundle\Helper\DefaultFields as DefaultFields;
use Application\Bundle\FrontBundle\SphinxSearch\SphinxSearch;
use Symfony\Component\Form\FormError;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HelpGuideController
 *
 * @author rimsha
 */

/**
 * HelpGuide controller.
 *
 * @Route("/help")
 */
class HelpGuideController extends Controller {

    /**
     * Lists all AudioRecords entities.
     *
     * @Route("/", name="help_guide")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function indexAction() {
        $entities = '';
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:HelpGuide')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    
    /**
     * Lists all AudioRecords entities.
     *
     * @Route("/list", name="help_guide_list")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function listAction() {
        $entities = '';
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:HelpGuide')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Bases entity.
     *
     * @param Request $request
     *
     * @Route("/", name="help_guide_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:HelpGuide:new.html.twig")
     * @return array
     */
    public function createAction(Request $request) {
        $entity = new HelpGuide();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Help Guide added succesfully.');

            return $this->redirect($this->generateUrl('help_guide'));
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
    private function createCreateForm(HelpGuide $entity) {
        $form = $this->createForm(new HelpGuideType(), $entity, array(
            'action' => $this->generateUrl('help_guide_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Save'));

        return $form;
    }

    /**
     * Displays a form to create a new help_guide entity.
     *
     * @Route("/new", name="help_guide_new")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function newAction() {
        if (!in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles())) {
            throw new AccessDeniedException('Access Denied.');
        }
        $entity = new HelpGuide();
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
     * @Route("/{slug}", name="help_guide_show")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function showAction($slug) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:HelpGuide')->findOneBy(array('slug' => $slug));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Help Guide.');
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
     * @Route("/delete/{id}", name="help_guide_delete")
     * @Method("GET")
     * @return redirect
     */
    public function deleteAction(Request $request, $id) {
        if (!in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles())) {
            throw new AccessDeniedException('Access Denied.');
        }
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationFrontBundle:HelpGuide')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Help Guide entity.');
        }

        $em->remove($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('help_guide'));
    }
    
     /**
     * Displays a form to edit an existing Bases entity.
     *
     * @param integer $id
     *
     * @Route("/{id}/edit", name="help_guide_edit")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function editAction($id)
    {
        if (!in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles())) {
            throw new AccessDeniedException('Access Denied.');
        }
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:HelpGuide')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Help Guide entity.');
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
    private function createEditForm(HelpGuide $entity)
    {
        $form = $this->createForm(new HelpGuideType(), $entity, array(
            'action' => $this->generateUrl('help_guide_update', array('id' => $entity->getId())),
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
     * @Route("/{id}", name="help_guide_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:HelpGuide:edit.html.twig")
     * @return array
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:HelpGuide')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Help Guide entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Help Guide updated succesfully.');

            return $this->redirect($this->generateUrl('help_guide'));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView()
        );
    }


}
