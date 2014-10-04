<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\Users;
use Application\Bundle\FrontBundle\Form\UsersType;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Users controller.
 *
 * @Route("/users")
 */
class UsersController extends Controller
{

    /**
     * Lists all Users entities.
     *
     * @Route("/", name="users")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:Users')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Users entity.
     *
     * @Route("/", name="users_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:Users:new.html.twig")
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * 
     * @return array/redirect to list page
     */
    public function createAction(Request $request)
    {
        $role_options = $this->getRoleHierarchy();
        $entity = new Users();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $form = $this->createCreateForm($entity, $role_options);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $entity->setEnabled(true);
            $entity->setUsersCreated($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('users'));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Users entity.
     *
     * @param \Application\Bundle\FrontBundle\Entity\Users $entity
     * @param array $rolesField
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Users $entity, $rolesField = array())
    {
        $form = $this->createForm(new UsersType($rolesField), $entity, array(
            'action' => $this->generateUrl('users_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Users entity.
     *
     * @Route("/new", name="users_new")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function newAction()
    {
        $role_options = $this->getRoleHierarchy();
        $entity = new Users();
        $form = $this->createCreateForm($entity, $role_options);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Users entity.
     *
     * @Route("/{id}", name="users_show")
     * @Method("GET")
     * @Template()
     * @param integer $id
     * @return array 
     * 
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:Users')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Users entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Users entity.
     *
     * @Route("/{id}/edit", name="users_edit")
     * @Method("GET")
     * @Template()
     * 
     * @param integer $id user id
     */
    public function editAction($id)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:Users')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Users entity.');
        }

        $roleOptions = $this->getRoleHierarchy();
        $roleOptions['user_role']  = $entity->getRoles();
        $editForm = $this->createEditForm($entity, $roleOptions);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Users entity.
     *
     * @param Users $entity The entity
     * @param array $rolesField roles array
     * 
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Users $entity, $rolesField = array())
    {
        $form = $this->createForm(new UsersType($rolesField), $entity, array(
            'action' => $this->generateUrl('users_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Users entity.
     *
     * @Route("/{id}", name="users_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:Users:edit.html.twig")
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param integer $id user id
     * 
     * @return type
     */
    public function updateAction(Request $request, $id)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationFrontBundle:Users')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Users entity.');
        }

        $role_options = $this->getRoleHierarchy();
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity, $role_options);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entity->setUsersUpdated($user);

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('users'));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Users entity.
     *
     * @Route("/{id}", name="users_delete")
     * @Method("DELETE")
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param integer $id
     * 
     * @return redirect to user list page
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:Users')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Users entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('users'));
    }

    /**
     * Creates a form to delete a Users entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('users_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm();
    }

    /**
     * Get roles form defined hierarchy in security.yml
     * 
     * @return roles array
     */
    private function getRoleHierarchy()
    {
        $rolesChoices = array();

        $roles = $this->container->getParameter('security.role_hierarchy.roles');
        foreach ($roles as $role => $inherited_roles) {
            foreach ($inherited_roles as $id => $inherited_role) {
                if (!array_key_exists($inherited_role, $rolesChoices)) {
                    $arrInheritedRoles = explode("_", $inherited_role);
                    array_shift($arrInheritedRoles);
                    $rInRoles = implode(" ", $arrInheritedRoles);
                    $rolesChoices[$inherited_role] = $rInRoles;
                }
            }

            if (!array_key_exists($role, $rolesChoices)) {
                $arrRoles = explode("_", $role);
                array_shift($arrRoles);

                $rrRoles = implode(" ", $arrRoles);
                $rolesChoices[$role] = $rrRoles;
            }
        }
        $roleOptions['role'] = $role;
        $roleOptions['roles'] = $rolesChoices;
        return $roleOptions;
    }

}
