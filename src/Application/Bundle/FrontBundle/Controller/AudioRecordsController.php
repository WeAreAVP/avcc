<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\AudioRecords;
use Application\Bundle\FrontBundle\Form\AudioRecordsType;
use Application\Bundle\FrontBundle\Helper\DefaultFields as DefaultFields;

/**
 * AudioRecords controller.
 *
 * @Route("/record")
 */
class AudioRecordsController extends Controller
{

    /**
     * Lists all AudioRecords entities.
     *
     * @Route("/", name="record")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:AudioRecords')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new AudioRecords entity.
     *
     * @param Request $request
     *
     * @Route("/", name="record_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:AudioRecords:new.html.twig")
     * @return array
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new AudioRecords();
        $data = $this->getRelatedInfo($em, $request);
        echo '<pre>';print_r($data);
        $form = $this->createCreateForm($entity, $em , $data);
        $form->handleRequest($request);
        $frmData = $form->getData();
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('record_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a AudioRecords entity.
     *
     * @param AudioRecords $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(AudioRecords $entity, $em, $data = null)
    {
        $form = $this->createForm(new AudioRecordsType($em, $data), $entity, array(
            'action' => $this->generateUrl('record_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new AudioRecords entity.
     * 
     * @param Request $request
     * 
     * @Route("/new", name="record_new")
     * @Method("POST")
     * @Template()
     * @return array
     */
    public function newAction(Request $request)
    {
        $mediaTypeId = $request->request->get('mediaType');
        $em = $this->getDoctrine()->getManager();
        $mediaType = $em->getRepository('ApplicationFrontBundle:MediaTypes')->findOneBy(array('id' => $mediaTypeId));
        $data = $this->getRelatedInfo($em,$request);
        
        $entity = new AudioRecords();
        $form = $this->createCreateForm($entity, $em, $data);
        $user_view_settings = $this->getFieldSettings();
        return $this->render('ApplicationFrontBundle:AudioRecords:new.html.php', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'fieldSettings' => $user_view_settings,
                    'type' => $mediaType->getName(),
        ));
    }

    /**
     * Finds and displays a AudioRecords entity.
     *
     * @param integer $id
     *
     * @Route("/{id}", name="record_show")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:AudioRecords')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AudioRecords entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing AudioRecords entity.
     *
     * @param integer $id
     *
     * @Route("/{id}/edit", name="record_edit")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:AudioRecords')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AudioRecords entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);
        $user_view_settings = $this->getFieldSettings();
        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'fieldSettings' => $user_view_settings,
        );
    }

    /**
     * Creates a form to edit a AudioRecords entity.
     *
     * @param AudioRecords $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(AudioRecords $entity)
    {
        $form = $this->createForm(new AudioRecordsType(), $entity, array(
            'action' => $this->generateUrl('record_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing AudioRecords entity.
     *
     * @param Request $request
     * @param type    $id
     *
     * @Route("/{id}", name="record_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:AudioRecords:edit.html.twig")
     * @return array
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:AudioRecords')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AudioRecords entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('record_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a AudioRecords entity.
     *
     * @param Request $request
     * @param integer $id
     *
     * @Route("/{id}", name="record_delete")
     * @Method("DELETE")
     * @return redirect
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:AudioRecords')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find AudioRecords entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('record'));
    }

    /**
     * Creates a form to delete a AudioRecords entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('record_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm();
    }

    /**
     *  Get Field settings
     */
    private function getFieldSettings()
    {
        $em = $this->getDoctrine()->getManager();
        $settings = $em->getRepository('ApplicationFrontBundle:UserSettings')->findOneBy(array('user' => $this->getUser()->getId()));
        if ($settings) {
            $view_settings = $settings->getViewSetting();
        } else {
            $f_obj = new DefaultFields();
            $view_settings = $f_obj->getDefaultOrder();
        }
        $user_view_settings = json_decode($view_settings, true);
        return $user_view_settings;
    }

    /**
     * Displays a form to select media type and projects.
     *
     * @param integer $id
     *
     * @Route("/add/{id}", name="record_add_project")
     * @Method("GET")
     * @Template()
     */
    public function addRecordProjectAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $projects = $em->getRepository('ApplicationFrontBundle:Projects')->findAll();
        $mediaTypes = $em->getRepository('ApplicationFrontBundle:MediaTypes')->findAll();

        return $this->render('ApplicationFrontBundle:AudioRecords:addRecord.html.twig', array(
                    'projects' => $projects,
                    'project_id' => $id,
                    'mediaTypes' => $mediaTypes
        ));
    }

    /**
     * Displays a form to select media type abd projects.
     * @Route("/add-record", name="record_add")
     * @Method("GET")
     * @Template()
     */
    public function addRecordAction()
    {
        $em = $this->getDoctrine()->getManager();
        $projects = $em->getRepository('ApplicationFrontBundle:Projects')->findAll();
        $mediaTypes = $em->getRepository('ApplicationFrontBundle:MediaTypes')->findAll();

        return $this->render('ApplicationFrontBundle:AudioRecords:addRecord.html.twig', array(
                    'projects' => $projects,
                    'mediaTypes' => $mediaTypes
        ));
    }

    private function getRelatedInfo($em, $request)
    {
        $data['mediaTypeId'] = $request->request->get('mediaType');
        $data['projectId'] = $request->request->get('project');

        $mediaTypes = $em->getRepository('ApplicationFrontBundle:MediaTypes')->findAll();

        foreach ($mediaTypes as $media) {
            $data['mediaTypesArr'][] = array($media->getId() => $media->getName());
        }

        $projects = $em->getRepository('ApplicationFrontBundle:Projects')->findAll();

        foreach ($projects as $project) {
            $data['projectsArr'][] = array($project->getId() => $project->getName());
        }
        return $data;
    }

}
