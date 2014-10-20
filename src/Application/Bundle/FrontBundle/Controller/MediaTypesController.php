<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\MediaTypes;
use Application\Bundle\FrontBundle\Form\MediaTypesType;

/**
 * MediaTypes controller.
 *
 * @Route("/vocabularies/mediatypes")
 */
class MediaTypesController extends Controller
{

    /**
     * Lists all MediaTypes entities.
     *
     * @Route("/", name="vocabularies_mediatypes")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:MediaTypes')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new MediaTypes entity.
     *
     * @Route("/", name="vocabularies_mediatypes_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:MediaTypes:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new MediaTypes();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Media type added succesfully.');
            
            return $this->redirect($this->generateUrl('vocabularies_mediatypes_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a MediaTypes entity.
     *
     * @param MediaTypes $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(MediaTypes $entity)
    {
        $form = $this->createForm(new MediaTypesType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_mediatypes_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new MediaTypes entity.
     *
     * @Route("/new", name="vocabularies_mediatypes_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new MediaTypes();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a MediaTypes entity.
     *
     * @Route("/{id}", name="vocabularies_mediatypes_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:MediaTypes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MediaTypes entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing MediaTypes entity.
     *
     * @Route("/{id}/edit", name="vocabularies_mediatypes_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:MediaTypes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MediaTypes entity.');
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
    * Creates a form to edit a MediaTypes entity.
    *
    * @param MediaTypes $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(MediaTypes $entity)
    {
        $form = $this->createForm(new MediaTypesType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_mediatypes_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing MediaTypes entity.
     *
     * @Route("/{id}", name="vocabularies_mediatypes_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:MediaTypes:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:MediaTypes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MediaTypes entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Media type updated succesfully.');
            
            return $this->redirect($this->generateUrl('vocabularies_mediatypes_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a MediaTypes entity.
     *
     * @Route("/{id}", name="vocabularies_mediatypes_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:MediaTypes')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find MediaTypes entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('vocabularies_mediatypes'));
    }

    /**
     * Creates a form to delete a MediaTypes entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('vocabularies_mediatypes_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
