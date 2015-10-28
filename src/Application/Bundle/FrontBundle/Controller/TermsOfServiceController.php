<?php

/**
 * AVCC
 * 
 * @category AVCC
 * @package  Application
 * @author   Nouman Tayyab <nouman@avpreserve.com>
 * @author   Rimsha Khalid <rimsha@avpreserve.com>
 * @license  AGPLv3 http://www.gnu.org/licenses/agpl-3.0.txt
 * @copyright Audio Visual Preservation Solutions, Inc
 * @link     http://avcc.avpreserve.com
 */

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\TermsOfService;
use Application\Bundle\FrontBundle\Form\TermsOfServiceType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * TermsOfService controller.
 *
 * @Route("/terms")
 */
class TermsOfServiceController extends Controller {

    /**
     * Lists all AudioRecords entities.
     *
     * @Route("/", name="terms_of_service")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function indexAction() {
        $entities = '';
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:TermsOfService')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Bases entity.
     *
     * @param Request $request
     *
     * @Route("/", name="terms_of_service_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:TermsOfService:new.html.twig")
     * @return array
     */
    public function createAction(Request $request) {
        $entity = new TermsOfService();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($entity->getStatus()) {
                $this->checkActiveRecord();
                $entity->setIsPublished(1);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
//            $this->updateTermsInOrg();
            $this->get('session')->getFlashBag()->add('success', 'Terms Of Service added succesfully.');

            return $this->redirect($this->generateUrl('terms_of_service'));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
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
    private function createCreateForm(TermsOfService $entity) {
        $form = $this->createForm(new TermsOfServiceType(), $entity, array(
            'action' => $this->generateUrl('terms_of_service_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Save'));

        return $form;
    }

    /**
     * Displays a form to create a new terms_of_service entity.
     *
     * @Route("/new", name="terms_of_service_new")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function newAction() {
//        $config['img_path'] = '/tinymceImages'; // Relative to domain name
//	$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . $config['img_path'];
//        
//        echo $config['upload_path'];exit;
        if (!in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles())) {
            throw new AccessDeniedException('Access Denied.');
        }
        $entity = new TermsOfService();
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
     * @Route("/{id}", name="terms_of_service_show")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:TermsOfService')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Terms Of Service.');
        }
        return array(
            'entity' => $entity,
        );
    }

    /**
     * Deletes a Help Guide.
     *
     * @param Request $request
     * @param type    $id
     *
     * @Route("/delete/{id}", name="terms_of_service_delete")
     * @Method("GET")
     * @return redirect
     */
    public function deleteAction(Request $request, $id) {
        if (!in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles())) {
            throw new AccessDeniedException('Access Denied.');
        }
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationFrontBundle:TermsOfService')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Terms Of Service entity.');
        }

        $em->remove($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('terms_of_service'));
    }

    /**
     * Displays a form to edit an existing Bases entity.
     *
     * @param integer $id
     *
     * @Route("/{id}/edit", name="terms_of_service_edit")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function editAction($id) {
        if (!in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles())) {
            throw new AccessDeniedException('Access Denied.');
        }
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:TermsOfService')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Terms Of Service entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView()
        );
    }

    /**
     * Creates a form to edit a Bases entity.
     *
     * @param Bases $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(TermsOfService $entity) {
        $form = $this->createForm(new TermsOfServiceType(), $entity, array(
            'action' => $this->generateUrl('terms_of_service_update', array('id' => $entity->getId())),
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
     * @Route("/{id}", name="terms_of_service_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:TermsOfService:edit.html.twig")
     * @return array
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:TermsOfService')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Terms Of Service entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            if ($entity->getStatus()) {
                $this->checkActiveRecord();
                $entity->setIsPublished(1);
            }
            $em->flush();
//            $this->updateTermsInOrg();
            $this->get('session')->getFlashBag()->add('success', 'Terms Of Service updated succesfully.');
            return $this->redirect($this->generateUrl('terms_of_service'));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView()
        );
    }

    private function checkActiveRecord($id = null) {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('ApplicationFrontBundle:TermsOfService')->findBy(array('status' => 1));
        if (count($entities) > 0) {
            foreach ($entities as $entity) {
                if ($id) {
                    if ($id != $entity->getId()) {
                        $entity->setStatus(0);
                    }
                } else {
                    $entity->setStatus(0);
                }
            }
            $em->persist($entity);
            $em->flush();
        }
    }

    private function updateTermsInOrg() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('ApplicationFrontBundle:Organizations')->findAll();
        $activeRecord = $em->getRepository('ApplicationFrontBundle:TermsOfService')->findBy(array('status' => 1));
        if (count($entities) > 0) {
            $termsId = $activeRecord[0]->getId();
            if ($termsId) {
                foreach ($entities as $entity) {
                    if ($entity->getTermsOfServiceId() == $termsId) {
                        break;
                    } else {
                        $entity->setTermsOfServiceId($termsId);
                        $entity->setIsAccepted(0);
                        $em->persist($entity);
                    }
                }
                $em->flush();
            }
        }
    }

}

