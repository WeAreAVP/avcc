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

        $entity = $em->getRepository('ApplicationFrontBundle:HelpGuide')->findBy(array('slug' => $slug));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Help Guide.');
        }
        return array(
            'entity' => $entity,
        );
    }

}
