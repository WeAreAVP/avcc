<?php

namespace Application\Bundle\FrontBundle\EventListener;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $token = $event->getAuthenticationToken();
        $request = $event->getRequest();
       
        $this->onAuthenticationSuccess($request, $token);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        echo "AuthenticationSuccessHandler";
        if($token->getUser() && $token->getUser()->getPasswordChangeRequest()) {
            $this->container->get('session')->clear();
            $emailEncode = base64_encode($token->getUser()->getEmail());
            $url = $this->container->get('router')->generate('login', ["action" => $emailEncode]);
            return new RedirectResponse($url);
        } else {
            return new RedirectResponse("/");
        }
    }
}