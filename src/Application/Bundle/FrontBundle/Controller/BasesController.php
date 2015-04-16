<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\Bases;
use Application\Bundle\FrontBundle\Form\BasesType;

/**
 * Bases controller.
 *
 * @Route("/vocabularies/bases")
 */
class BasesController extends Controller
{

    /**
     * Lists all Bases entities.
     *
     * @Route("/", name="vocabularies_bases")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:Bases')->findBy(array(), array('order' => 'ASC'));

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Bases entity.
     *
     * @param Request $request
     *
     * @Route("/", name="vocabularies_bases_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:Bases:new.html.twig")
     * @return array
     */
    public function createAction(Request $request)
    {
        $entity = new Bases();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $postedValue = $this->get('request')->request->get('application_bundle_frontbundle_bases');

            $em = $this->getDoctrine()->getManager();
            $f = $form->getData();
            foreach ($postedValue['baseFormat'] as $key => $value) {
                $entity = new Bases();
                $entity->setName($f->getName());
                $format = $this->getDoctrine()->getRepository('ApplicationFrontBundle:Formats')->find($value);
                $entity->setBaseFormat($format);
                $em->persist($entity);
                $em->flush();
            }
            $this->get('session')->getFlashBag()->add('success', 'Base added succesfully.');

            return $this->redirect($this->generateUrl('vocabularies_bases'));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Bases entity.
     *
     * @param Bases $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Bases $entity)
    {
        $form = $this->createForm(new BasesType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_bases_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Bases entity.
     *
     * @Route("/new", name="vocabularies_bases_new")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function newAction()
    {
        $entity = new Bases();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Bases entity.
     *
     * @param integer $id
     *
     * @Route("/{id}", name="vocabularies_bases_show")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:Bases')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Bases entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Bases entity.
     *
     * @param integer $id
     *
     * @Route("/{id}/edit", name="vocabularies_bases_edit")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:Bases')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Bases entity.');
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
     * Creates a form to edit a Bases entity.
     *
     * @param Bases $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Bases $entity)
    {
        $form = $this->createForm(new BasesType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_bases_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Bases entity.
     *
     * @param Request $request
     * @param type    $id
     *
     * @Route("/{id}", name="vocabularies_bases_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:Bases:edit.html.twig")
     * @return array
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:Bases')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Bases entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Base updated succesfully.');

            return $this->redirect($this->generateUrl('vocabularies_bases_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Bases entity.
     *
     * @param Request $request
     * @param type    $id
     *
     * @Route("/{id}", name="vocabularies_bases_delete")
     * @Method("DELETE")
     * @return redirect
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:Bases')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Bases entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('vocabularies_bases'));
    }

    /**
     * Creates a form to delete a Bases entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('vocabularies_bases_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm();
    }
    
    /**
     * update field order
     *
     * @param Request $request
     *
     * @Route("/fieldOrder", name="bases_update_order")
     * @Method("POST")
     * @return array
     */
    public function updateFieldOrder(Request $request) {
        // code to update
        $colorIds = $this->get('request')->request->get('bases_ids');
        $count = 0;
        
        foreach ($colorIds as $color) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:Bases')->find($color);
            if ($entity) {
                $entity->setOrder($count);
               // $em->persist($entity);
                $em->flush();
                $count = $count + 1;
            }
        }
        echo json_encode(array('success' => 'true'));
        exit;
    }

}
