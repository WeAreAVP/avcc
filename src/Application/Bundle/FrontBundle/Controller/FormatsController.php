<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\Formats;
use Application\Bundle\FrontBundle\Form\FormatsType;

/**
 * Formats controller.
 *
 * @Route("/vocabularies/formats")
 */
class FormatsController extends Controller
{

    /**
     * Lists all Formats entities.
     *
     * @Route("/", name="vocabularies_formats")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:Formats')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    
    /**
     * Creates a new Formats entity.
     * 
     * @param Request $request
     * 
     * @Route("/", name="vocabularies_formats_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:Formats:new.html.twig")
     * @return array
     */
    public function createAction(Request $request)
    {
        $entity = new Formats();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Media types added succesfully.');

            return $this->redirect($this->generateUrl('vocabularies_formats_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Formats entity.
     *
     * @param Formats $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Formats $entity)
    {
        $form = $this->createForm(new FormatsType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_formats_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Formats entity.
     *
     * @Route("/new", name="vocabularies_formats_new")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function newAction()
    {
        $entity = new Formats();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Formats entity.
     * 
     * @param integer $id 
     * 
     * @Route("/{id}", name="vocabularies_formats_show")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:Formats')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Formats entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Formats entity.
     * 
     * @param integer $id 
     * 
     * @Route("/{id}/edit", name="vocabularies_formats_edit")
     * @Method("GET")
     * @Template()
     * @return array 
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:Formats')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Formats entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Formats entity.
    *
    * @param Formats $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Formats $entity)
    {
        $form = $this->createForm(new FormatsType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_formats_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Formats entity.
     * 
     * @param Request $request
     * @param integer $id 
     * 
     * @Route("/{id}", name="vocabularies_formats_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:Formats:edit.html.twig")
     * @return array 
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:Formats')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Formats entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Media type updated succesfully.');

            return $this->redirect($this->generateUrl('vocabularies_formats_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Formats entity.
     * 
     * @param Request $request
     * @param integer $id 
     * 
     * @Route("/{id}", name="vocabularies_formats_delete")
     * @Method("DELETE")
     * @return redirect
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:Formats')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Formats entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('vocabularies_formats'));
    }

    /**
     * Creates a form to delete a Formats entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('vocabularies_formats_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
