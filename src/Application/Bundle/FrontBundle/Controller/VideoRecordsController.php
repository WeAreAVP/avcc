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
use Application\Bundle\FrontBundle\SphinxSearch\SphinxSearch;

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
            $em->persist($entity);
            $em->flush();
            $sphinxSearch = new SphinxSearch($em, $entity->getId(), 3);
            $sphinxSearch->insert();
            $this->get('session')->getFlashBag()->add('success', 'Video record added succesfully.');

            return $this->redirect($this->generateUrl('record_list'));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
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
     * @Route("/new/{projectId}", name="record_video_new_against_project")
     * @Route("/new/{videoRecId}/duplicate", name="record_video_duplicate")
     * @Method("GET")
     * @Template()
     * @return template
     */
    public function newAction($projectId = null, $videoRecId = null)
    {
        $em = $this->getDoctrine()->getManager();
        $fieldsObj = new DefaultFields();
        $data = $fieldsObj->getData(3, $em, $this->getUser(), $projectId);
        if ($videoRecId) {
            $entity = $em->getRepository('ApplicationFrontBundle:VideoRecords')->find($videoRecId);
        } else {
            $entity = new VideoRecords();
        }
        $form = $this->createCreateForm($entity, $em, $data);
        $userViewSettings = $fieldsObj->getFieldSettings($this->getUser(), $em);

        return $this->render('ApplicationFrontBundle:VideoRecords:new.html.php', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'fieldSettings' => $userViewSettings,
                    'type' => $data['mediaType']->getName(),
        ));
    }

    /**
     * Finds and displays a VideoRecords entity.
     *
     * @Route("/{id}", name="record_video_show")
     * @Method("GET")
     * @Template()
     * @return template
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:VideoRecords')->findOneBy(array('record'=>$id));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VideoRecords entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationFrontBundle:VideoRecords:show.html.php', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing VideoRecords entity.
     *
     * @Route("/{id}/edit", name="record_video_edit")
     * @Method("GET")
     * @Template()
     * @return template
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationFrontBundle:VideoRecords')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VideoRecords entity.');
        }
        $fieldsObj = new DefaultFields();
        $data = $fieldsObj->getData(3, $em, $this->getUser());
        $editForm = $this->createEditForm($entity, $em, $data);
        $deleteForm = $this->createDeleteForm($id);

        $userViewSettings = $fieldsObj->getFieldSettings($this->getUser(), $em);

        return $this->render('ApplicationFrontBundle:VideoRecords:edit.html.php', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
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
        $form->add('save_and_duplicate', 'submit', array('label' => 'Duplicate'));

        return $form;
    }

    /**
     * Edits an existing VideoRecords entity.
     *
     * @Route("/{id}", name="record_video_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:VideoRecords:edit.html.php")
     * @return redirect
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:VideoRecords')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VideoRecords entity.');
        }
        $fieldsObj = new DefaultFields();
        $data = $fieldsObj->getData(3, $em, $this->getUser());
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity, $em, $data);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $sphinxSearch = new SphinxSearch($em, $entity->getId(), 3);
            $sphinxSearch->replace();
            // the save_and_dupplicate button was clicked
            if ($editForm->get('save_and_duplicate')->isClicked()) {
                return $this->redirect($this->generateUrl('record_video_duplicate', array('videoRecId' => $id)));
            }
            $this->get('session')->getFlashBag()->add('success', 'Video record updated succesfully.');

            return $this->redirect($this->generateUrl('record_list'));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a VideoRecords entity.
     *
     * @Route("/{id}", name="record_video_delete")
     * @Method("DELETE")
     * @return redorect
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
                        ->getForm();
    }

}
