<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\ReelDiameters;
use Application\Bundle\FrontBundle\Form\ReelDiametersType;

/**
 * ReelDiameters controller.
 *
 * @Route("/vocabularies/reeldiameters")
 */
class ReelDiametersController extends Controller
{

    /**
     * Lists all ReelDiameters entities.
     *
     * @Route("/", name="vocabularies_reeldiameters")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:ReelDiameters')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new ReelDiameters entity.
     *
     * @Route("/", name="vocabularies_reeldiameters_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:ReelDiameters:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new ReelDiameters();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $posted_value = $this->get('request')->request->get('application_bundle_frontbundle_reeldiameters');

            $em = $this->getDoctrine()->getManager();
            $f = $form->getData();
            foreach ($posted_value['reelFormat'] as $key => $value) {
                $entity = new ReelDiameters();
                $entity->setName($f->getName());
                $format = $this->getDoctrine()->getRepository('ApplicationFrontBundle:Formats')->find($value);
                $entity->setReelFormat($format);
                $em->persist($entity);
                $em->flush();
            }
            $this->get('session')->getFlashBag()->add('success', 'Reel diameter added succesfully.');

            return $this->redirect($this->generateUrl('vocabularies_reeldiameters'));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a ReelDiameters entity.
     *
     * @param ReelDiameters $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ReelDiameters $entity)
    {
        $form = $this->createForm(new ReelDiametersType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_reeldiameters_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ReelDiameters entity.
     *
     * @Route("/new", name="vocabularies_reeldiameters_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new ReelDiameters();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a ReelDiameters entity.
     *
     * @Route("/{id}", name="vocabularies_reeldiameters_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:ReelDiameters')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ReelDiameters entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ReelDiameters entity.
     *
     * @Route("/{id}/edit", name="vocabularies_reeldiameters_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:ReelDiameters')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ReelDiameters entity.');
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
     * Creates a form to edit a ReelDiameters entity.
     *
     * @param ReelDiameters $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(ReelDiameters $entity)
    {
        $form = $this->createForm(new ReelDiametersType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_reeldiameters_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing ReelDiameters entity.
     *
     * @Route("/{id}", name="vocabularies_reeldiameters_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:ReelDiameters:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:ReelDiameters')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ReelDiameters entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Reel diameter updated succesfully.');

            return $this->redirect($this->generateUrl('vocabularies_reeldiameters_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a ReelDiameters entity.
     *
     * @Route("/{id}", name="vocabularies_reeldiameters_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:ReelDiameters')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ReelDiameters entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('vocabularies_reeldiameters'));
    }

    /**
     * Creates a form to delete a ReelDiameters entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('vocabularies_reeldiameters_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

}
