<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\UserSettings;
use Application\Bundle\FrontBundle\Entity\FieldSettings;
use Application\Bundle\FrontBundle\Helper\DefaultFields as DefaultFields;
use Application\Bundle\FrontBundle\Form\UserSettingsType;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Application\Bundle\FrontBundle\Entity\Records;
use Application\Bundle\FrontBundle\Entity\Projects;
use JMS\JobQueueBundle\Entity\Job;
use DateInterval;
use DateTime;

/**
 * UserSettings controller.
 *
 * @Route("/fieldsettings")
 */
class UserSettingsController extends Controller {

	/**
	 * User settings
	 *
	 * @Route("/cron", name="cron_setup")
	 * @Method("GET")
	 * @Template()
	 * @return array
	 */
	public function setupCronAction() {
		$job = new Job('avcc:backup-report');
		$date = new DateTime();
		$date->add(new DateInterval('PT1M'));
		$job->setExecuteAfter($date);
		$em->persist($job);
		$em->flush($job);
	}

	/**
	 * User settings
	 *  
	 * @param Request $request
	 * 
	 * @Route("/", name="field_settings")
	 * @Route("/{projectId}", name="field_settings_against_project")
	 * @Method("GET")
	 * @Template()
	 * @return array
	 */
	public function indexAction($projectId = null) {
		$em = $this->getDoctrine()->getManager();
		$entities = '';
		$name = '';
		$viewSettings = json_encode(array('audio' => '', 'video' => '', 'film' => ''));
		if ( ! in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles()) && $this->getUser()->getOrganizations()) {
			$projects = $em->getRepository('ApplicationFrontBundle:Projects')->findBy(array('organization' => $this->getUser()->getOrganizations()->getId()));
		} else {
			$projects = $em->getRepository('ApplicationFrontBundle:Projects')->findAll();
		}
		foreach ($projects as $project) {
			$proj[$project->getId()] = $project->getName();
		}

		if ($projectId) {
			$entities = $em->getRepository('ApplicationFrontBundle:Projects')->findOneBy(array('id' => $projectId));

			if ( ! $entities || ! $entities->getViewSetting()) {
				$fObj = new DefaultFields();
				$viewSettings = $fObj->getDefaultOrder();
			} else {
				$viewSettings = $entities->getViewSetting();
				$name = $entities->getName();
			}
		}
		$userViewSettings = json_decode($viewSettings, true);

