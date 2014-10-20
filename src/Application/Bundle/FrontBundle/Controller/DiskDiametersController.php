<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\DiskDiameters;
use Application\Bundle\FrontBundle\Form\DiskDiametersType;

/**
 * DiskDiameters controller.
 *
 * @Route("/vocabularies/diskdiameters")
 */
class DiskDiametersController extends Controller
{

    /**
     * Lists all DiskDiameters entities.
     *
     * @Route("/", name="vocabularies_diskdiameters")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:DiskDiameters')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new DiskDiameters entity.
     *
     * @Route("/", name="vocabularies_diskdiameters_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:DiskDiameters:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new DiskDiameters();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Disk diameter added succesfully.');

            return $this->redirect($this->generateUrl('vocabularies_diskdiameters_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a DiskDiameters entity.
     *
     * @param DiskDiameters $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(DiskDiameters $entity)
    {
        $form = $this->createForm(new DiskDiametersType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_diskdiameters_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new DiskDiameters entity.
     *
     * @Route("/new", name="vocabularies_diskdiameters_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new DiskDiameters();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a DiskDiameters entity.
     *
     * @Route("/{id}", name="vocabularies_diskdiameters_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:DiskDiameters')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DiskDiameters entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing DiskDiameters entity.
     *
     * @Route("/{id}/edit", name="vocabularies_diskdiameters_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:DiskDiameters')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DiskDiameters entity.');
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
     * Creates a form to edit a DiskDiameters entity.
     *
     * @param DiskDiameters $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(DiskDiameters $entity)
    {
        $form = $this->createForm(new DiskDiametersType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_diskdiameters_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing DiskDiameters entity.
     *
     * @Route("/{id}", name="vocabularies_diskdiameters_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:DiskDiameters:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:DiskDiameters')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DiskDiameters entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Disk diameter updated succesfully.');

            return $this->redirect($this->generateUrl('vocabularies_diskdiameters_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a DiskDiameters entity.
     *
     * @Route("/{id}", name="vocabularies_diskdiameters_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:DiskDiameters')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find DiskDiameters entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('vocabularies_diskdiameters'));
    }

    /**
     * Creates a form to delete a DiskDiameters entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('vocabularies_diskdiameters_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

}
