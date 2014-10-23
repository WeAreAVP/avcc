<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\FormatVersions;
use Application\Bundle\FrontBundle\Form\FormatVersionsType;

/**
 * FormatVersions controller.
 *
 * @Route("/vocabularies/formatversions")
 */
class FormatVersionsController extends Controller
{

    /**
     * Lists all FormatVersions entities.
     *
     * @Route("/", name="vocabularies_formatversions")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:FormatVersions')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new FormatVersions entity.
     * 
     * @param Request $request
     * 
     * @Route("/", name="vocabularies_formatversions_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:FormatVersions:new.html.twig")
     * @return array
     */
    public function createAction(Request $request)
    {
        $entity = new FormatVersions();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $posted_value = $this->get('request')->request->get('application_bundle_frontbundle_formatversions');

            $em = $this->getDoctrine()->getManager();
            $f = $form->getData();
            foreach ($posted_value['formatVersionFormat'] as $key => $value) {
                $entity = new FormatVersions();
                $entity->setName($f->getName());
                $format = $this->getDoctrine()->getRepository('ApplicationFrontBundle:Formats')->find($value);
                $entity->setFormatVersionFormat($format);
                $em->persist($entity);
                $em->flush();
            }
            $this->get('session')->getFlashBag()->add('success', 'Format version added succesfully.');

            return $this->redirect($this->generateUrl('vocabularies_formatversions'));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a FormatVersions entity.
     *
     * @param FormatVersions $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(FormatVersions $entity)
    {
        $form = $this->createForm(new FormatVersionsType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_formatversions_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new FormatVersions entity.
     *
     * @Route("/new", name="vocabularies_formatversions_new")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function newAction()
    {
        $entity = new FormatVersions();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a FormatVersions entity.
     * 
     * @param integer $id 
     * 
     * @Route("/{id}", name="vocabularies_formatversions_show")
     * @Method("GET")
     * @Template()
     * @return array 
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:FormatVersions')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FormatVersions entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing FormatVersions entity.
     * 
     * @param integer $id 
     * 
     * @Route("/{id}/edit", name="vocabularies_formatversions_edit")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:FormatVersions')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FormatVersions entity.');
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
     * Creates a form to edit a FormatVersions entity.
     *
     * @param FormatVersions $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(FormatVersions $entity)
    {
        $form = $this->createForm(new FormatVersionsType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_formatversions_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing FormatVersions entity.
     * 
     * @param Request $request
     * @param integer $id 
     * 
     * @Route("/{id}", name="vocabularies_formatversions_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:FormatVersions:edit.html.twig")
     * @return array 
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:FormatVersions')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FormatVersions entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Format version updated succesfully.');

            return $this->redirect($this->generateUrl('vocabularies_formatversions_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a FormatVersions entity.
     * 
     * @param Request $request
     * @param integer $id 
     * 
     * @Route("/{id}", name="vocabularies_formatversions_delete")
     * @Method("DELETE")
     * @return redirect
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:FormatVersions')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FormatVersions entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('vocabularies_formatversions'));
    }

    /**
     * Creates a form to delete a FormatVersions entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('vocabularies_formatversions_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

}
