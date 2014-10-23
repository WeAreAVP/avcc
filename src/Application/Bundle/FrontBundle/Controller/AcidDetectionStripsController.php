<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\AcidDetectionStrips;
use Application\Bundle\FrontBundle\Form\AcidDetectionStripsType;

/**
 * AcidDetectionStrips controller.
 *
 * @Route("/vocabularies/aciddetectionstrips")
 */
class AcidDetectionStripsController extends Controller
{

    /**
     * Lists all AcidDetectionStrips entities.
     *
     * @Route("/", name="vocabularies_aciddetectionstrips")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:AcidDetectionStrips')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new AcidDetectionStrips entity.
     *
     * @param Request $request
     *
     * @Route("/", name="vocabularies_aciddetectionstrips_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:AcidDetectionStrips:new.html.twig")
     * @return array
     */
    public function createAction(Request $request)
    {
        $entity = new AcidDetectionStrips();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Acid detection strip added succesfully.');

            return $this->redirect($this->generateUrl('vocabularies_aciddetectionstrips_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a AcidDetectionStrips entity.
     *
     * @param AcidDetectionStrips $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(AcidDetectionStrips $entity)
    {
        $form = $this->createForm(new AcidDetectionStripsType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_aciddetectionstrips_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new AcidDetectionStrips entity.
     *
     * @Route("/new", name="vocabularies_aciddetectionstrips_new")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function newAction()
    {
        $entity = new AcidDetectionStrips();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a AcidDetectionStrips entity.
     *
     * @param integer $id
     *
     * @Route("/{id}", name="vocabularies_aciddetectionstrips_show")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:AcidDetectionStrips')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AcidDetectionStrips entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing AcidDetectionStrips entity.
     *
     * @param integer $id
     *
     * @Route("/{id}/edit", name="vocabularies_aciddetectionstrips_edit")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:AcidDetectionStrips')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AcidDetectionStrips entity.');
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
     * Creates a form to edit a AcidDetectionStrips entity.
     *
     * @param AcidDetectionStrips $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(AcidDetectionStrips $entity)
    {
        $form = $this->createForm(new AcidDetectionStripsType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_aciddetectionstrips_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing AcidDetectionStrips entity.
     *
     * @param Request $request
     * @param type    $id
     *
     * @Route("/{id}", name="vocabularies_aciddetectionstrips_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:AcidDetectionStrips:edit.html.twig")
     * @return type
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:AcidDetectionStrips')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AcidDetectionStrips entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Acid detection strip updated succesfully.');

            return $this->redirect($this->generateUrl('vocabularies_aciddetectionstrips_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a AcidDetectionStrips entity.
     *
     * @param Request $request
     * @param type    $id
     *
     * @Route("/{id}", name="vocabularies_aciddetectionstrips_delete")
     * @Method("DELETE")
     * @return redirect redirect to list page
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:AcidDetectionStrips')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find AcidDetectionStrips entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('vocabularies_aciddetectionstrips'));
    }

    /**
     * Creates a form to delete a AcidDetectionStrips entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('vocabularies_aciddetectionstrips_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

}
