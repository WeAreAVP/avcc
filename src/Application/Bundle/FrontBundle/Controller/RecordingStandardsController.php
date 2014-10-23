<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\RecordingStandards;
use Application\Bundle\FrontBundle\Form\RecordingStandardsType;

/**
 * RecordingStandards controller.
 *
 * @Route("/vocabularies/recordingstandards")
 */
class RecordingStandardsController extends Controller
{

    /**
     * Lists all RecordingStandards entities.
     *
     * @Route("/", name="vocabularies_recordingstandards")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:RecordingStandards')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new RecordingStandards entity.
     *
     * @param Request $request
     *
     * @Route("/", name="vocabularies_recordingstandards_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:RecordingStandards:new.html.twig")
     * @return array
     */
    public function createAction(Request $request)
    {
        $entity = new RecordingStandards();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('vocabularies_recordingstandards_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a RecordingStandards entity.
     *
     * @param RecordingStandards $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(RecordingStandards $entity)
    {
        $form = $this->createForm(new RecordingStandardsType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_recordingstandards_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new RecordingStandards entity.
     *
     * @Route("/new", name="vocabularies_recordingstandards_new")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function newAction()
    {
        $entity = new RecordingStandards();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a RecordingStandards entity.
     *
     * @param integer $id
     *
     * @Route("/{id}", name="vocabularies_recordingstandards_show")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:RecordingStandards')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RecordingStandards entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing RecordingStandards entity.
     *
     * @param integer $id
     *
     * @Route("/{id}/edit", name="vocabularies_recordingstandards_edit")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:RecordingStandards')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RecordingStandards entity.');
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
    * Creates a form to edit a RecordingStandards entity.
    *
    * @param RecordingStandards $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(RecordingStandards $entity)
    {
        $form = $this->createForm(new RecordingStandardsType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_recordingstandards_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing RecordingStandards entity.
     *
     * @param Request $request
     * @param integer $id
     *
     * @Route("/{id}", name="vocabularies_recordingstandards_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:RecordingStandards:edit.html.twig")
     * @return array
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:RecordingStandards')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RecordingStandards entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('vocabularies_recordingstandards_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a RecordingStandards entity.
     *
     * @param Request $request
     * @param integer $id
     *
     * @Route("/{id}", name="vocabularies_recordingstandards_delete")
     * @Method("DELETE")
     * @return arra
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:RecordingStandards')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find RecordingStandards entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('vocabularies_recordingstandards'));
    }

    /**
     * Creates a form to delete a RecordingStandards entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('vocabularies_recordingstandards_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
