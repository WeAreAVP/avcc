<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\RecordingSpeed;
use Application\Bundle\FrontBundle\Form\RecordingSpeedType;

/**
 * RecordingSpeed controller.
 *
 * @Route("/vocabularies/recordingspeed")
 */
class RecordingSpeedController extends Controller
{

    /**
     * Lists all RecordingSpeed entities.
     *
     * @Route("/", name="vocabularies_recordingspeed")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:RecordingSpeed')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new RecordingSpeed entity.
     *
     * @Route("/", name="vocabularies_recordingspeed_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:RecordingSpeed:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new RecordingSpeed();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $posted_value = $this->get('request')->request->get('application_bundle_frontbundle_recordingspeed');

            $em = $this->getDoctrine()->getManager();
            $f = $form->getData();
            foreach ($posted_value['recSpeedFormat'] as $key => $value) {
                $entity = new RecordingSpeed();
                $entity->setName($f->getName());
                $format = $this->getDoctrine()->getRepository('ApplicationFrontBundle:Formats')->find($value);
                $entity->setRecSpeedFormat($format);
                $em->persist($entity);
                $em->flush();
            }

            return $this->redirect($this->generateUrl('vocabularies_recordingspeed'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a RecordingSpeed entity.
     *
     * @param RecordingSpeed $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(RecordingSpeed $entity)
    {
        $form = $this->createForm(new RecordingSpeedType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_recordingspeed_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new RecordingSpeed entity.
     *
     * @Route("/new", name="vocabularies_recordingspeed_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new RecordingSpeed();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a RecordingSpeed entity.
     *
     * @Route("/{id}", name="vocabularies_recordingspeed_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:RecordingSpeed')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RecordingSpeed entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing RecordingSpeed entity.
     *
     * @Route("/{id}/edit", name="vocabularies_recordingspeed_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:RecordingSpeed')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RecordingSpeed entity.');
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
    * Creates a form to edit a RecordingSpeed entity.
    *
    * @param RecordingSpeed $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(RecordingSpeed $entity)
    {
        $form = $this->createForm(new RecordingSpeedType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_recordingspeed_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing RecordingSpeed entity.
     *
     * @Route("/{id}", name="vocabularies_recordingspeed_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:RecordingSpeed:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:RecordingSpeed')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RecordingSpeed entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('vocabularies_recordingspeed_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a RecordingSpeed entity.
     *
     * @Route("/{id}", name="vocabularies_recordingspeed_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:RecordingSpeed')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find RecordingSpeed entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('vocabularies_recordingspeed'));
    }

    /**
     * Creates a form to delete a RecordingSpeed entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('vocabularies_recordingspeed_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
