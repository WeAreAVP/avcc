<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\NoiceReduction;
use Application\Bundle\FrontBundle\Form\NoiceReductionType;

/**
 * NoiceReduction controller.
 *
 * @Route("/vocabularies/noicereduction")
 */
class NoiceReductionController extends Controller
{

    /**
     * Lists all NoiceReduction entities.
     *
     * @Route("/", name="vocabularies_noicereduction")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:NoiceReduction')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new NoiceReduction entity.
     *
     * @param Request $request
     * @param integer $id
     *
     * @Route("/", name="vocabularies_noicereduction_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:NoiceReduction:new.html.twig")
     * @return array
     */
    public function createAction(Request $request)
    {
        $entity = new NoiceReduction();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('vocabularies_noicereduction_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a NoiceReduction entity.
     *
     * @param NoiceReduction $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(NoiceReduction $entity)
    {
        $form = $this->createForm(new NoiceReductionType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_noicereduction_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new NoiceReduction entity.
     *
     * @Route("/new", name="vocabularies_noicereduction_new")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function newAction()
    {
        $entity = new NoiceReduction();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a NoiceReduction entity.
     *
     * @param integer $id
     *
     * @Route("/{id}", name="vocabularies_noicereduction_show")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:NoiceReduction')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NoiceReduction entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing NoiceReduction entity.
     *
     * @param integer $id
     *
     * @Route("/{id}/edit", name="vocabularies_noicereduction_edit")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:NoiceReduction')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NoiceReduction entity.');
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
    * Creates a form to edit a NoiceReduction entity.
    *
    * @param NoiceReduction $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(NoiceReduction $entity)
    {
        $form = $this->createForm(new NoiceReductionType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_noicereduction_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing NoiceReduction entity.
     *
     * @param Request $request
     * @param integer $id
     *
     * @Route("/{id}", name="vocabularies_noicereduction_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:NoiceReduction:edit.html.twig")
     * @return array
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:NoiceReduction')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NoiceReduction entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('vocabularies_noicereduction_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a NoiceReduction entity.
     *
     * @param Request $request
     * @param integer $id
     *
     * @Route("/{id}", name="vocabularies_noicereduction_delete")
     * @Method("DELETE")
     * @return redirect
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:NoiceReduction')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find NoiceReduction entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('vocabularies_noicereduction'));
    }

    /**
     * Creates a form to delete a NoiceReduction entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('vocabularies_noicereduction_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
