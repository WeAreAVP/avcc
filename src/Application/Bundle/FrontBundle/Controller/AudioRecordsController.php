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
use Application\Bundle\FrontBundle\SphinxSearch\SphinxSearch;
use Symfony\Component\Form\FormError;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * AudioRecords controller.
 *
 * @Route("/record")
 */
class AudioRecordsController extends Controller {

    /**
     * Lists all AudioRecords entities.
     *
     * @Route("/", name="record")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:Records')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new AudioRecords entity.
     *
     * @param Request $request
     *
     * @Route("/audio/", name="record_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:AudioRecords:new.html.php")
     * @return array
     */
    public function createAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $fieldsObj = new DefaultFields();
        $data = $fieldsObj->getData(1, $em, $this->getUser(), null);
        $entity = new AudioRecords();
        echo '<pre>';
        //  print_r($request);
        $unique = $request->request->get('uniqueId');
        //  echo 'checkk === ' . $unique;
        // exit;
        $form = $this->createCreateForm($entity, $em, $data);
        $form->handleRequest($request);
        $error = '';
        $result = $this->checkUniqueId($request);
        $recordForm = $form->get('record');
       print_r($recordForm->get('uniqueId'));
        //   echo $result;
        exit;
        if ($form->isValid()) {
            print_r($form->get('uniqueId'));

            $em->persist($entity);
            try {
                $em->flush();
                $shpinxInfo = $this->getSphinxInfo();
                $sphinxSearch = new SphinxSearch($em, $shpinxInfo, $entity->getRecord()->getId(), 1);
                $sphinxSearch->insert();
                $this->get('session')->getFlashBag()->add('success', 'Audio record added succesfully.');
                // the save_and_dupplicate button was clicked
                if ($form->get('save_and_duplicate')->isClicked()) {
                    return $this->redirect($this->generateUrl('record_audio_duplicate', array('audioRecId' => $entity->getId())));
                }
                if ($form->get('save_and_new')->isClicked()) {
                    return $this->redirect($this->generateUrl('record_new', array('audioRecId' => $entity->getId())));
                }
                return $this->redirect($this->generateUrl('record_list'));
            } catch (\Doctrine\DBAL\DBALException $e) {
                if (is_int(strpos($e->getPrevious()->getMessage(), "Column 'project_id' cannot be null"))) {
                    $error = new FormError("Project is required field.");
                    $recordForm = $form->get('record');
                    $recordForm->get('project')->addError($error);
                }
                if (is_int(strpos($e->getPrevious()->getMessage(), 'Duplicate entry'))) {
                    $error = new FormError("The unique ID must be unique.");
                    $recordForm = $form->get('record');
                    $recordForm->get('uniqueId')->addError($error);
                }
                if (is_int(strpos($e->getPrevious()->getMessage(), "Column 'format_id' cannot be null"))) {
                    $error = new FormError("Format is required field.");
                    $recordForm = $form->get('record');
                    $recordForm->get('format')->addError($error);
                }
            }
        }
        $userViewSettings = $fieldsObj->getFieldSettings($this->getUser(), $em);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'type' => $data['mediaType']->getName(),
            'fieldSettings' => $userViewSettings
        );
    }

    /**
     * Creates a form to create a AudioRecords entity.
     *
     * @param AudioRecords  $entity The entity
     * @param EntityManager $em
     * @param form          $data
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(AudioRecords $entity, $em, $data = null) {
        $form = $this->createForm(new AudioRecordsType($em, $data), $entity, array(
            'action' => $this->generateUrl('record_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Save'));
        $form->add('save_and_new', 'submit', array('label' => 'Save & New'));
        $form->add('save_and_duplicate', 'submit', array('label' => 'Save & Duplicate'));

        return $form;
    }

    /**
     * Displays a form to create a new AudioRecords entity.
     *
     * @param integer $projectId
     * @param integer $audioRecId
     *
     * @Route("/audio/new", name="record_new")
     * @Route("/audio/new/{projectId}", name="record_new_against_project")
     * @Route("/audio/new/{audioRecId}/duplicate", name="record_audio_duplicate")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function newAction($projectId = null, $audioRecId = null) {
        if (false === $this->get('security.context')->isGranted('ROLE_CATALOGER')) {
            throw new AccessDeniedException('Access Denied.');
        }
        $fieldsObj = new DefaultFields();
        $em = $this->getDoctrine()->getManager();
        $data = $fieldsObj->getData(1, $em, $this->getUser(), $projectId);
        if ($audioRecId) {
            $entity = $em->getRepository('ApplicationFrontBundle:AudioRecords')->find($audioRecId);
            $entity->getRecord()->setUniqueId(NULL);
            $entity->getRecord()->setLocation(NULL);
            $entity->getRecord()->setTitle(NULL);
            $entity->getRecord()->setDescription(NULL);
            $entity->getRecord()->setContentDuration(NULL);
            $entity->setMediaDuration(NULL);
            $entity->getRecord()->setCreationDate(NULL);
            $entity->getRecord()->setContentDate(NULL);
            $entity->getRecord()->setIsReview(NULL);
            $entity->setSlides(NULL);
            $entity->setTrackTypes(NULL);
            $entity->setMonoStereo(NULL);
            $entity->setNoiceReduction(NULL);
            $entity->getRecord()->setGenreTerms(NULL);
            $entity->getRecord()->setContributor(NULL);
            $entity->getRecord()->setGeneration(NULL);
            $entity->getRecord()->setPart(NULL);
            $entity->getRecord()->setDuplicatesDerivatives(NULL);
            $entity->getRecord()->setRelatedMaterial(NULL);
            $entity->getRecord()->setConditionNote(NULL);
        } else {
            $entity = new AudioRecords();
        }
        $form = $this->createCreateForm($entity, $em, $data);
        $userViewSettings = $fieldsObj->getFieldSettings($this->getUser(), $em);

        return $this->render('ApplicationFrontBundle:AudioRecords:new.html.php', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'fieldSettings' => $userViewSettings,
                    'type' => $data['mediaType']->getName(),
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
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:AudioRecords')->findOneBy(array('record' => $id));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AudioRecords entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $fieldsObj = new DefaultFields();
        $userViewSettings = $fieldsObj->getFieldSettings($this->getUser(), $em);

        return $this->render('ApplicationFrontBundle:AudioRecords:show.html.php', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),
                    'fieldSettings' => $userViewSettings
        ));
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
    public function editAction($id) {
        if (false === $this->get('security.context')->isGranted('ROLE_CATALOGER')) {
            throw new AccessDeniedException('Access Denied.');
        }
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:AudioRecords')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AudioRecords entity.');
        }
        $fieldsObj = new DefaultFields();
        $data = $fieldsObj->getData(1, $em, $this->getUser(), null, $entity->getRecord()->getId());
        $editForm = $this->createEditForm($entity, $em, $data);
        $deleteForm = $this->createDeleteForm($id);

        $userViewSettings = $fieldsObj->getFieldSettings($this->getUser(), $em);

        return $this->render('ApplicationFrontBundle:AudioRecords:edit.html.php', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                    'fieldSettings' => $userViewSettings,
                    'type' => $data['mediaType']->getName(),
        ));
    }

    /**
     * Creates a form to edit a AudioRecords entity.
     *
     * @param AudioRecords  $entity The entity
     * @param EntityManager $em
     * @param array         $data
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(AudioRecords $entity, $em, $data = null) {
        $form = $this->createForm(new AudioRecordsType($em, $data), $entity, array(
            'action' => $this->generateUrl('record_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Save'));
        $form->add('save_and_new', 'submit', array('label' => 'Save & New'));
        $form->add('save_and_duplicate', 'submit', array('label' => 'Save & Duplicate'));

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
     * @Template("ApplicationFrontBundle:AudioRecords:edit.html.php")
     * @return array
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:AudioRecords')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AudioRecords entity.');
        }
        $user = $this->getUser();
        $fieldsObj = new DefaultFields();
        $data = $fieldsObj->getData(1, $em, $user, null, $entity->getRecord()->getId());
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity, $em, $data);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            try {
                $em->flush();
                $shpinxInfo = $this->getSphinxInfo();
                $sphinxSearch = new SphinxSearch($em, $shpinxInfo, $entity->getRecord()->getId(), 1);
                $sphinxSearch->replace();

                // the save_and_dupplicate button was clicked
                if ($editForm->get('save_and_duplicate')->isClicked()) {
                    return $this->redirect($this->generateUrl('record_audio_duplicate', array('audioRecId' => $id)));
                }
                if ($editForm->get('save_and_new')->isClicked()) {
                    return $this->redirect($this->generateUrl('record_new', array('audioRecId' => $entity->getId())));
                }
                $this->get('session')->getFlashBag()->add('success', 'Audio record updated succesfully.');

                return $this->redirect($this->generateUrl('record_list'));
            } catch (\Doctrine\DBAL\DBALException $e) {
                if (is_int(strpos($e->getPrevious()->getMessage(), "Column 'project_id' cannot be null"))) {
                    $error = new FormError("Project is required field.");
                    $recordForm = $form->get('record');
                    $recordForm->get('project')->addError($error);
                }
//                if (is_int(strpos($e->getPrevious()->getMessage(), 'Duplicate entry'))) {
//                    $error = new FormError("The unique ID must be unique.");
//                    $recordForm = $editForm->get('record');
//                    $recordForm->get('uniqueId')->addError($error);
//                }
                if (is_int(strpos($e->getPrevious()->getMessage(), "Column 'format_id' cannot be null"))) {
                    $error = new FormError("Format is required field.");
                    $recordForm = $form->get('record');
                    $recordForm->get('format')->addError($error);
                }
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
     * Deletes a AudioRecords entity.
     *
     * @param Request $request
     * @param integer $id
     *
     * @Route("/{id}", name="record_delete")
     * @Method("DELETE")
     * @return redirect
     */
    public function deleteAction(Request $request, $id) {
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
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('record_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm();
    }

    /**
     * Displays a form to select media type abd projects.
     * @Route("/add-record", name="record_add")
     * @Method("GET")
     * @Template()
     * @return template
     */
    public function addRecordAction() {
        $em = $this->getDoctrine()->getManager();
        $projects = $em->getRepository('ApplicationFrontBundle:Projects')->findAll();
        $mediaTypes = $em->getRepository('ApplicationFrontBundle:MediaTypes')->findAll();

        return $this->render('ApplicationFrontBundle:AudioRecords:addRecord.html.twig', array(
                    'projects' => $projects,
                    'mediaTypes' => $mediaTypes
        ));
    }

    /**
     * Displays a base values in dropdown.
     *
     * @param integer $formatId Format id
     *
     * @Route("/getBase/{formatId}", name="record_get_base")
     * @Method("GET")
     * @Template()
     * @return template
     */
    public function getBaseAction($formatId) {
        $em = $this->getDoctrine()->getManager();
        $bases = $em->getRepository('ApplicationFrontBundle:Bases')->findBy(array('baseFormat' => $formatId));

        return $this->render('ApplicationFrontBundle:AudioRecords:getBase.html.php', array(
                    'bases' => $bases
        ));
    }

    /**
     * get recording speed values to show in dropdown.
     *
     * @param integer $formatId    Format id
     * @param integer $mediaTypeId
     *
     * @Route("/getRecordingSpeed/{formatId}/{mediaTypeId}", name="record_get_speed")
     * @Route("/getRecordingSpeed/{formatId}/{mediaTypeId}/{selectedrs}", name="record_get_speed")
     * @Method("GET")
     * @Template()
     * @return template
     */
    public function getRecordingSpeedAction($formatId, $mediaTypeId, $selectedrs = null) {
        $em = $this->getDoctrine()->getManager();
        if ($mediaTypeId == 3) {
            $speeds = $em->getRepository('ApplicationFrontBundle:RecordingSpeed')->findBy(array('recSpeedFormat' => NULL));
        } else {
            $speeds = $em->getRepository('ApplicationFrontBundle:RecordingSpeed')->findBy(array('recSpeedFormat' => $formatId));
        }

        return $this->render('ApplicationFrontBundle:AudioRecords:getRecordingSpeed.html.php', array(
                    'speeds' => $speeds,
                    'selectedrs' => $selectedrs
        ));
    }

    /**
     * get format values to show in dropdown.
     *
     * @param integer $mediaTypeId Media type id
     * @param integer $formatId
     *
     * @Route("/getFormat/{mediaTypeId}", name="record_get_format")
     * @Route("/getFormat/{mediaTypeId}/{formatId}", name="record_get_format_selected")
     * @Method("GET")
     * @Template()
     * @return template
     */
    public function getFormatAction($mediaTypeId, $formatId = null) {
        $em = $this->getDoctrine()->getManager();
        $formats = $em->getRepository('ApplicationFrontBundle:Formats')->findBy(array('mediaType' => $mediaTypeId));

        return $this->render('ApplicationFrontBundle:AudioRecords:getFormat.html.php', array(
                    'formats' => $formats,
                    'formatId' => $formatId
        ));
    }

    /**
     * get values to show in dropdown.
     *
     * @param integer $formatId Format id
     *
     * @Route("/getFormatVersion/{formatId}", name="record_get_formatversion")
     * @Route("/getFormatVersion/{formatId}/{versionId}", name="record_get_formatversion_version")
     * @Method("GET")
     * @Template()
     * @return template
     */
    public function getFormatVersionAction($formatId, $versionId = null) {
        $em = $this->getDoctrine()->getManager();
        $formatVersions = $em->getRepository('ApplicationFrontBundle:FormatVersions')->findBy(array('formatVersionFormat' => $formatId));

        return $this->render('ApplicationFrontBundle:AudioRecords:getFormatVersion.html.php', array(
                    'formatVersions' => $formatVersions,
                    'selectedVersion' => $versionId
        ));
    }

    /**
     * get reel diameters values to show in dropdown.
     *
     * @param integer $formatId    Format id
     * @param integer $mediaTypeId
     *
     * @Route("/getReelDiameter/{formatId}/{mediaTypeId}", name="record_get_reeldiameter")
     * @Route("/getReelDiameter/{formatId}/{mediaTypeId}/{selectedRD}", name="record_get_reeldiameter_selected")
     * @Method("GET")
     * @Template()
     * @return template
     */
    public function getReelDiameterAction($formatId, $mediaTypeId, $selectedRD = Null) {
        $em = $this->getDoctrine()->getManager();
        if ($mediaTypeId == 2) {
            $reeldiameters = $em->getRepository('ApplicationFrontBundle:ReelDiameters')->findBy(array('reelFormat' => NULL));
        } else {
            $reeldiameters = $em->getRepository('ApplicationFrontBundle:ReelDiameters')->findBy(array('reelFormat' => $formatId));
        }

        return $this->render('ApplicationFrontBundle:AudioRecords:getReelDiameter.html.php', array(
                    'reeldiameters' => $reeldiameters,
                    'selectedRD' => $selectedRD
        ));
    }

    /**
     * Get sphinx parameters
     *
     * @return array
     */
    protected function getSphinxInfo() {
        return $this->container->getParameter('sphinx_param');
    }

    /**
     * Get all project.
     *
     * @param int $selectedProjectId
     *
     * @Route("/getAllProjects", name="record_projects")
     * @Route("/getAllProjects/{selectedProjectId}", name="record_user_projects")
     * @Method("GET")
     * @Template()
     * @return type
     */
    public function getAllProjectsAction($selectedProjectId = null) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        if (!in_array("ROLE_SUPER_ADMIN", $user->getRoles()) && $user->getOrganizations()) {
            $projects = $em->getRepository('ApplicationFrontBundle:Projects')->findBy(array('organization' => $user->getOrganizations()->getId()));
        } else {
            $projects = $em->getRepository('ApplicationFrontBundle:Projects')->findAll();
        }

        return $this->render('ApplicationFrontBundle:AudioRecords:getProjects.html.php', array(
                    'projects' => $projects,
                    'selectedProjectId' => $selectedProjectId
        ));
    }

    public function checkUniqueId(Request $request) {
        echo 'here<pre>';
        echo $request->getMethod();
        $unique = $request->request->get('uniqueId');
        echo '1 === ' . $unique;
        echo '<br>';
        echo $this->getUser()->getOrganizations()->getId();

        $role = $this->getUser()->getRoles();
        //   if (!in_array("ROLE_SUPER_ADMIN", $user->getRoles())) {
        $em = $this->getDoctrine()->getManager();
        $records = $em->getRepository('ApplicationFrontBundle:Records')->findOrganizationUniqueidRecords($this->getUser()->getOrganizations()->getId(), $unique);
        if (count($records) == 0) {
            return '';
        } else {
            return 'unique id not unique';
        }

        //  }
    }

}
