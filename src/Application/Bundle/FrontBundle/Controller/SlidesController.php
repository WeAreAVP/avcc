<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\Slides;
use Application\Bundle\FrontBundle\Form\SlidesType;

/**
 * Slides controller.
 *
 * @Route("/vocabularies/slides")
 */
class SlidesController extends Controller
{

    /**
     * Lists all Slides entities.
     *
     * @Route("/", name="vocabularies_slides")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:Slides')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Slides entity.
     *
     * @Route("/", name="vocabularies_slides_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:Slides:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Slides();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('vocabularies_slides_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Slides entity.
     *
     * @param Slides $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Slides $entity)
    {
        $form = $this->createForm(new SlidesType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_slides_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Slides entity.
     *
     * @Route("/new", name="vocabularies_slides_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Slides();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Slides entity.
     *
     * @Route("/{id}", name="vocabularies_slides_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:Slides')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Slides entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Slides entity.
     *
     * @Route("/{id}/edit", name="vocabularies_slides_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:Slides')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Slides entity.');
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
    * Creates a form to edit a Slides entity.
    *
    * @param Slides $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Slides $entity)
    {
        $form = $this->createForm(new SlidesType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_slides_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Slides entity.
     *
     * @Route("/{id}", name="vocabularies_slides_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:Slides:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:Slides')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Slides entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('vocabularies_slides_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Slides entity.
     *
     * @Route("/{id}", name="vocabularies_slides_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:Slides')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Slides entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('vocabularies_slides'));
    }

    /**
     * Creates a form to delete a Slides entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('vocabularies_slides_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
