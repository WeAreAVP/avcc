<?php

/**
 * AVCC
 * 
 * @category AVCC
 * @package  Application
 * @author   Nouman Tayyab <nouman@weareavp.com>
 * @author   Rimsha Khalid <rimsha@weareavp.com>
 * @license  AGPLv3 http://www.gnu.org/licenses/agpl-3.0.txt
 * @copyright Audio Visual Preservation Solutions, Inc
 * @link     http://avcc.weareavp.com
 */

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
use Application\Bundle\FrontBundle\Entity\Projects;
use Application\Bundle\FrontBundle\Controller\MyController;

/**
 * VideoRecords controller.
 *
 * @Route("/record")
 */
class VideoRecordsController extends MyController {

    /**
     * Lists all VideoRecords entities.
     *
     * @Route("/", name="record_video")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $session = $this->getRequest()->getSession();
        if (($session->has('termsStatus') && $session->get('termsStatus') == 0) || ($session->has('limitExceed') && $session->get('limitExceed') == 0)) {
            return $this->redirect($this->generateUrl('dashboard'));
        }
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
            $form->get('record')->get('uniqueId')->addError($error);
        }
        $fieldsObj = new DefaultFields();
        $data = $fieldsObj->getData(3, $em, $this->getUser(), null);
        if ($form->isValid()) {
            $em->persist($entity);
            try {
                $em->flush();
                if (!empty($request->files->get('files'))) {
                    $this->get('application_front.photo_uploader')->upload($request->files->get('files'), $entity->getRecord()->getId());
                }
                $sphinxInfo = $this->getSphinxInfo();
                $sphinxSearch = new SphinxSearch($em, $sphinxInfo, $entity->getRecord()->getId(), 3);
                $sphinxSearch->insert();
                if (!in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles()) && $this->getUser()->getOrganizations() && ($form->get('save_and_duplicate')->isClicked() || $form->get('save_and_new')->isClicked()) && $this->container->getParameter("enable_stripe")) {
                    $paidOrg = $fieldsObj->paidOrganizations($this->getUser()->getOrganizations()->getId(), $em);
                    if ($paidOrg || is_array($paidOrg)) {
                        $org_records = $em->getRepository('ApplicationFrontBundle:Records')->countOrganizationRecords($this->getUser()->getOrganizations()->getId());
                        $counter = $org_records['total'];
                        $plan_limit = 2500;
                        $plan_id = "";
                        $creator = $this->getUser()->getOrganizations()->getUsersCreated();
                        if (in_array("ROLE_ADMIN", $creator->getRoles())) {
                            $plan_id = $creator->getStripePlanId();
                        }
                        if ($plan_id != NULL && $plan_id != "") {
                            $plan = $em->getRepository('ApplicationFrontBundle:Plans')->findBy(array("planId" => $plan_id));
                            $plan_limit = $plan[0]->getRecords();
                        }
                        if ($counter == $plan_limit) {
                            return $this->redirect($this->generateUrl('record_list_withdialog', array('dialog' => 1)));
                        }
                    }
                }
                // the save_and_dupplicate button was clicked
                if ($form->get('save_and_duplicate')->isClicked()) {
                    return $this->redirect($this->generateUrl('record_video_duplicate', array('videoRecId' => $entity->getId())));
                }
                if ($form->get('save_and_new')->isClicked()) {
                    return $this->redirect($this->generateUrl('record_video_new'));
                }
                $this->get('session')->getFlashBag()->add('success', 'Video record added succesfully.');
                $this->get('session')->set('vedioProjectId', $entity->getRecord()->getProject()->getId());

                return $this->redirect($this->generateUrl('record_list'));
            } catch (\Doctrine\DBAL\DBALException $e) {
                
            }
        }
        $allowed_upload = "";
        if ($this->get('session')->get('vedioProjectId')) {
            $projectId = $this->get('session')->get('vedioProjectId');
        } else if ($entity->getRecord()->getProject()->getId()) {
            $projectId = $entity->getRecord()->getProject()->getId();
        }
        if ($projectId) {
            $allowed_upload = true;
            $project = $em->getRepository('ApplicationFrontBundle:Projects')->findOneBy(array('id' => $projectId));
            if ($project->getViewSetting() != null) {
                $defSettings = $fieldsObj->getDefaultOrder();
                $dbSettings = $project->getViewSetting();
                $userViewSettings = $fieldsObj->fields_cmp(json_decode($defSettings, true), json_decode($dbSettings, true));
            } else {
                $userViewSettings = $fieldsObj->getDefaultOrder();
            }
            $organization = $em->getRepository('ApplicationFrontBundle:Organizations')->find($project->getOrganization()->getId());
            $creator = $organization->getUsersCreated();
            $customerId = $creator->getStripeCustomerId();
            if ($organization->getIsPaid() != 1 || $customerId == "" || $customerId == null) {
                $allowed_upload = false;
            }
        } else {
            $userViewSettings = $fieldsObj->getDefaultOrder();
        }

        $userViewSettings = json_decode($userViewSettings, true);
        $tooltip = $fieldsObj->getToolTip(3);
        $allErrors = $this->allFormErrors($form);
        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'fieldSettings' => $userViewSettings,
            'allErrors' => $allErrors,
            'type' => $data['mediaType']->getName(),
            'tooltip' => $tooltip,
            'allowed_upload' => $allowed_upload
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
        $session = $this->getRequest()->getSession();
        if (($session->has('termsStatus') && $session->get('termsStatus') == 0) || ($session->has('limitExceed') && $session->get('limitExceed') == 0)) {
            return $this->redirect($this->generateUrl('dashboard'));
        }
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
        $userViewSettings = $fieldsObj->getDefaultOrder();
        $allowed_upload = "";
        if ($projectId || $this->get('session')->get('vedioProjectId')) {
            $allowed_upload = true;
            if ($projectId == null && $this->get('session')->get('vedioProjectId')) {
                $projectId = $this->get('session')->get('vedioProjectId');
            }
            $project = $em->getRepository('ApplicationFrontBundle:Projects')->findOneBy(array('id' => $projectId));
            if ($project->getViewSetting() != null) {
                $defSettings = $fieldsObj->getDefaultOrder();
                $dbSettings = $project->getViewSetting();
                $userViewSettings = $fieldsObj->fields_cmp(json_decode($defSettings, true), json_decode($dbSettings, true));
            } else {
                $userViewSettings = $fieldsObj->getDefaultOrder();
            }
            $organization = $em->getRepository('ApplicationFrontBundle:Organizations')->find($project->getOrganization()->getId());
            $creator = $organization->getUsersCreated();
            $customerId = $creator->getStripeCustomerId();
            if ($organization->getIsPaid() != 1 || $customerId == "" || $customerId == null) {
                $allowed_upload = false;
            }
        }
        $userViewSettings = json_decode($userViewSettings, true);
        $tooltip = $fieldsObj->getToolTip(3);
        return $this->render('ApplicationFrontBundle:VideoRecords:new.html.php', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'fieldSettings' => $userViewSettings,
                    'type' => $data['mediaType']->getName(),
                    'allErrors' => array(),
                    'tooltip' => $tooltip,
                    'allowed_upload' => $allowed_upload
        ));
    }

    /**
     * Displays a form to edit an existing VideoRecords entity.
     *
     * @Route("/video/{id}/edit", name="record_video_edit")
     * @Route("/video/{id}/edit/{projectId}", name="record_video_edit_against_project")
     * @Method("GET")
     * @Template()
     * @return template
     */
    public function editAction($id, $projectId = null) {
        $session = $this->getRequest()->getSession();
        if (($session->has('termsStatus') && $session->get('termsStatus') == 0) || ($session->has('limitExceed') && $session->get('limitExceed') == 0)) {
            return $this->redirect($this->generateUrl('dashboard'));
        }
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
        //   $deleteForm = $this->createDeleteForm($id);

        if ($projectId) {
            $project = $em->getRepository('ApplicationFrontBundle:Projects')->findOneBy(array('id' => $projectId));
            if ($project->getViewSetting() != null) {
                $defSettings = $fieldsObj->getDefaultOrder();
                $dbSettings = $project->getViewSetting();
                $userViewSettings = $fieldsObj->fields_cmp(json_decode($defSettings, true), json_decode($dbSettings, true));
//                $userViewSettings = $project->getViewSetting();
            } else {
                $userViewSettings = $fieldsObj->getDefaultOrder();
            }
        } else if ($entity->getRecord()->getProject()->getViewSetting()) {
//            $userViewSettings = $entity->getRecord()->getProject()->getViewSetting();
            $defSettings = $fieldsObj->getDefaultOrder();
            $dbSettings = $entity->getRecord()->getProject()->getViewSetting();
            $userViewSettings = $fieldsObj->fields_cmp(json_decode($defSettings, true), json_decode($dbSettings, true));
        } else {
            $userViewSettings = $fieldsObj->getDefaultOrder();
        }

        $userViewSettings = json_decode($userViewSettings, true);
        $tooltip = $fieldsObj->getToolTip(3);
        $images = $em->getRepository('ApplicationFrontBundle:RecordImages')->findBy(array('recordId' => $entity->getRecord()->getId()));

        return $this->render('ApplicationFrontBundle:VideoRecords:edit.html.php', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    //        'delete_form' => $deleteForm->createView(),
                    'fieldSettings' => $userViewSettings,
                    'type' => $data['mediaType']->getName(),
                    'allErrors' => array(),
                    'tooltip' => $tooltip,
                    'images' => $images
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
        $form->add('delete', 'submit', array('label' => 'Delete', 'attr' => array('class' => 'button danger', 'onclick' => 'return confirm("Are you sure you want to delete selected record?")')));
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
        $userViewSettings = $fieldsObj->getDefaultOrder();
        $data = $fieldsObj->getData(3, $em, $this->getUser(), null, $entity->getRecord()->getId());

        //  $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity, $em, $data);
        $editForm->handleRequest($request);
        $result = $this->checkUniqueId($request, $entity->getRecord()->getId());
        if ($result != '') {
            $error = new FormError("The unique ID must be unique.");
            $editForm->get('record')->get('uniqueId')->addError($error);
        }
        if ($editForm->get('delete')->isClicked()) {
            return $this->redirect($this->generateUrl('record_video_delete', array('id' => $id)));
        }
        if ($editForm->isValid()) {
            try {
                $em->flush();
                $sphinxInfo = $this->getSphinxInfo();
                $sphinxSearch = new SphinxSearch($em, $sphinxInfo, $entity->getRecord()->getId(), 3);
                $sphinxSearch->replace();
                if (!in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles()) && $this->getUser()->getOrganizations() && ($editForm->get('save_and_duplicate')->isClicked() || $editForm->get('save_and_new')->isClicked()) && $this->container->getParameter("enable_stripe")) {
                    $paidOrg = $fieldsObj->paidOrganizations($this->getUser()->getOrganizations()->getId(), $em);
                    if ($paidOrg || is_array($paidOrg)) {
                        $org_records = $em->getRepository('ApplicationFrontBundle:Records')->countOrganizationRecords($this->getUser()->getOrganizations()->getId());
                        $counter = $org_records['total'];
                        $plan_limit = 2500;
                        $plan_id = "";
                        $creator = $this->getUser()->getOrganizations()->getUsersCreated();
                        if (in_array("ROLE_ADMIN", $creator->getRoles())) {
                            $plan_id = $creator->getStripePlanId();
                        }
                        if ($plan_id != NULL && $plan_id != "") {
                            $plan = $em->getRepository('ApplicationFrontBundle:Plans')->findBy(array("planId" => $plan_id));
                            $plan_limit = $plan[0]->getRecords();
                        }
                        if ($counter == $plan_limit) {
                            return $this->redirect($this->generateUrl('record_list_withdialog', array('dialog' => 1)));
                        }
                    }
                }
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
                
            }
        }


        if ($entity->getRecord()->getProject()->getViewSetting()) {
            $defSettings = $fieldsObj->getDefaultOrder();
            $dbSettings = $entity->getRecord()->getProject()->getViewSetting();
            $userViewSettings = $fieldsObj->fields_cmp(json_decode($defSettings, true), json_decode($dbSettings, true));
//            $userViewSettings = $entity->getRecord()->getProject()->getViewSetting();
        }
        $userViewSettings = json_decode($userViewSettings, true);
        $tooltip = $fieldsObj->getToolTip(3);
        $allErrors = $this->allFormErrors($editForm);
        $images = $em->getRepository('ApplicationFrontBundle:RecordImages')->findBy(array('recordId' => $entity->getRecord()->getId()));

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            //     'delete_form' => $deleteForm->createView(),
            'fieldSettings' => $userViewSettings,
            'allErrors' => $allErrors,
            'type' => $data['mediaType']->getName(),
            'tooltip' => $tooltip,
            'images' => $images
        );
    }

    /**
     * Deletes a VideoRecords entity.
     *
     * @param integer $id
     *
     * @route("/{id}", name = "record_video_delete")
     * @return redirect
     */
    public function delete($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationFrontBundle:VideoRecords')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VideoRecords entity.');
        }
        $shpinxInfo = $this->getSphinxInfo();
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo, $entity->getRecord()->getId(), 3);
        $sphinxSearch->delete();
        $em->remove($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('record_list'));
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
        if (count($user) != 0) {
            $records = $em->getRepository('ApplicationFrontBundle:Records')->findOrganizationUniqueRecords($user->getProject()->getOrganization()->getId(), $unique, $id);
            if (count($records) == 0) {
                return '';
            } else {
                return 'unique id not unique';
            }
        }
        return '';
    }

    private function allFormErrors($form) {
        $return = array();
        $errors = $form->getErrorsAsString();
        $all = explode(":", $errors);
        $skip = false;
        foreach ($all as $key => $value) {
            if (strpos("ERROR", trim($value)) !== FALSE && strpos("ERROR", trim($value)) === 0) {
                $skip = true;
                $required = explode("\n", $all[$key + 1]);
                $return[] = $required[0];
            }
            if ($skip) {
                $skip = FALSE;
            }
        }
        return $return;
    }

}
