<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\TapeThickness;
use Application\Bundle\FrontBundle\Form\TapeThicknessType;

/**
 * TapeThickness controller.
 *
 * @Route("/vocabularies/tapethickness")
 */
class TapeThicknessController extends Controller
{

    /**
     * Lists all TapeThickness entities.
     *
     * @Route("/", name="vocabularies_tapethickness")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:TapeThickness')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new TapeThickness entity.
     *
     * @param Request $request Description
     *
     * @Route("/", name="vocabularies_tapethickness_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:TapeThickness:new.html.twig")
     * @return array
     */
    public function createAction(Request $request)
    {
        $entity = new TapeThickness();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Tape thickness added succesfully.');

            return $this->redirect($this->generateUrl('vocabularies_tapethickness'));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a TapeThickness entity.
     *
     * @param TapeThickness $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(TapeThickness $entity)
    {
        $form = $this->createForm(new TapeThicknessType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_tapethickness_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new TapeThickness entity.
     *
     * @Route("/new", name="vocabularies_tapethickness_new")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function newAction()
    {
        $entity = new TapeThickness();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a TapeThickness entity.
     *
     * @param integer $id
     *
     * @Route("/{id}", name="vocabularies_tapethickness_show")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:TapeThickness')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TapeThickness entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing TapeThickness entity.
     *
     * @param integer $id
     *
     * @Route("/{id}/edit", name="vocabularies_tapethickness_edit")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:TapeThickness')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TapeThickness entity.');
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
     * Creates a form to edit a TapeThickness entity.
     *
     * @param TapeThickness $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(TapeThickness $entity)
    {
        $form = $this->createForm(new TapeThicknessType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_tapethickness_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing TapeThickness entity.
     *
     * @param Request $request
     * @param integer $id
     *
     * @Route("/{id}", name="vocabularies_tapethickness_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:TapeThickness:edit.html.twig")
     * @return array
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:TapeThickness')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TapeThickness entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Tape thickness updated succesfully.');

            return $this->redirect($this->generateUrl('vocabularies_tapethickness_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a TapeThickness entity.
     *
     * @param Request $request
     * @param integer $id
     *
     * @Route("/{id}", name="vocabularies_tapethickness_delete")
     * @Method("DELETE")
     * @return redirect
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:TapeThickness')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TapeThickness entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('vocabularies_tapethickness'));
    }

    /**
     * Creates a form to delete a TapeThickness entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('vocabularies_tapethickness_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

}
