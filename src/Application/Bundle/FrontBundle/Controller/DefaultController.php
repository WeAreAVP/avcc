<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DefaultController extends Controller
{

    public function getParent()
    {
        return 'FOSUserBundle';
    }

    /**
     * 
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if ( ! is_object($user) || ! $user instanceof UserInterface)
        {
            throw new AccessDeniedException('This user does not have access to this section.');
            $this->redirect($this->generateUrl("application_front"));
        }
        return $this->render('ApplicationFrontBundle:Default:index.html.twig', array('name' => 'me'));
    }

}
