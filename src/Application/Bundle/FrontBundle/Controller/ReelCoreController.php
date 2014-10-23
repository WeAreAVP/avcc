<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\ReelCore;
use Application\Bundle\FrontBundle\Form\ReelCoreType;

/**
 * ReelCore controller.
 *
 * @Route("/vocabularies/reelcore")
 */
class ReelCoreController extends Controller
{

    /**
     * Lists all ReelCore entities.
     *
     * @Route("/", name="vocabularies_reelcore")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:ReelCore')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new ReelCore entity.
     *
     * @param Request $request
     *
     * @Route("/", name="vocabularies_reelcore_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:ReelCore:new.html.twig")
     * @return array
     */
    public function createAction(Request $request)
    {
        $entity = new ReelCore();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('vocabularies_reelcore_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a ReelCore entity.
     *
     * @param ReelCore $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ReelCore $entity)
    {
        $form = $this->createForm(new ReelCoreType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_reelcore_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ReelCore entity.
     *
     * @Route("/new", name="vocabularies_reelcore_new")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function newAction()
    {
        $entity = new ReelCore();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a ReelCore entity.
     *
     * @param integer $id
     *
     * @Route("/{id}", name="vocabularies_reelcore_show")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:ReelCore')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ReelCore entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ReelCore entity.
     *
     * @param integer $id
     *
     * @Route("/{id}/edit", name="vocabularies_reelcore_edit")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:ReelCore')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ReelCore entity.');
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
    * Creates a form to edit a ReelCore entity.
    *
    * @param ReelCore $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ReelCore $entity)
    {
        $form = $this->createForm(new ReelCoreType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_reelcore_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing ReelCore entity.
     *
     * @param Request $request
     * @param integer $id
     *
     * @Route("/{id}", name="vocabularies_reelcore_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:ReelCore:edit.html.twig")
     * @return array
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:ReelCore')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ReelCore entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('vocabularies_reelcore_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a ReelCore entity.
     *
     * @param Request $request
     * @param integer $id
     *
     * @Route("/{id}", name="vocabularies_reelcore_delete")
     * @Method("DELETE")
     * @return array
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:ReelCore')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ReelCore entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('vocabularies_reelcore'));
    }

    /**
     * Creates a form to delete a ReelCore entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('vocabularies_reelcore_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
