<?php

/**
 * AVCC
 * 
 * @category AVCC
 * @package  Application
 * @author   Nouman Tayyab <nouman@avpreserve.com>
 * @author   Rimsha Khalid <rimsha@avpreserve.com>
 * @license  AGPLv3 http://www.gnu.org/licenses/agpl-3.0.txt
 * @copyright Audio Visual Preservation Solutions, Inc
 * @link     http://avcc.avpreserve.com
 */

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\Projects;
use Application\Bundle\FrontBundle\Entity\Users;
use Application\Bundle\FrontBundle\Entity\Records;
use Application\Bundle\FrontBundle\Entity\UsersRepository;
use Application\Bundle\FrontBundle\Form\ProjectsType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Application\Bundle\FrontBundle\Helper\DefaultFields as DefaultFields;
use Application\Bundle\FrontBundle\SphinxSearch\SphinxSearch;
use Application\Bundle\FrontBundle\Controller\MyController;
/**
 * Projects controller.
 *
 * @Route("/projects")
 */
class ProjectsController extends MyController {

    /**
     * Lists all Projects entities.
     * 
     * @param integer $orgId
     * 
     * @Route("/", name="projects")
     * @Method("GET")
     * @Template()
     * @return stdObject
     */
    public function indexAction($orgId = null) {
        $session = $this->getRequest()->getSession();        
        if (($session->has('termsStatus') && $session->get('termsStatus') == 0) || ($session->has('limitExceed') && $session->get('limitExceed') == 0)) {
            return $this->redirect($this->generateUrl('dashboard'));
        }
        $organizations = array();
        $em = $this->getDoctrine()->getManager();
        $org = array();
        if (true === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN') && $orgId) {
            $entities = $em->getRepository('ApplicationFrontBundle:Projects')->findBy(array('organization' => $orgId));
            $organizations = $em->getRepository('ApplicationFrontBundle:Organizations')->findBy(array('status' => 1));
        } else if (true === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            $entities = $organizations = $em->getRepository('ApplicationFrontBundle:Projects')->findAll();
        } else {
            $entities = $em->getRepository('ApplicationFrontBundle:Projects')->findBy(array('organization' => $this->getUser()->getOrganizations()));
        }
        foreach ($organizations as $entity) {
            $org[$entity->getOrganization()->getId()] = $entity->getOrganization()->getName();
        }
        return array(
            'entities' => $entities,
            'organization' => $org,
            'org_id' => $orgId
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
    public function createAction(Request $request) {
        $user = $this->getUser();
        $entity = new Projects();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $usersList = $request->request->get('application_bundle_frontbundle_projects');
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $fieldsObj = new DefaultFields();
            $view_settings = $fieldsObj->getDefaultOrder();
            $entity->setUsersCreated($user);
            $entity->setViewSetting($view_settings);
            if ($entity->getOrganization()->getStatus() == 0) {
                $entity->setStatus(0);
            }
            $em->persist($entity);
            $em->flush();

            if (isset($usersList['projectUsers'])) {
                foreach ($usersList['projectUsers'] as $key => $users) {
                    $_user = $em->getRepository('ApplicationFrontBundle:Users')->find($users);
                    if ($_user) {
                        $new = $_user->getUserProjects()->contains($entity);
                        if (!$new) {
                            $_user->setUserProjects($entity);
                            $em->persist($_user);
                            $em->flush();
                        }
                    }
                }
            }

            $this->get('session')->getFlashBag()->add('success', 'Project added succesfully.');

            return $this->redirect($this->generateUrl('projects', array('id' => $entity->getId())));
        }
        $org_id = '';
        if (!in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles()) && $this->getUser()->getOrganizations()) {
            $org_id = $this->getUser()->getOrganizations()->getId();
        }
        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'organization' => $org_id
        );
    }

    /**
     * Creates a form to create a Projects entity.
     *
     * @param Projects $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Projects $entity) {
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
    public function newAction() {
        $session = $this->getRequest()->getSession();        
        if (($session->has('termsStatus') && $session->get('termsStatus') == 0) || ($session->has('limitExceed') && $session->get('limitExceed') == 0)) {
            return $this->redirect($this->generateUrl('dashboard'));
        }
        $entity = new Projects();
        $form = $this->createCreateForm($entity);
        $org_id = '';
        if (!in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles()) && $this->getUser()->getOrganizations()) {
            $org_id = $this->getUser()->getOrganizations()->getId();
        }
        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'organization' => $org_id
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
    public function showAction($id) {
        $session = $this->getRequest()->getSession();        
        if (($session->has('termsStatus') && $session->get('termsStatus') == 0) || ($session->has('limitExceed') && $session->get('limitExceed') == 0)) {
            return $this->redirect($this->generateUrl('dashboard'));
        }
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
    public function editAction($id) {
        $session = $this->getRequest()->getSession();        
        if (($session->has('termsStatus') && $session->get('termsStatus') == 0) || ($session->has('limitExceed') && $session->get('limitExceed') == 0)) {
            return $this->redirect($this->generateUrl('dashboard'));
        }
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:Projects')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Projects entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);
        $org_id = '';
        if (!in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles()) && $this->getUser()->getOrganizations()) {
            $org_id = $this->getUser()->getOrganizations()->getId();
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'organization' => $org_id
        );
    }

    /**
     * Creates a form to edit a Projects entity.
     *
     * @param Projects $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Projects $entity) {
        $em = $this->getDoctrine()->getManager();
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
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $entity = $em->getRepository('ApplicationFrontBundle:Projects')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Projects entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        $usersList = $request->request->get('application_bundle_frontbundle_projects');

        if ($editForm->isValid()) {

            $entity->setUsersUpdated($user);
            if ($entity->getOrganization()->getStatus() == 0) {
                $entity->setStatus(0);
            } else {
                $entity->setStatus(1);
            }
            if (isset($usersList['projectUsers'])) {
                $old_users = explode(',', $usersList['hidden_projectUsers']);
                $array_diff = array_diff($old_users, $usersList['projectUsers']);
                foreach ($usersList['projectUsers'] as $key => $users) {
                    $_user = $em->getRepository('ApplicationFrontBundle:Users')->find($users);
                    if ($_user) {
                        $checkUser = $_user->getUserProjects()->contains($entity);

                        if (!$checkUser) {
                            $_user->setUserProjects($entity);
                            $em->persist($_user);
                            $em->flush();
                        }
                    }
                }
                if (!empty($array_diff)) {
                    foreach ($array_diff as $key => $diff_id) {
                        $_user = $em->getRepository('ApplicationFrontBundle:Users')->find($diff_id);
                        if ($_user) {
                            $_user->removeUserProjects($entity);
                            $em->persist($_user);
                            $em->flush();
                        }
                    }
                }
            }
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Project updated succesfully.');

            return $this->redirect($this->generateUrl('projects'));
        }

        $org_id = '';
        if (!in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles()) && $this->getUser()->getOrganizations()) {
            $org_id = $this->getUser()->getOrganizations()->getId();
        }
        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'organization' => $org_id
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
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:Projects')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Projects entity.');
            }

            $records = $em->getRepository('ApplicationFrontBundle:Records')->findBy(array('project' => $id));
            foreach ($records as $record) {
                $shpinxInfo = $this->container->getParameter('sphinx_param');
                $sphinxSearch = new SphinxSearch($em, $shpinxInfo, $record->getId(), $record->getMediaType()->getId());
                $sphinxSearch->delete();
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
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('projects_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array('onclick' => "return confirm('Are you sure you want to delete selected project?')")))
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
    public function addRecordProjectAction($id) {
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
    public function addRecordAction(Request $request) {
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

    /**
     * Get users
     *
     * @param Request $request
     *
     * @Route("/get_user", name="get_users_of_org")
     * @Method("POST")
     * @Template()
     * @return template
     */
    public function getUsersAction(Request $request) {
        $new_users = array();
        $id = $request->request->get('organizationId');
        $selectedIds = $request->request->get('selectedIds');
        $users = '';
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('ApplicationFrontBundle:Users')->findBy(array('organizations' => $id));
        foreach ($users as $value) {
            if (!in_array("ROLE_SUPER_ADMIN", $value->getRoles()) && !in_array("ROLE_ADMIN", $value->getRoles()) && !in_array("ROLE_MANAGER", $value->getRoles())) {
                $new_users[] = $value;
            }
        }
        $selectedUserId = array();
        if ($selectedIds) {
            $selectedUserId = $selectedIds;
        }
        return $this->render('ApplicationFrontBundle:Projects:getUsers.html.php', array(
                    'users' => $new_users,
                    'selectedUserId' => $selectedUserId,
        ));
    }

    /**
     * Active/Inactive projects.
     *
     * @param integer $id User id
     * @param integer $status User status id
     * 
     * @Route("/changeprojectstatus/{id}/{status}", name="project_changestatus")
     * @Method("GET")
     * @Template()
     * @return redirection
     */
    public function changeStatusAction($id, $status) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationFrontBundle:Projects')->find($id);
        if ($status == 1) {
            $entity->setStatus(0);
            $users = $em->getRepository('ApplicationFrontBundle:Users')->findBy(array('organizations' => $id));

            $this->get('session')->getFlashBag()->add('success', 'Project disabled succesfully.');
        } else {
            $entity->setStatus(1);
            $this->get('session')->getFlashBag()->add('success', 'Project activated succesfully.');
        }
        $em->flush();
        return $this->redirect($this->generateUrl('projects'));
    }

}