		return array(
			'entities' => $userViewSettings,
			'project' => $proj,
			'project_name' => ucwords($name),
		);
	}

	/**
	 * Update user settings
	 *
	 * @param Request $request
	 *
	 * @Route("/update", name="field_settings_update")
	 * @Method("POST")
	 * @Template()
	 * @return array
	 */
	public function updateAction(Request $request) {
		$success = FALSE;
		$reload = FALSE;
		if ($request->getMethod() == 'POST') {
			$settings = $this->get('request')->request->get('settings');
			$projectId = $this->get('request')->request->get('project_id');

			$userSetting = json_encode($settings);
			$em = $this->getDoctrine()->getManager();
			if ($projectId == 0) {
				$this->get('session')->getFlashBag()->add('error', 'Please select project.');
				$success = TRUE;
				$reload = TRUE;
			} else {
				$userEntity = $em->getRepository('ApplicationFrontBundle:Projects')->findOneBy(array('id' => $projectId));
				if ($userEntity) {
					$userEntity->setViewSetting($userSetting);
					$userEntity->setUpdatedOnValue(date('Y-m-d h:i:s'));
					$em->persist($userEntity);
					$em->flush();
					$this->get('session')->getFlashBag()->add('success', 'Settings updated succesfully.');
					$success = TRUE;
					$reload = TRUE;
				} else {
					$this->get('session')->getFlashBag()->add('error', 'Please select project.');
					$success = TRUE;
					$reload = TRUE;
				}
			}
		}
		echo json_encode(array('success' => $success, 'reload' => $reload));
		exit;
	}

	/**
	 * enable backup
	 *
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 *
	 * @Route("/backup/", name="field_settings_backup")
	 * @Method("GET")
	 * @Template()
	 * @return array
	 */
	public function backupAction(Request $request) {
		$userEntity = $this->getDoctrine()
		->getRepository('ApplicationFrontBundle:UserSettings')
		->findOneBy(array('user' => $this->getUser()->getId()));
		if ( ! $userEntity) {
			$userEntity = new UserSettings();
			$userEntity->setUser($this->getUser());
			$em = $this->getDoctrine()->getManager();
			$em->persist($userEntity);
			$em->flush();
		}
		$session = $request->getSession();
		if ($session->has('error')) {
			$error = $session->get('error');
			$session->remove('error');
		} else {
			$error = '';
		}
		$form = $this->createEditForm($userEntity);
		return array(
			'entity' => $userEntity,
			'form' => $form->createView(),
			'error' => $error,
		);
	}

	/**
	 * create New Form
	 *
	 * @param UserSettings $entity
	 *
	 * @return type
	 */
	public function createNewForm(UserSettings $entity) {
		$form = $this->createForm(new UserSettingsType(), $entity, array(
			'action' => $this->generateUrl('new_backup'),
			'method' => 'POST',
		));
		$form->add('submit', 'submit', array('label' => 'Save', 'attr' => array('class' => 'button primary')));

		return $form;
	}

	/**
	 * create Edit Form
	 *
	 * @param UserSettings $userEntity
	 *
	 * @return type
	 */
	public function createEditForm(UserSettings $userEntity) {
		$form = $this->createForm(new UserSettingsType(), $userEntity, array(
			'action' => $this->generateUrl('edit_backup', array('id' => $userEntity->getId())),
			'method' => 'POST',
		));
		$form->add('submit', 'submit', array('label' => 'Save', 'attr' => array('class' => 'button primary')));

		return $form;
	}

	/**
	 * edit form
	 *
	 * @param  \Symfony\Component\HttpFoundation\Request $request
	 * @Method ("POST")
	 * @Route("/backup/{id}", name="edit_backup")
	 * @Template()
	 * @return array
	 */
	public function updateFormAction(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ApplicationFrontBundle:UserSettings')->find($id);
		if ( ! $entity) {
			throw $this->createNotFoundException('Unable to find UserSettings entity.');
		}

		$editForm = $this->createEditForm($entity);
		$editForm->handleRequest($request);
		if ($editForm->get('backupEmail')->getData()) {
			$email_ids = explode(',', $editForm->get('backupEmail')->getData());
		}
		$emailConstraint = new EmailConstraint();
		$emailConstraint->message = 'Invalid email id';
		if (isset($email_ids)) {
			foreach ($email_ids as $email) {
				$errors = $this->get('validator')->validateValue(trim($email), $emailConstraint);
				if (strpos($errors, 'Invalid email id')) {
					$session = $request->getSession();
					$session->set('error', 'Please enter valid email id');

					return $this->redirect($this->generateUrl('field_settings_backup'));
				}
			}
		}
		if ($editForm->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();
		}

		return $this->redirect($this->generateUrl('field_settings_backup'));
	}

	/**
	 * edit form
	 *
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 *
	 * @Route("/new", name="new_backup")
	 * @Template()
	 * @return array
	 */
	public function newFormAction(Request $request) {
		$entity = new UserSettings();
		$form = $this->createNewForm($entity);
		$form->handleRequest($request);
		if ($form->get('backupEmail')->getData()) {
			$email_ids = explode(',', $form->get('backupEmail')->getData());
		}
		$emailConstraint = new EmailConstraint();
		$emailConstraint->message = 'Invalid email id';
		foreach ($email_ids as $email) {
			$errors = $this->get('validator')->validateValue($email, $emailConstraint);
			if (strpos($errors, 'Invalid email id')) {
				$session = $request->getSession();
				$session->set('error', 'Please enter valid email id');

				return $this->redirect($this->generateUrl('field_settings_backup'));
			}
		}
		$em = $this->getDoctrine()->getManager();
		if ($form->isValid()) {
			$entity->setUser($this->getUser());
			$em->persist($entity);
			$em->flush();
		}

		return $this->redirect($this->generateUrl('field_settings_backup'));
	}

	/**
	 * add Backup Record command In Job
	 *
	 * @Route("/addBackup", name="backup_record")
	 * @Template("ApplicationFrontBundle:UserSettings:default.html.php")
	 */
	public function addBackupRecordInJobAction() {
		$em = $this->getDoctrine()->getManager();
		$job = new Job('avcc:backup-report');
		$date = new DateTime();
		$date->setTime(0, 0);
		//   $date->add(new DateInterval('PT1M'));
		$job->setExecuteAfter($date);
		$em->persist($job);
		$em->flush($job);
		exit;
	}

}
