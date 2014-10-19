<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\AudioRecords;
use Application\Bundle\FrontBundle\Form\AudioRecordsType;

/**
 * AudioRecords controller.
 *
 * @Route("/record")
 */
class AudioRecordsController extends Controller
{

    /**
     * Lists all AudioRecords entities.
     *
     * @Route("/", name="record")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:AudioRecords')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new AudioRecords entity.
     *
     * @Route("/", name="record_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:AudioRecords:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new AudioRecords();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('record_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a AudioRecords entity.
     *
     * @param AudioRecords $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(AudioRecords $entity)
    {
        $form = $this->createForm(new AudioRecordsType(), $entity, array(
            'action' => $this->generateUrl('record_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new AudioRecords entity.
     *
     * @Route("/new", name="record_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new AudioRecords();
        $form   = $this->createCreateForm($entity);
//		$displayOrder=array('record.uniqueId');
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
//            'displayOrder'   => $displayOrder,
        );
    }

    /**
     * Finds and displays a AudioRecords entity.
     *
     * @Route("/{id}", name="record_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:AudioRecords')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AudioRecords entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing AudioRecords entity.
     *
     * @Route("/{id}/edit", name="record_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:AudioRecords')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AudioRecords entity.');
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
    * Creates a form to edit a AudioRecords entity.
    *
    * @param AudioRecords $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(AudioRecords $entity)
    {
        $form = $this->createForm(new AudioRecordsType(), $entity, array(
            'action' => $this->generateUrl('record_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing AudioRecords entity.
     *
     * @Route("/{id}", name="record_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:AudioRecords:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:AudioRecords')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AudioRecords entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('record_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a AudioRecords entity.
     *
     * @Route("/{id}", name="record_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:AudioRecords')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find AudioRecords entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('record'));
    }

    /**
     * Creates a form to delete a AudioRecords entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('record_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
