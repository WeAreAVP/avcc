<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\Organizations;
use Application\Bundle\FrontBundle\Form\OrganizationsType;

/**
 * Organizations controller.
 *
 * @Route("/organizations")
 */
class OrganizationsController extends Controller
{

    /**
     * Lists all Organizations entities.
     *
     * @Route("/", name="organizations")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:Organizations')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Organizations entity.
     *
     * @Route("/", name="organizations_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:Organizations:new.html.twig")     * 
     * @param \Symfony\Component\HttpFoundation\Request $request form data variable
     * 
     * @return array
     */
    public function createAction(Request $request)
    {
        $user = $this->getUser();
        $entity = new Organizations();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setUsersCreated($user);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('organizations', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Organizations entity.
     *
     * @param Organizations $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Organizations $entity)
    {
        $form = $this->createForm(new OrganizationsType(), $entity, array(
            'action' => $this->generateUrl('organizations_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Organizations entity.
     *
     * @Route("/new", name="organizations_new")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function newAction()
    {
        $entity = new Organizations();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Organizations entity.
     *
     * @Route("/{id}", name="organizations_show")
     * @Method("GET")
     * @Template()     * 
     * @param integer $id organization id
     * 
     * @return array
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:Organizations')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Organizations entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Organizations entity.
     *
     * @Route("/{id}/edit", name="organizations_edit")
     * @Method("GET")
     * @param integer $id organization id
     * 
     * @Template()
     * @return array
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:Organizations')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Organizations entity.');
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
     * Creates a form to edit a Organizations entity.
     *
     * @param Organizations $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Organizations $entity)
    {
        $form = $this->createForm(new OrganizationsType(), $entity, array(
            'action' => $this->generateUrl('organizations_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Organizations entity.
     *
     * @Route("/{id}", name="organizations_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:Organizations:edit.html.twig")
     * @param \Symfony\Component\HttpFoundation\Request $request form data variable
     * @param integer                                   $id organization id
     * 
     * @return array
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $entity = $em->getRepository('ApplicationFrontBundle:Organizations')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Organizations entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entity->setUsersUpdated($user);
            $em->flush();

            return $this->redirect($this->generateUrl('organizations'));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Organizations entity.
     *
     * @Route("/{id}", name="organizations_delete")
     * @Method("DELETE")
     * @param \Symfony\Component\HttpFoundation\Request $request form data variable
     * @param integer                                   $id organization id
     * 
     * @return redirect to organization list page
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:Organizations')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Organizations entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('organizations'));
    }

    /**
     * Creates a form to delete a Organizations entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('organizations_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm();
    }

}
