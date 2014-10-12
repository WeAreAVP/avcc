<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\Projects;
use Application\Bundle\FrontBundle\Form\ProjectsType;

/**
 * Projects controller.
 *
 * @Route("/projects")
 */
class ProjectsController extends Controller
{

    /**
     * Lists all Projects entities.
     *
     * @Route("/", name="projects")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
         if (true === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            $entities = $em->getRepository('ApplicationFrontBundle:Projects')->findAll();
        } else {
            $entities = $em->getRepository('ApplicationFrontBundle:Projects')->findBy(array('organization' => $this->getUser()->getOrganizations()));
        }

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Projects entity.
     *
     * @Route("/", name="projects_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:Projects:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $user = $this->getUser();
        $entity = new Projects();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setUsersCreated($user);
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Project added succesfully.');

            return $this->redirect($this->generateUrl('projects', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Projects entity.
     *
     * @param Projects $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Projects $entity)
    {
        $form = $this->createForm(new ProjectsType(), $entity, array(
            'action' => $this->generateUrl('projects_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Projects entity.
     *
     * @Route("/new", name="projects_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Projects();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Projects entity.
     *
     * @Route("/{id}", name="projects_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:Projects')->find($id);

        if (! $entity) {
            throw $this->createNotFoundException('Unable to find Projects entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Projects entity.
     *
     * @Route("/{id}/edit", name="projects_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:Projects')->find($id);

        if (! $entity) {
            throw $this->createNotFoundException('Unable to find Projects entity.');
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
     * Creates a form to edit a Projects entity.
     *
     * @param Projects $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Projects $entity)
    {
        $form = $this->createForm(new ProjectsType(), $entity, array(
            'action' => $this->generateUrl('projects_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Projects entity.
     *
     * @Route("/{id}", name="projects_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:Projects:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $entity = $em->getRepository('ApplicationFrontBundle:Projects')->find($id);

        if (! $entity) {
            throw $this->createNotFoundException('Unable to find Projects entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entity->setUsersUpdated($user);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Project updated succesfully.');

            return $this->redirect($this->generateUrl('projects'));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Projects entity.
     *
     * @Route("/{id}", name="projects_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:Projects')->find($id);

            if (! $entity) {
                throw $this->createNotFoundException('Unable to find Projects entity.');
            }

            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Project deleted succesfully.');
        }

        return $this->redirect($this->generateUrl('projects'));
    }

    /**
     * Creates a form to delete a Projects entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
        ->setAction($this->generateUrl('projects_delete', array('id' => $id)))
        ->setMethod('DELETE')
        ->add('submit', 'submit', array('label' => 'Delete'))
        ->getForm()
        ;
    }

}
