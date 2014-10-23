<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\PrintTypes;
use Application\Bundle\FrontBundle\Form\PrintTypesType;

/**
 * PrintTypes controller.
 *
 * @Route("/vocabularies/printtypes")
 */
class PrintTypesController extends Controller
{

    /**
     * Lists all PrintTypes entities.
     *
     * @Route("/", name="vocabularies_printtypes")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:PrintTypes')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new PrintTypes entity.
     *
     * @param Request $request
     * @param integer $id
     *
     * @Route("/", name="vocabularies_printtypes_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:PrintTypes:new.html.twig")
     * @return array
     */
    public function createAction(Request $request)
    {
        $entity = new PrintTypes();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('vocabularies_printtypes_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a PrintTypes entity.
     *
     * @param PrintTypes $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(PrintTypes $entity)
    {
        $form = $this->createForm(new PrintTypesType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_printtypes_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new PrintTypes entity.
     *
     * @Route("/new", name="vocabularies_printtypes_new")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function newAction()
    {
        $entity = new PrintTypes();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a PrintTypes entity.
     *
     * @param integer $id
     *
     * @Route("/{id}", name="vocabularies_printtypes_show")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:PrintTypes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PrintTypes entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing PrintTypes entity.
     *
     * @param integer $id
     *
     * @Route("/{id}/edit", name="vocabularies_printtypes_edit")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:PrintTypes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PrintTypes entity.');
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
    * Creates a form to edit a PrintTypes entity.
    *
    * @param PrintTypes $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(PrintTypes $entity)
    {
        $form = $this->createForm(new PrintTypesType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_printtypes_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing PrintTypes entity.
     *
     * @param Request $request
     * @param integer $id
     *
     * @Route("/{id}", name="vocabularies_printtypes_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:PrintTypes:edit.html.twig")
     * @return array
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:PrintTypes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PrintTypes entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('vocabularies_printtypes_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a PrintTypes entity.
     *
     * @param Request $request
     * @param integer $id
     *
     * @Route("/{id}", name="vocabularies_printtypes_delete")
     * @Method("DELETE")
     * @return array
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:PrintTypes')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find PrintTypes entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('vocabularies_printtypes'));
    }

    /**
     * Creates a form to delete a PrintTypes entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('vocabularies_printtypes_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
