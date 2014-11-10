<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\FilmRecords;
use Application\Bundle\FrontBundle\Form\FilmRecordsType;
use Application\Bundle\FrontBundle\Helper\DefaultFields as DefaultFields;

/**
 * FilmRecords controller.
 *
 * @Route("/record/film")
 */
class FilmRecordsController extends Controller
{

    /**
     * Lists all FilmRecords entities.
     *
     * @Route("/", name="record_film")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:FilmRecords')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    
    /**
     * Creates a new FilmRecords entity.
     *
     * @param Request $request
     * 
     * @Route("/", name="record_film_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:FilmRecords:new.html.php")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new FilmRecords();
        $form = $this->createCreateForm($entity,$em);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Film record added succesfully.');
            return $this->redirect($this->generateUrl('record'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a FilmRecords entity.
     *
     * @param FilmRecords $entity The entity
     * @param EntityManager $em
     * @param array $data
     * 
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(FilmRecords $entity, $em, $data = null)
    {
        $form = $this->createForm(new FilmRecordsType($em, $data), $entity, array(
            'action' => $this->generateUrl('record_film_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new FilmRecords entity.
     *
     * @Route("/new", name="record_film_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();
        $data['mediaTypeId'] = 2;
//        $data['projectId'] = $request->request->get('project');
        $data['userId'] = $this->getUser()->getId();
        $mediaTypes = $em->getRepository('ApplicationFrontBundle:MediaTypes')->findAll();

        foreach ($mediaTypes as $media) {
            $data['mediaTypesArr'][] = array($media->getId() => $media->getName());
        }

        $projects = $em->getRepository('ApplicationFrontBundle:Projects')->findAll();

        foreach ($projects as $project) {
            $data['projectsArr'][] = array($project->getId() => $project->getName());
        }

        $mediaType = $em->getRepository('ApplicationFrontBundle:MediaTypes')->findOneBy(array('id' => $data['mediaTypeId']));

        $entity = new FilmRecords();
        $form = $this->createCreateForm($entity, $em, $data);
        $f_obj = new DefaultFields();
        $user_view_settings = $f_obj->getFieldSettings($this->getUser(),$em);
        return $this->render('ApplicationFrontBundle:FilmRecords:new.html.php', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'fieldSettings' => $user_view_settings,
                    'type' => $mediaType->getName(),
        ));
        
    }

    /**
     * Finds and displays a FilmRecords entity.
     *
     * @param integer $id
     * 
     * @Route("/{id}", name="record_film_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:FilmRecords')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FilmRecords entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing FilmRecords entity.
     * 
     * @param integer $id
     * 
     * @Route("/{id}/edit", name="record_film_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:FilmRecords')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FilmRecords entity.');
        }
        $data = $this->getData();        
        $editForm = $this->createEditForm($entity, $em, $data);
        $deleteForm = $this->createDeleteForm($id);

        $fieldsObj = new DefaultFields();
        $userViewSettings = $fieldsObj->getFieldSettings($this->getUser(), $em);
        
        return $this->render('ApplicationFrontBundle:FilmRecords:edit.html.php',array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'fieldSettings' => $userViewSettings,
            'type' => $data['mediaType']->getName(),
        ));
    }

    /**
    * Creates a form to edit a FilmRecords entity.
    *
    * @param FilmRecords $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(FilmRecords $entity, $em, $data = null)
    {
        $form = $this->createForm(new FilmRecordsType($em, $data), $entity, array(
            'action' => $this->generateUrl('record_film_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing FilmRecords entity.
     * 
     * @param Request $request
     * @param type $id
     * 
     * @Route("/{id}", name="record_film_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:FilmRecords:edit.html.php")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:FilmRecords')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FilmRecords entity.');
        }
        $data = $this->getData();
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity, $em, $data);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Film record updated succesfully.');
            return $this->redirect($this->generateUrl('record'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a FilmRecords entity.
     * 
     * @param Request $request
     * @param integer $id
     * 
     * @Route("/{id}", name="record_film_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:FilmRecords')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FilmRecords entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('record_film'));
    }

    /**
     * Creates a form to delete a FilmRecords entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('record_film_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
    
    private function getData(){
        $em = $this->getDoctrine()->getManager();
        $data['mediaTypeId'] = 2;
        $data['userId'] = $this->getUser()->getId();
        $mediaTypes = $em->getRepository('ApplicationFrontBundle:MediaTypes')->findAll();

        foreach ($mediaTypes as $media) {
            $data['mediaTypesArr'][] = array($media->getId() => $media->getName());
        }

        $projects = $em->getRepository('ApplicationFrontBundle:Projects')->findAll();

        foreach ($projects as $project) {
            $data['projectsArr'][] = array($project->getId() => $project->getName());
        }

        $data['mediaType'] = $em->getRepository('ApplicationFrontBundle:MediaTypes')->findOneBy(array('id' => $data['mediaTypeId']));

        return $data;
    }
}
