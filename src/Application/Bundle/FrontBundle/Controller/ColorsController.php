<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\Colors;
use Application\Bundle\FrontBundle\Form\ColorsType;

/**
 * Colors controller.
 *
 * @Route("/vocabularies/colors")
 */
class ColorsController extends Controller
{

    /**
     * Lists all Colors entities.
     *
     * @Route("/", name="vocabularies_colors")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:Colors')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Colors entity.
     *
     * @param Request $request
     *
     * @Route("/", name="vocabularies_colors_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:Colors:new.html.twig")
     * @return array
     */
    public function createAction(Request $request)
    {
        $entity = new Colors();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Color added succesfully.');

            return $this->redirect($this->generateUrl('vocabularies_colors_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Colors entity.
     *
     * @param Colors $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Colors $entity)
    {
        $form = $this->createForm(new ColorsType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_colors_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Colors entity.
     *
     * @Route("/new", name="vocabularies_colors_new")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function newAction()
    {
        $entity = new Colors();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Colors entity.
     *
     * @param integer $id
     *
     * @Route("/{id}", name="vocabularies_colors_show")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:Colors')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Colors entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Colors entity.
     *
     * @param integer $id
     *
     * @Route("/{id}/edit", name="vocabularies_colors_edit")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:Colors')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Colors entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Colors entity.
     *
     * @param Colors $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Colors $entity)
    {
        $form = $this->createForm(new ColorsType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_colors_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Colors entity.
     *
     * @param Request $request
     * @param integer $id
     *
     * @Route("/{id}", name="vocabularies_colors_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:Colors:edit.html.twig")
     * @return array
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:Colors')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Colors entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Color updated succesfully.');

            return $this->redirect($this->generateUrl('vocabularies_colors_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Colors entity.
     *
     * @param Request $request
     * @param integer $id
     *
     * @Route("/{id}", name="vocabularies_colors_delete")
     * @Method("DELETE")
     * @return redirect
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:Colors')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Colors entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('vocabularies_colors'));
    }

    /**
     * Creates a form to delete a Colors entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('vocabularies_colors_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm();
    }

}
