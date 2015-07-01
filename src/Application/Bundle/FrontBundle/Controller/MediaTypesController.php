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
use Application\Bundle\FrontBundle\Entity\MediaTypes;
use Application\Bundle\FrontBundle\Form\MediaTypesType;

/**
 * MediaTypes controller.
 *
 * @Route("/vocabularies/mediatypes")
 */
class MediaTypesController extends Controller
{

    /**
     * Lists all MediaTypes entities.
     *
     * @Route("/", name="vocabularies_mediatypes")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:MediaTypes')->findBy(array(), array('order' => 'ASC'));

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new MediaTypes entity.
     *
     * @param Request $request
     *
     * @Route("/", name="vocabularies_mediatypes_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:MediaTypes:new.html.twig")
     * @return array
     */
    public function createAction(Request $request)
    {
        $entity = new MediaTypes();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Media type added succesfully.');

            return $this->redirect($this->generateUrl('vocabularies_mediatypes_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a MediaTypes entity.
     *
     * @param MediaTypes $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(MediaTypes $entity)
    {
        $form = $this->createForm(new MediaTypesType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_mediatypes_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new MediaTypes entity.
     *
     * @Route("/new", name="vocabularies_mediatypes_new")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function newAction()
    {
        $entity = new MediaTypes();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a MediaTypes entity.
     *
     * @param integer $id
     *
     * @Route("/{id}", name="vocabularies_mediatypes_show")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:MediaTypes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MediaTypes entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing MediaTypes entity.
     *
     * @param integer $id
     *
     * @Route("/{id}/edit", name="vocabularies_mediatypes_edit")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:MediaTypes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MediaTypes entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a MediaTypes entity.
    *
    * @param MediaTypes $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(MediaTypes $entity)
    {
        $form = $this->createForm(new MediaTypesType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_mediatypes_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing MediaTypes entity.
     *
     * @param Request $request
     * @param integer $id
     *
     * @Route("/{id}", name="vocabularies_mediatypes_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:MediaTypes:edit.html.twig")
     * @return array
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:MediaTypes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MediaTypes entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Media type updated succesfully.');

            return $this->redirect($this->generateUrl('vocabularies_mediatypes_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a MediaTypes entity.
     *
     * @param Request $request
     * @param integer $id
     *
     * @Route("/{id}", name="vocabularies_mediatypes_delete")
     * @Method("DELETE")
     * @return redirect
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:MediaTypes')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find MediaTypes entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('vocabularies_mediatypes'));
    }

    /**
     * Creates a form to delete a MediaTypes entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('vocabularies_mediatypes_delete', array('id' => $id)))
            ->setMethod('DELETE')
             ->add('submit', 'submit', array('label' => 'Delete','attr' => array('onclick' => "return confirm('Are you sure you want to delete selected term?')")))
                        ->getForm();
    }
    /**
     * update field order
     *
     * @param Request $request
     *
     * @Route("/fieldOrder", name="mediatype_update_order")
     * @Method("POST")
     * @return array
     */
    public function updateFieldOrder(Request $request) {
        // code to update
        $adsIds = $this->get('request')->request->get('media_ids');
        $count = 0;
        foreach ($adsIds as $ads) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:MediaTypes')->find($ads);
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
