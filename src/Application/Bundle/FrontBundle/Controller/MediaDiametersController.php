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
use Application\Bundle\FrontBundle\Entity\MediaDiameters;
use Application\Bundle\FrontBundle\Form\MediaDiametersType;
/**
 * MediaDiameters controller.
 *
 * @Route("/vocabularies/mediadiameters")
 */
class MediaDiametersController extends Controller
{

    /**
     * Lists all MediaDiameters entities.
     *
     * @Route("/", name="vocabularies_mediadiameters")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:MediaDiameters')->findBy(array(), array('order' => 'ASC'));

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new MediaDiameters entity.
     *
     * @param Request $request
     *
     * @Route("/", name="vocabularies_mediadiameters_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:MediaDiameters:new.html.twig")
     * @return array
     */
    public function createAction(Request $request)
    {
        $entity = new MediaDiameters();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Media diameter added succesfully.');

            return $this->redirect($this->generateUrl('vocabularies_mediadiameters'));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a MediaDiameters entity.
     *
     * @param MediaDiameters $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(MediaDiameters $entity)
    {
        $form = $this->createForm(new MediaDiametersType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_mediadiameters_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new MediaDiameters entity.
     *
     * @Route("/new", name="vocabularies_mediadiameters_new")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function newAction()
    {
        $entity = new MediaDiameters();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a MediaDiameters entity.
     *
     * @param integer $id
     *
     * @Route("/{id}", name="vocabularies_mediadiameters_show")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:MediaDiameters')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MediaDiameters entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing MediaDiameters entity.
     *
     * @param integer $id
     *
     * @Route("/{id}/edit", name="vocabularies_mediadiameters_edit")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:MediaDiameters')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MediaDiameters entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a MediaDiameters entity.
     *
     * @param MediaDiameters $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(MediaDiameters $entity)
    {
        $form = $this->createForm(new MediaDiametersType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_mediadiameters_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing MediaDiameters entity.
     *
     * @param Request $request
     * @param integer $id
     *
     * @Route("/{id}", name="vocabularies_mediadiameters_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:MediaDiameters:edit.html.twig")
     * @return array
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:MediaDiameters')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MediaDiameters entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Media diameter updated succesfully.');

            return $this->redirect($this->generateUrl('vocabularies_mediadiameters_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a MediaDiameters entity.
     *
     * @param Request $request
     * @param integer $id
     *
     * @Route("/{id}", name="vocabularies_mediadiameters_delete")
     * @Method("DELETE")
     * @return redirect
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:MediaDiameters')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find MediaDiameters entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('vocabularies_mediadiameters'));
    }

    /**
     * Creates a form to delete a MediaDiameters entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('vocabularies_mediadiameters_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                         ->add('submit', 'submit', array('label' => 'Delete','attr' => array('onclick' => "return confirm('Are you sure you want to delete selected term?')")))
                        ->getForm();
    }
    /**
     * update field order
     *
     * @param Request $request
     *
     * @Route("/fieldOrder", name="md_update_order")
     * @Method("POST")
     * @return array
     */
    public function updateFieldOrder(Request $request) {
        // code to update
        $adsIds = $this->get('request')->request->get('md_ids');
        $count = 0;
        foreach ($adsIds as $ads) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:MediaDiameters')->find($ads);
            if ($entity) {
                $entity->setOrder($count);
               // $em->persist($entity);
                $em->flush();
                $count = $count + 1;
            }
        }
        echo json_encode(array('success' => 'true'));
        exit;
    }

}
