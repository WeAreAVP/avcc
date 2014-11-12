<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Security\Core\Exception\AccessDeniedException;
//use Symfony\Component\Security\Core\SecurityContext;
//use Application\Bundle\FrontBundle\Entity\Users;
//use Application\Bundle\FrontBundle\Helper\DefaultFields as DefaultFields;
//use Application\Bundle\FrontBundle\Entity\UserSettings as UserSettings;

/**
 * Records controller.
 * 
 * @Route("/")
 *
 */
class RecordsController extends Controller
{
    /**
     * Lists all AudioRecords entities.
     *
     * @Route("/", name="record_list")
     * @Method("GET")
     * @Template("ApplicationFrontBundle:AudioRecords:index.html.twig")
     * @return array
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:Records')->findAll();

        return array(
            'entities' => $entities,
        );
    }

}
