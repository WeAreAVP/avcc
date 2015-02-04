<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContext;
use Application\Bundle\FrontBundle\Entity\Users;
use Application\Bundle\FrontBundle\Form\Type\RegistrationFormType;
use Application\Bundle\FrontBundle\Helper\DefaultFields as DefaultFields;
use Application\Bundle\FrontBundle\Entity\UserSettings as UserSettings;
use Application\Bundle\FrontBundle\SphinxSearch\SphinxSearch;

/**
 * Default controller.
 *
 */
class DefaultController extends Controller
{

    /**
     *
     * @var string
     */
    static $DEFAULT_ROLE = 'ROLE_ADMIN';

    /**
     * calling parent bundle
     *
     * @return string
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }

    /**
     * Dashboard 
     * 
     * @Route("/", name="dashboard")
     * @Method("GET")
     * @Template()
     *
     * @return type renders index.html.twig template
     */
    public function indexAction()
    {

        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
            $this->redirect($this->generateUrl("application_front"));
        }
        $em = $this->getDoctrine()->getManager();
        if (true === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            $projects = $em->getRepository('ApplicationFrontBundle:Projects')->findAll();
        } else {
            $projects = $em->getRepository('ApplicationFrontBundle:Projects')->findBy(array('organization' => $this->getUser()->getOrganizations()));
        }
        
        $shpinxInfo = $this->container->getParameter('sphinx_param');
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo);
        $result = $sphinxSearch->removeEmpty($sphinxSearch->facetSelect('format', $this->getUser(), null, false, 'media_type', 'media_type'), 'format');
        
        $formatsChart = array();
        foreach ($result as $index => $format) {
            $formatsChart[] = array($format['format'], (int) $format['total']);
        }
        
        return $this->render('ApplicationFrontBundle:Default:index.html.twig', array(
                    'name' => $user->getUsername(),
                    'projects' => $projects,
                    'formats' => json_encode($formatsChart)
                        )
        );
    }

    /**
     * Login function
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return type
     */
    public function loginAction(Request $request)
    {
        /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
        $session = $request->getSession();
        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        if ($error) {
            // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
            $error = $error->getMessage();
            if (strtolower($error) === 'bad credentials') {
                $error = "Invalid username or password.";
            }
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);

        $csrfToken = $this->container->has('form.csrf_provider') ? $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate') : null;

        return $this->renderLogin(array(
                    'last_username' => $lastUsername,
                    'error' => $error,
                    'csrf_token' => $csrfToken,
        ));
    }

    /**
     * Renders the login template with the given parameters. Overwrite this function in
     * an extended controller to provide additional data for the login template.
     *
     * @param array $data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderLogin(array $data)
    {
        $template = sprintf('ApplicationFrontBundle:Default:login.html.twig');

        return $this->container->get('templating')->renderResponse($template, $data);
    }

    /**
     * @throws \RuntimeException
     */
    public function checkAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    /**
     * Login function
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return type
     */
    public function signupAction(Request $request)
    {
        $csrfToken = $this->container->has('form.csrf_provider') ? $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate') : null;
        $entity = new Users();
        $form = $this->createForm(new RegistrationFormType(), $entity, array(
            'action' => $this->generateUrl('users_create'),
            'method' => 'POST',
        ));

        if ('POST' === $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                $tokenGenerator = $this->container->get('fos_user.util.token_generator');
                $entity->setConfirmationToken($tokenGenerator->generateToken());
                $entity->setEnabled(0);
                $em = $this->getDoctrine()->getManager();
                $data = $form->getData();
                $em->persist($data->getOrganizations());
                $em->persist($data);
                $data->setRoles(array(DefaultController::$DEFAULT_ROLE));
                $data->getOrganizations()->setUsersCreated($data);
                $em->flush();

                $fieldsObj = new DefaultFields();
                $viewSettings = $fieldsObj->getDefaultOrder();

                $userSetting = new UserSettings();
                $userSetting->setUser($entity);
                $userSetting->setViewSetting($viewSettings);
                $userSetting->setCreatedOnValue(date('Y-m-d h:i:s'));
                $em->persist($userSetting);
                $em->flush();

                $url = $this->get('router')->generate('fos_user_registration_confirm', array('token' => $entity->getConfirmationToken()), true);
                $rendered = $this->renderView('FOSUserBundle:Registration:email.txt.twig', array(
                    'user' => $entity,
                    'confirmationUrl' => $url
                ));
                $this->sendEmailMessage($rendered, $this->container->getParameter('from_email'), $entity->getEmail());
                $this->get('session')->set('fos_user_send_confirmation_email/email', $entity->getEmail());

                return $this->redirect($this->generateUrl('fos_user_registration_check_email'));
            }
        }

        return $this->renderSignup(array(
                    'csrf_token' => $csrfToken,
                    'form' => $form->createView()
        ));
    }

    /**
     * Renders the signup template with the given parameters. Overwrite this function in
     * an extended controller to provide additional data for the login template.
     *
     * @param array $data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderSignup(array $data)
    {
        $template = sprintf('ApplicationFrontBundle:Default:signup.html.twig');

        return $this->container->get('templating')->renderResponse($template, $data);
    }

    /**
     * Send activation email to user.
     *
     * @param string $renderedTemplate
     * @param string $fromEmail
     * @param string $toEmail
     *
     * @return void
     */
    protected function sendEmailMessage($renderedTemplate, $fromEmail, $toEmail)
    {
        // Render the email, use the first line as the subject, and the rest as the body
        $renderedLines = explode("\n", trim($renderedTemplate));
        $subject = $renderedLines[0];
        $body = implode("\n", array_slice($renderedLines, 1));

        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($fromEmail)
                ->setTo($toEmail)
                ->setBody($body);
        $this->get('mailer')->send($message);
    }

}
