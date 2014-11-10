<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\VideoRecords;
use Application\Bundle\FrontBundle\Form\VideoRecordsType;
use Application\Bundle\FrontBundle\Helper\DefaultFields as DefaultFields;

/**
 * VideoRecords controller.
 *
 * @Route("/record/video")
 */
class VideoRecordsController extends Controller
{

    /**
     * Lists all VideoRecords entities.
     *
     * @Route("/", name="record_video")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:VideoRecords')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new VideoRecords entity.
     *
     * @Route("/", name="record_video_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:VideoRecords:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new VideoRecords();
        $form = $this->createCreateForm($entity, $em);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Video record added succesfully.');
            return $this->redirect($this->generateUrl('record'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a VideoRecords entity.
     *
     * @param VideoRecords $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(VideoRecords $entity, $em, $data = null)
    {
        $form = $this->createForm(new VideoRecordsType($em, $data), $entity, array(
            'action' => $this->generateUrl('record_video_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new VideoRecords entity.
     *
     * @Route("/new", name="record_video_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $data['mediaTypeId'] = 3;
        $data['projectId'] = $request->request->get('project');
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

        $entity = new VideoRecords();
        $form = $this->createCreateForm($entity, $em, $data);
        $f_obj = new DefaultFields();
        $user_view_settings = $f_obj->getFieldSettings($this->getUser(),$em);
        return $this->render('ApplicationFrontBundle:VideoRecords:new.html.php', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'fieldSettings' => $user_view_settings,
                    'type' => $mediaType->getName(),
        ));
    }

    /**
     * Finds and displays a VideoRecords entity.
     *
     * @Route("/{id}", name="record_video_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:VideoRecords')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VideoRecords entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing VideoRecords entity.
     *
     * @Route("/{id}/edit", name="record_video_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:VideoRecords')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VideoRecords entity.');
        }
        $data = $this->getData();        
        $editForm = $this->createEditForm($entity, $em, $data);
        $deleteForm = $this->createDeleteForm($id);

        $fieldsObj = new DefaultFields();
        $userViewSettings = $fieldsObj->getFieldSettings($this->getUser(), $em);
        
        return $this->render('ApplicationFrontBundle:VideoRecords:edit.html.php',array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'fieldSettings' => $userViewSettings,
            'type' => $data['mediaType']->getName(),
        ));
    }

    /**
    * Creates a form to edit a VideoRecords entity.
    *
    * @param VideoRecords $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(VideoRecords $entity, $em, $data = null)
    {
        $form = $this->createForm(new VideoRecordsType($em, $data), $entity, array(
            'action' => $this->generateUrl('record_video_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing VideoRecords entity.
     *
     * @Route("/{id}", name="record_video_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:VideoRecords:edit.html.php")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:VideoRecords')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VideoRecords entity.');
        }
        $data = $this->getData();
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity, $em, $data);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Video record updated succesfully.');
            return $this->redirect($this->generateUrl('record'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a VideoRecords entity.
     *
     * @Route("/{id}", name="record_video_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:VideoRecords')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find VideoRecords entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('record_video'));
    }

    /**
     * Creates a form to delete a VideoRecords entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('record_video_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    private function getData(){
        $em = $this->getDoctrine()->getManager();
        $data['mediaTypeId'] = 3;
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
