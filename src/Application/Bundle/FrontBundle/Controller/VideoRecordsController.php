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
use Symfony\Component\Form\FormError;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * VideoRecords controller.
 *
 * @Route("/record")
 */
class VideoRecordsController extends Controller {

    /**
     * Lists all VideoRecords entities.
     *
     * @Route("/", name="record_video")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:VideoRecords')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new VideoRecords entity.
     *
     * @Route("/video/", name="record_video_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:VideoRecords:new.html.php")
     */
    public function createAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $entity = new VideoRecords();
        $form = $this->createCreateForm($entity, $em);
        $form->handleRequest($request);
        $error = '';
        $result = $this->checkUniqueId($request);
        if ($result != '') {
            $error = new FormError("The unique ID must be unique.");
            $recordForm = $form->get('record');
            $recordForm->get('uniqueId')->addError($error);
        }
        $fieldsObj = new DefaultFields();
        $data = $fieldsObj->getData(3, $em, $this->getUser(), null);
        if ($form->isValid()) {
            $em->persist($entity);
            try {
                $em->flush();
                $sphinxInfo = $this->getSphinxInfo();
                $sphinxSearch = new SphinxSearch($em, $sphinxInfo, $entity->getRecord()->getId(), 3);
                $sphinxSearch->insert();
                // the save_and_dupplicate button was clicked
                if ($form->get('save_and_duplicate')->isClicked()) {
                    return $this->redirect($this->generateUrl('record_video_duplicate', array('videoRecId' => $entity->getId())));
                }
                if ($form->get('save_and_new')->isClicked()) {
                    return $this->redirect($this->generateUrl('record_video_new'));
                }
                $this->get('session')->getFlashBag()->add('success', 'Video record added succesfully.');

                return $this->redirect($this->generateUrl('record_list'));
            } catch (\Doctrine\DBAL\DBALException $e) {
//                if (is_int(strpos($e->getPrevious()->getMessage(), "Column 'project_id' cannot be null"))) {
//                    $error = new FormError("Project is required field.");
//                    $recordForm = $form->get('record');
//                    $recordForm->get('project')->addError($error);
//                }
//                if (is_int(strpos($e->getPrevious()->getMessage(), 'Duplicate entry'))) {
//                    $error = new FormError("The unique ID must be unique.");
//                    $recordForm = $form->get('record');
//                    $recordForm->get('uniqueId')->addError($error);
//                }
//                if (is_int(strpos($e->getPrevious()->getMessage(), "Column 'format_id' cannot be null"))) {
//                    $error = new FormError("Format is required field.");
//                    $recordForm = $form->get('record');
//                    $recordForm->get('format')->addError($error);
//                }
            }
        }
        $user_view_settings = $fieldsObj->getFieldSettings($this->getUser(), $em);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'fieldSettings' => $user_view_settings,
            'type' => $data['mediaType']->getName(),
        );
    }

    /**
     * Creates a form to create a VideoRecords entity.
     *
     * @param VideoRecords $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(VideoRecords $entity, $em, $data = null) {
        $form = $this->createForm(new VideoRecordsType($em, $data), $entity, array(
            'action' => $this->generateUrl('record_video_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Save'));
        $form->add('save_and_new', 'submit', array('label' => 'Save & New'));
        $form->add('save_and_duplicate', 'submit', array('label' => 'Save & Duplicate'));

        return $form;
    }

    /**
     * Displays a form to create a new VideoRecords entity.
     *
     * @Route("/video/new", name="record_video_new")
     * @Route("/video/new/{projectId}", name="record_video_new_against_project")
     * @Route("/video/new/{videoRecId}/duplicate", name="record_video_duplicate")
     * @Method("GET")
     * @Template()
     * @return template
     */
    public function newAction($projectId = null, $videoRecId = null) {
        if (false === $this->get('security.context')->isGranted('ROLE_CATALOGER')) {
            throw new AccessDeniedException('Access Denied.');
        }
        $em = $this->getDoctrine()->getManager();
        $fieldsObj = new DefaultFields();
        $data = $fieldsObj->getData(3, $em, $this->getUser(), $projectId);
        if ($videoRecId) {
            $entity = $em->getRepository('ApplicationFrontBundle:VideoRecords')->find($videoRecId);
            $entity->getRecord()->setUniqueId(NULL);
            $entity->getRecord()->setLocation(NULL);
            $entity->getRecord()->setTitle(NULL);
            $entity->getRecord()->setDescription(NULL);
            $entity->getRecord()->setContentDuration(NULL);
            $entity->setFormatVersion(NULL);
            $entity->getRecord()->setCreationDate(NULL);
            $entity->getRecord()->setContentDate(NULL);
            $entity->getRecord()->setIsReview(NULL);
            $entity->setMediaDuration(NULL);
            $entity->getRecord()->setGenreTerms(NULL);
            $entity->getRecord()->setContributor(NULL);
            $entity->getRecord()->setGeneration(NULL);
            $entity->getRecord()->setPart(NULL);
            $entity->getRecord()->setDuplicatesDerivatives(NULL);
            $entity->getRecord()->setRelatedMaterial(NULL);
            $entity->getRecord()->setConditionNote(NULL);
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
     * Displays a form to edit an existing VideoRecords entity.
     *
     * @Route("/video/{id}/edit", name="record_video_edit")
     * @Method("GET")
     * @Template()
     * @return template
     */
    public function editAction($id) {
        if (false === $this->get('security.context')->isGranted('ROLE_CATALOGER')) {
            throw new AccessDeniedException('Access Denied.');
        }
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationFrontBundle:VideoRecords')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VideoRecords entity.');
        }
        $fieldsObj = new DefaultFields();
        $data = $fieldsObj->getData(3, $em, $this->getUser(), null, $entity->getRecord()->getId());
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
    private function createEditForm(VideoRecords $entity, $em, $data = null) {
        $form = $this->createForm(new VideoRecordsType($em, $data), $entity, array(
            'action' => $this->generateUrl('record_video_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Save'));
        $form->add('save_and_new', 'submit', array('label' => 'Save & New'));
        $form->add('save_and_duplicate', 'submit', array('label' => 'Save & Duplicate'));

        return $form;
    }

    /**
     * Edits an existing VideoRecords entity.
     *
     * @Route("/video/{id}", name="record_video_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:VideoRecords:edit.html.php")
     * @return redirect
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:VideoRecords')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VideoRecords entity.');
        }
        $fieldsObj = new DefaultFields();
        $data = $fieldsObj->getData(3, $em, $this->getUser(), null, $entity->getRecord()->getId());
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity, $em, $data);
        $editForm->handleRequest($request);
        $result = $this->checkUniqueId($request, $entity->getRecord()->getId());
        if ($result != '') {
            $error = new FormError("The unique ID must be unique.");
            $recordForm = $editForm->get('record');
            $recordForm->get('uniqueId')->addError($error);
        }
        if ($editForm->isValid()) {
            try {
                $em->flush();
                $sphinxInfo = $this->getSphinxInfo();
                $sphinxSearch = new SphinxSearch($em, $sphinxInfo, $entity->getRecord()->getId(), 3);
                $sphinxSearch->replace();
                // the save_and_dupplicate button was clicked
                if ($editForm->get('save_and_duplicate')->isClicked()) {
                    return $this->redirect($this->generateUrl('record_video_duplicate', array('videoRecId' => $id)));
                }
                if ($editForm->get('save_and_new')->isClicked()) {
                    return $this->redirect($this->generateUrl('record_video_new'));
                }
                $this->get('session')->getFlashBag()->add('success', 'Video record updated succesfully.');

                return $this->redirect($this->generateUrl('record_list'));
            } catch (\Doctrine\DBAL\DBALException $e) {
//                if (is_int(strpos($e->getPrevious()->getMessage(), "Column 'project_id' cannot be null"))) {
//                    $error = new FormError("Project is required field.");
//                    $recordForm = $form->get('record');
//                    $recordForm->get('project')->addError($error);
//                }
//                if (is_int(strpos($e->getPrevious()->getMessage(), 'Duplicate entry'))) {
//                    $error = new FormError("The unique ID must be unique.");
//                    $recordForm = $editForm->get('record');
//                    $recordForm->get('uniqueId')->addError($error);
//                }
//                if (is_int(strpos($e->getPrevious()->getMessage(), "Column 'format_id' cannot be null"))) {
//                    $error = new FormError("Format is required field.");
//                    $recordForm = $form->get('record');
//                    $recordForm->get('format')->addError($error);
//                }
            }
        }
        $userViewSettings = $fieldsObj->getFieldSettings($this->getUser(), $em);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'fieldSettings' => $userViewSettings,
            'type' => $data['mediaType']->getName(),
        );
    }

    /**
     * Deletes a VideoRecords entity.
     *
     * @Route("/video/{id}", name="record_video_delete")
     * @Method("DELETE")
     * @return redorect
     */
    public function deleteAction(Request $request, $id) {
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
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('record_video_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm();
    }

    /**
     * Get sphinx parameters
     *
     * @return array
     */
    protected function getSphinxInfo() {
        return $this->container->getParameter('sphinx_param');
    }

    public function checkUniqueId(Request $request, $id = 0) {
        $em = $this->getDoctrine()->getManager();
        $record = $request->request->get('application_bundle_frontbundle_videorecords');
        $unique = $record['record']['uniqueId'];
        $project_id = $record['record']['project'];
        if (empty($project_id) || $project_id == '') {
            return '';
        }
        $user = $em->getRepository('ApplicationFrontBundle:Records')->findOneBy(array('project' => $project_id));
        $records = $em->getRepository('ApplicationFrontBundle:Records')->findOrganizationUniqueRecords($user->getUser()->getOrganizations()->getId(), $unique, $id);
        if (count($records) == 0) {
            return '';
        } else {
            return 'unique id not unique';
        }
    }

}
