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
use Application\Bundle\FrontBundle\Entity\CassetteSizes;
use Application\Bundle\FrontBundle\Form\CassetteSizesType;

/**
 * CassetteSizes controller.
 *
 * @Route("/vocabularies/cassettessizes")
 */
class CassetteSizesController extends Controller
{

    /**
     * Lists all CassetteSizes entities.
     *
     * @Route("/", name="vocabularies_cassettessizes")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:CassetteSizes')->findBy(array(), array('order' => 'ASC'));

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new CassetteSizes entity.
     *
     * @param Request $request
     *
     * @Route("/", name="vocabularies_cassettessizes_create")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:CassetteSizes:new.html.twig")
     * @return array
     */
    public function createAction(Request $request)
    {
        $entity = new CassetteSizes();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Cassette size added succesfully.');

            return $this->redirect($this->generateUrl('vocabularies_cassettessizes'));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a CassetteSizes entity.
     *
     * @param CassetteSizes $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CassetteSizes $entity)
    {
        $form = $this->createForm(new CassetteSizesType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_cassettessizes_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new CassetteSizes entity.
     *
     * @Route("/new", name="vocabularies_cassettessizes_new")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function newAction()
    {
        $entity = new CassetteSizes();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a CassetteSizes entity.
     *
     * @param integer $id
     *
     * @Route("/{id}", name="vocabularies_cassettessizes_show")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:CassetteSizes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CassetteSizes entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing CassetteSizes entity.
     *
     * @param integer $id
     *
     * @Route("/{id}/edit", name="vocabularies_cassettessizes_edit")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:CassetteSizes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CassetteSizes entity.');
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
     * Creates a form to edit a CassetteSizes entity.
     *
     * @param CassetteSizes $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(CassetteSizes $entity)
    {
        $form = $this->createForm(new CassetteSizesType(), $entity, array(
            'action' => $this->generateUrl('vocabularies_cassettessizes_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing CassetteSizes entity.
     *
     * @param Request $request
     * @param type    $id
     *
     * @Route("/{id}", name="vocabularies_cassettessizes_update")
     * @Method("PUT")
     * @Template("ApplicationFrontBundle:CassetteSizes:edit.html.twig")
     * @return array
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:CassetteSizes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CassetteSizes entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Cassette size updated succesfully.');

            return $this->redirect($this->generateUrl('vocabularies_cassettessizes_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a CassetteSizes entity.
     *
     * @param Request $request
     * @param type    $id
     *
     * @Route("/{id}", name="vocabularies_cassettessizes_delete")
     * @Method("DELETE")
     * @return redirect
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:CassetteSizes')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CassetteSizes entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('vocabularies_cassettessizes'));
    }

    /**
     * Creates a form to delete a CassetteSizes entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('vocabularies_cassettessizes_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm();
    }
    
    /**
     * update field order
     *
     * @param Request $request
     *
     * @Route("/fieldOrder", name="cassettesize_update_order")
     * @Method("POST")
     * @return array
     */
    public function updateFieldOrder(Request $request) {
        // code to update
        $adsIds = $this->get('request')->request->get('cs_ids');
        $count = 0;
        foreach ($adsIds as $ads) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:CassetteSizes')->find($ads);
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
