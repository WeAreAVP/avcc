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
use Application\Bundle\FrontBundle\SphinxSearch\SphinxSearch;
use Symfony\Component\Form\FormError;

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
        $form = $this->createCreateForm($entity, $em);
        $form->handleRequest($request);
        $fieldsObj = new DefaultFields();
        $data = $fieldsObj->getData(2, $em, $this->getUser(), null);
        if ($form->isValid()) {
            $em->persist($entity);
            try {
                $em->flush();
                $shpinxInfo = $this->getSphinxInfo();
                $sphinxSearch = new SphinxSearch($em, $shpinxInfo, $entity->getRecord()->getId(), 2);
                $sphinxSearch->insert();
                // the save_and_dupplicate button was clicked
                if ($form->get('save_and_duplicate')->isClicked()) {
                    return $this->redirect($this->generateUrl('record_film_duplicate', array('filmRecId' => $entity->getId())));
                }
                if ($form->get('save_and_new')->isClicked()) {
                    return $this->redirect($this->generateUrl('record_film_new'));
                }
                $this->get('session')->getFlashBag()->add('success', 'Film record added succesfully.');

                return $this->redirect($this->generateUrl('record_list'));
            } catch (\Doctrine\DBAL\DBALException $e) {
                if (is_int(strpos($e->getPrevious()->getMessage(), 'Duplicate entry'))) {
                    $error = new FormError("The unique ID must be unique.");
                    $recordForm = $form->get('record');
                    $recordForm->get('uniqueId')->addError($error);
                }
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
     * Creates a form to create a FilmRecords entity.
     *
     * @param FilmRecords   $entity The entity
     * @param EntityManager $em
     * @param array         $data
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(FilmRecords $entity, $em, $data = null)
    {
        $form = $this->createForm(new FilmRecordsType($em, $data), $entity, array(
            'action' => $this->generateUrl('record_film_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Save'));
        $form->add('save_and_new', 'submit', array('label' => 'Save & New'));
        $form->add('save_and_duplicate', 'submit', array('label' => 'Save & Duplicate'));

        return $form;
    }

    /**
     * Displays a form to create a new FilmRecords entity.
     *
     * @Route("/new", name="record_film_new")
     * @Route("/new/{projectId}", name="record_film_new_against_project")
     * @Route("/new/{filmRecId}/duplicate", name="record_film_duplicate")
     * @Method("GET")
     * @Template()
     * @return template
     */
    public function newAction($projectId = null, $filmRecId = null)
    {
        $em = $this->getDoctrine()->getManager();
        $fieldsObj = new DefaultFields();
        $data = $fieldsObj->getData(2, $em, $this->getUser(), $projectId);
        if ($filmRecId) {
            $entity = $em->getRepository('ApplicationFrontBundle:FilmRecords')->find($filmRecId);
        } else {
            $entity = new FilmRecords();
        }
        $form = $this->createCreateForm($entity, $em, $data);
        $user_view_settings = $fieldsObj->getFieldSettings($this->getUser(), $em);

        return $this->render('ApplicationFrontBundle:FilmRecords:new.html.php', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'fieldSettings' => $user_view_settings,
                    'type' => $data['mediaType']->getName(),
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
     * @return array
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:FilmRecords')->findOneBy(array('record' => $id));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FilmRecords entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationFrontBundle:FilmRecords:show.html.php', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing FilmRecords entity.
     *
     * @param integer $id
     *
     * @Route("/{id}/edit", name="record_film_edit")
     * @Method("GET")
     * @Template()
     * @return template
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:FilmRecords')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FilmRecords entity.');
        }
        $fieldsObj = new DefaultFields();
        $data = $fieldsObj->getData(2, $em, $this->getUser(), null, $entity->getRecord()->getId());
        $editForm = $this->createEditForm($entity, $em, $data);
        $deleteForm = $this->createDeleteForm($id);
        $userViewSettings = $fieldsObj->getFieldSettings($this->getUser(), $em);

        return $this->render('ApplicationFrontBundle:FilmRecords:edit.html.php', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
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

        $form->add('submit', 'submit', array('label' => 'Save'));
        $form->add('save_and_new', 'submit', array('label' => 'Save & New'));
        $form->add('save_and_duplicate', 'submit', array('label' => 'Save & Duplicate'));

        return $form;
    }

    /**
     * Edits an existing FilmRecords entity.
     *
     * @param Request $request
     * @param type    $id
     *
     * @Route("/{id}", name="record_film_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:FilmRecords:edit.html.php")
     * @return template
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:FilmRecords')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FilmRecords entity.');
        }
        $fieldsObj = new DefaultFields();
        $data = $fieldsObj->getData(2, $em, $this->getUser(), null, $entity->getRecord()->getId());
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity, $em, $data);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            try {
                $em->flush();
                $shpinxInfo = $this->getSphinxInfo();
                $sphinxSearch = new SphinxSearch($em, $shpinxInfo, $entity->getRecord()->getId(), 2);
                $sphinxSearch->replace();
                // the save_and_dupplicate button was clicked
                if ($editForm->get('save_and_duplicate')->isClicked()) {
                    return $this->redirect($this->generateUrl('record_film_duplicate', array('filmRecId' => $id)));
                }
                if ($editForm->get('save_and_new')->isClicked()) {
                    return $this->redirect($this->generateUrl('record_film_new'));
                }
                $this->get('session')->getFlashBag()->add('success', 'Film record updated succesfully.');

                return $this->redirect($this->generateUrl('record_list'));
            } catch (\Doctrine\DBAL\DBALException $e) {
                if (is_int(strpos($e->getPrevious()->getMessage(), 'Duplicate entry'))) {
                    $error = new FormError("The unique ID must be unique.");
                    $recordForm = $editForm->get('record');
                    $recordForm->get('uniqueId')->addError($error);
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
     * Deletes a FilmRecords entity.
     *
     * @param Request $request
     * @param integer $id
     *
     * @Route("/{id}", name="record_film_delete")
     * @Method("DELETE")
     * @return Redirect
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

    /**
     * Get sphinx parameters
     *
     * @return array
     */
    protected function getSphinxInfo()
    {
        return $this->container->getParameter('sphinx_param');
    }

}
