<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Form\PhotoType;
use Application\Bundle\FrontBundle\SphinxSearch\SphinxSearch;

/**
 * PhotoController controller.
 *
 * @Route("/photo")
 */
class PhotoController extends Controller {

    /**
     * Creates a new FilmRecords entity.
     *
     * @param Request $request
     *
     * @Route("/add/{id}", name="record_photo")
     * @Template("ApplicationFrontBundle:Photo:add.html.twig")
     */
    public function addAction(Request $request, $id = NULL) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:Records')->findOneBy(array('id' => $id));
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Records entity.');
        }

        $organization = $em->getRepository('ApplicationFrontBundle:Organizations')->find($entity->getProject()->getOrganization()->getId());
        $creator = $organization->getUsersCreated();
        $customerId = $creator->getStripeCustomerId();
        if ($organization->getIsPaid() != 1 || $customerId == "" || $customerId == null) {
            return array(
                'rec_id' => $id,
                "error" => true,
                "msg" => "You cannot add images to this record because " . $organization->getName() . " is on free plan"
            );
        }
        
        $form = $this->createForm(new PhotoType($id), array(), array(
            'action' => $this->generateUrl('record_photo', array('id' => $id)),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Save'));
        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                $data = $form->getData();
                $this->getPhotoUploader()->upload($data['photo'], $data['recordId']);
                $shpinxInfo = $this->container->getParameter('sphinx_param');
                $em = $this->getDoctrine()->getManager();
                $record = $em->getRepository('ApplicationFrontBundle:Records')->find($data['recordId']);
                $sphinxSearch = new SphinxSearch($em, $shpinxInfo, $data['recordId'], $record->getMediaType()->getId());
                $sphinxSearch->replace();
                return $this->redirect($this->generateUrl('record_show', array('id' => $data['recordId'])));
            }
        } 

        return array(
            'rec_id' => $id,
            'form' => $form->createView(),
            "error" => false
        );
    }

    /**
     * @return Acme\StorageBundle\Uploader\PhotoUploader
     */
    protected function getPhotoUploader() {
        return $this->get('application_front.photo_uploader');
    }

}
