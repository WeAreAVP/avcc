<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\Projects;
use Application\Bundle\FrontBundle\Form\ProjectsType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
     * @return stdObject
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
     * @param Request $request
     *
     * @Route("/", name="projects_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:Projects:new.html.twig")
     *
     * @return array entity and form
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
        $formOptions['currentUser'] = $this->getUser();
        $form = $this->createForm(new ProjectsType($formOptions), $entity, array(
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
     * @return array project entity and form
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
     * @param integer $id project id
     *
     * @Route("/{id}", name="projects_show")
     * @Method("GET")
     * @Template()
     *
     * @return array
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:Projects')->find($id);

        if (!$entity) {
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
     * @param integer $id project id
     *
     * @Route("/{id}/edit", name="projects_edit")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:Projects')->find($id);

        if (!$entity) {
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
        $formOptions['currentUser'] = $this->getUser();
        $form = $this->createForm(new ProjectsType($formOptions), $entity, array(
            'action' => $this->generateUrl('projects_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Projects entity.
     *
     * @param Request $request
     * @param integer $id
     *
     * @Route("/{id}", name="projects_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:Projects:edit.html.twig")
     * @return array
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $entity = $em->getRepository('ApplicationFrontBundle:Projects')->find($id);

        if (!$entity) {
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
     * @param Request $request
     * @param integer $id
     *
     * @Route("/{id}", name="projects_delete")
     * @Method("DELETE")
     * @return Redirect
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:Projects')->find($id);

            if (!$entity) {
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
                        ->getForm();
    }

    /**
     * Displays a form to select media type and projects.
     *
     * @param integer $id
     *
     * @Route("/add/{id}", name="project_record_add")
     * @Method("GET")
     * @Template()
     * @return template
     */
    public function addRecordProjectAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $projects = $em->getRepository('ApplicationFrontBundle:Projects')->findAll();
        $mediaTypes = $em->getRepository('ApplicationFrontBundle:MediaTypes')->findAll();

        return $this->render('ApplicationFrontBundle:Projects:addRecord.html.twig', array(
                    'projects' => $projects,
                    'project_id' => $id,
                    'mediaTypes' => $mediaTypes
        ));
    }

    /**
     * Get media type and project id and redirect to add records.
     *
     * @param Request $request
     *
     * @Route("/addRec", name="project_add_rec")
     * @Method("POST")
     * @Template()
     * @return redirect
     */
    public function addRecordAction(Request $request)
    {
        $id = $request->request->get('project');
        $mediaTypeId = $request->request->get('mediaType');

        /// Audio
        if ($mediaTypeId == 1) {
            return $this->redirect($this->generateUrl('record_new_against_project', array('projectId' => $id)));
        } elseif ($mediaTypeId == 2) {
            /// Film
            return $this->redirect($this->generateUrl('record_film_new_against_project', array('projectId' => $id)));
        } elseif ($mediaTypeId == 3) {
            /// Video
            return $this->redirect($this->generateUrl('record_video_new_against_project', array('projectId' => $id)));
        } else {
            throw new AccessDeniedException();
        }
    }

}
