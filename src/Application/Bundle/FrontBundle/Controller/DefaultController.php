<?php

/**
 * AVCC
 * 
 * @category AVCC
 * @package  Application
 * @author   Nouman Tayyab <nouman@weareavp.com>
 * @author   Rimsha Khalid <rimsha@weareavp.com>
 * @license  AGPLv3 http://www.gnu.org/licenses/agpl-3.0.txt
 * @copyright Audio Visual Preservation Solutions, Inc
 * @link     http://avcc.weareavp.com
 */

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
use Application\Bundle\FrontBundle\Entity\OrganizationTerms as OrganizationTerms;
use Application\Bundle\FrontBundle\SphinxSearch\SphinxSearch;
use Application\Bundle\FrontBundle\Helper\EmailHelper;
use Application\Bundle\FrontBundle\Helper\StripeHelper;
use Application\Bundle\FrontBundle\Entity\MonthlyChargeReport;
use JMS\JobQueueBundle\Entity\Job;
use DateInterval;
use DateTime;

/**
 * Default controller.
 *
 */
class DefaultController extends Controller {

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
    public function getParent() {
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
    public function indexAction() {
        $check = 0;
        $user = $this->container->get('security.context')->getToken()->getUser();
        $showTerms = false;
        $contact_person = "avcc@weareavp.com";
        $notification = "";
        $terms = "";
        $cancelBySAdmin = false;
        $em = $this->getDoctrine()->getManager();
        if (!in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles())) {
            $orgId = $user->getOrganizations()->getId();
            $activeRecord = $em->getRepository('ApplicationFrontBundle:TermsOfService')->findBy(array('status' => 1));
            if (count($activeRecord) > 0) {
                $show = false;
                $orgTerms = $em->getRepository('ApplicationFrontBundle:OrganizationTerms')->findBy(array('organizationId' => $orgId, 'termsOfServiceId' => $activeRecord[0]->getId()));
                if (empty($orgTerms)) {
                    $entity = new OrganizationTerms();
                    $entityManager = $this->getDoctrine()->getManager();
                    $entity->setTermsOfServiceId($activeRecord[0]->getId());
                    $entity->setIsAccepted(0);
                    $entity->setOrganizationId($orgId);
                    $entityManager->persist($entity);
                    $entityManager->flush();
                    $show = true;
                } else {
                    if (!$orgTerms[0]->getIsAccepted()) {
                        $show = true;
                    }
                }
                if ($show) {
                    $showTerms = true;
                    $terms = $activeRecord[0]->getTerms();
                }
            }
            $fieldsObj = new DefaultFields();
            $paidOrg = $fieldsObj->paidOrganizations($orgId, $em);
            if ($paidOrg && $this->container->getParameter("enable_stripe")) {
                $free = 2500;
                $freePlan = $em->getRepository('ApplicationFrontBundle:Plans')->findOneBy(array("amount" => 0));
                if (!empty($freePlan)) {
                    $free = $freePlan->getRecords();
                }
                $organization = $em->getRepository('ApplicationFrontBundle:Organizations')->find($orgId);
                $creator = $organization->getUsersCreated();
                $customerId = $creator->getStripeCustomerId();
                $org_records = $em->getRepository('ApplicationFrontBundle:Records')->countOrganizationRecords($orgId);
                $counter = $org_records['total'];
                if (($counter > $free && ($organization->getIsPaid() == 0 || ($customerId == NULL || $customerId == ""))) || $organization->getCancelSubscription() == 1) {
                    $notification = "cancel";
                    $creator = $this->getUser()->getOrganizations()->getUsersCreated();
                    if (in_array("ROLE_ADMIN", $creator->getRoles())) {
                        $contact_person = $creator->getEmail();
                    }
                    if ($contact_person == $this->getUser()->getEmail()) {
                        $contact_person = "";
                    }
                    if ($this->getUser()->getOrganizations()->getCancelSubscription() == 1) {
                        $contact_person = "avcc@weareavp.com";
                        $cancelBySAdmin = true;
                    }
                } else if ($counter == $free && $organization->getIsPaid() == 0) {
                    $notification = "not-cancel";
                }
            }
        }
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
            $this->redirect($this->generateUrl("application_front"));
        }

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
        $session = $this->get('session');
        if (!$session->has('display_message')) {
            if (in_array("ROLE_MANAGER", $this->getUser()->getRoles()) || in_array("ROLE_ADMIN", $this->getUser()->getRoles())) {
                $check = $this->getUser()->getMessageDisplay();
                $session->set('display_message', 0);
            } else {
                $em = $this->getDoctrine()->getManager();
                $entity = $em->getRepository('ApplicationFrontBundle:Users')->find($this->getUser()->getId());
                $entity->setMessageDisplay(0);
                $em->persist($entity);
                $em->flush();
            }
        }
        return $this->render('ApplicationFrontBundle:Default:index.html.twig', array(
                    'name' => $user->getUsername(),
                    'projects' => $projects,
                    'formats' => json_encode($formatsChart),
                    'check' => $check,
                    'showTerms' => $showTerms,
                    'terms' => $terms,
                    'notification' => $notification,
                    'contact_person' => $contact_person,
                    'cancelBySAdmin' => $cancelBySAdmin,
                    'user' => $this->getUser()
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
    public function loginAction(Request $request) {
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
    protected function renderLogin(array $data) {
        $template = sprintf('ApplicationFrontBundle:Default:login.html.twig');

        return $this->container->get('templating')->renderResponse($template, $data);
    }

    /**
     * @throws \RuntimeException
     */
    public function checkAction() {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    /**
     * Login function
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return type
     */
    public function signupAction(Request $request) {
        $csrfToken = $this->container->has('form.csrf_provider') ? $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate') : null;
        $entity = new Users();
        $terms = "";
        $show = false;
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new RegistrationFormType(), $entity, array(
            'action' => $this->generateUrl('users_create'),
            'method' => 'POST',
        ));
        $activeRecord = $em->getRepository('ApplicationFrontBundle:TermsOfService')->findBy(array('status' => 1));
        if (count($activeRecord) > 0) {
            $terms = $activeRecord[0]->getTerms();
            $show = true;
        }
        if ('POST' === $request->getMethod()) {
            $form->bind($request);
            $termStatus = $request->request->get('application_user_registration');
            if ($form->isValid()) {
                $tokenGenerator = $this->container->get('fos_user.util.token_generator');
                $entity->setConfirmationToken($tokenGenerator->generateToken());
                $entity->setEnabled(0);
                $data = $form->getData();
                $em->persist($data->getOrganizations());
                $em->persist($data);
                $data->setRoles(array(DefaultController::$DEFAULT_ROLE));
                $data->getOrganizations()->setUsersCreated($data);
                $em->flush();
                $url = $this->get('router')->generate('fos_user_registration_confirm', array('token' => $entity->getConfirmationToken()), true);
                $rendered = $this->renderView('FOSUserBundle:Registration:email.txt.twig', array(
                    'user' => $entity,
                    'confirmationUrl' => $url
                ));
                $this->sendEmailMessage($rendered, $this->container->getParameter('from_email'), $entity->getEmail());
                $this->get('session')->set('fos_user_send_confirmation_email/email', $entity->getEmail());
                if (count($activeRecord) > 0 && $termStatus['termStatus']) {
                    $organizationTerms = new OrganizationTerms();
                    $entityManager = $this->getDoctrine()->getManager();
                    $organizationTerms->setTermsOfServiceId($activeRecord[0]->getId());
                    $organizationTerms->setIsAccepted(1);
                    $organizationTerms->setOrganizationId($entity->getOrganizations()->getId());
                    $organizationTerms->setUserId($entity->getId());
                    $entityManager->persist($organizationTerms);
                    $entityManager->flush();
                }
                return $this->redirect($this->generateUrl('fos_user_registration_check_email'));
            }
        }

        return $this->renderSignup(array(
                    'csrf_token' => $csrfToken,
                    'form' => $form->createView(),
                    'show' => $show,
                    'terms' => $terms
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
    protected function renderSignup(array $data) {
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
    protected function sendEmailMessage($renderedTemplate, $fromEmail, $toEmail) {
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

    /**
     * update message display settings
     *
     * @param Request $request
     *
     * @Route("/message/", name="message_settings")
     * @Method("POST")
     * @Template()
     */
    public function updateMessageSettingAction(Request $request) {
        if ($request->request->get('check_message')) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationFrontBundle:Users')->find($this->getUser()->getId());
            $entity->setMessageDisplay(0);
            $em->persist($entity);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('dashboard'));
    }

    /**
     * update term of service in organization
     *
     * @param Request $request
     *
     * @Route("/accepted/", name="accept_terms")
     * @Method("POST")
     * @Template()
     */
    public function updateTermsOfServiceAction(Request $request) {
        if ($request->request->get('accepted')) {
            $session = $this->getRequest()->getSession();
            $user = $this->container->get('security.context')->getToken()->getUser();
            $em = $this->getDoctrine()->getManager();
            $orgId = $user->getOrganizations()->getId();
            $activeRecord = $em->getRepository('ApplicationFrontBundle:TermsOfService')->findBy(array('status' => 1));
            $entity = $em->getRepository('ApplicationFrontBundle:OrganizationTerms')->findBy(array('organizationId' => $orgId, 'termsOfServiceId' => $activeRecord[0]->getId()));
            $entity[0]->setIsAccepted(1);
            $entity[0]->setUserId($user->getId());
            $em->persist($entity[0]);
            $em->flush();
            if ($session->has('termsStatus'))
                $session->remove('termsStatus');
            return $this->redirect($this->generateUrl('dashboard'));
        } else {
            return $this->redirect($this->generateUrl('fos_user_security_logout'));
        }
    }

    /**
     * show term of service
     *
     * @param Request $request
     *
     * @Route("/termsofservice", name="show_terms")
     * @Method("GET")
     * @Template()
     */
    public function showTermsOfServiceAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $active = array();
        $activeRecord = $em->getRepository('ApplicationFrontBundle:TermsOfService')->findBy(array('status' => 1));
        $entities = $em->getRepository('ApplicationFrontBundle:TermsOfService')->findBy(array('isPublished' => 1, 'status' => 0), array('createdOn' => 'DESC'));
        if (count($activeRecord) > 0) {
            $active = $activeRecord[0];
        }
        return $this->render('ApplicationFrontBundle:Default:show.html.twig', array(
                    'entities' => $entities,
                    'active' => $active
                        )
        );
    }

    /**
     * show Privacy Policy
     *
     * @param Request $request
     *
     * @Route("/privacy-policy", name="privacy_policy")
     * @Method("GET")
     * @Template()
     */
    public function privacy_policy(Request $request) {
        return $this->render('ApplicationFrontBundle:Default:privacy_policy.html.twig', array(
                    
                        )
        );
    }

    /**
     * Cancel subscription.
     *
     * @param Request $request
     *
     * @Route("/webhooks", name="webhook")
     * @Method("POST")
     */
    public function webhooks(Request $request) {
        $input = @file_get_contents("php://input");
        $event_json = json_decode($input, true);
        $em = $this->getDoctrine()->getManager();
        $helper = new StripeHelper($this->container);
        echo $event_json["type"];
        if ($event_json["type"] == "invoice.payment_succeeded") {
            $customerId = $event_json["data"]["object"]["customer"];
            $card = $helper->getCardInfo($customerId);
            $datetime1 = new DateTime($card["exp_year"] . '-' . $card["exp_month"]);
            $datetime2 = new DateTime(date('Y-m', strtotime('first day of next month')));
            $interval = $datetime2->diff($datetime1);
            $difference = (int) $interval->format('%R%a');
            if ($difference <= 0) {
                $expiration = new DateTime('today +20 days');
                $user = $em->getRepository('ApplicationFrontBundle:Users')->findOneBy(array('stripeCustomerId' => $customerId));
                $job = new Job('avcc:stripe-notification', array('id' => $user->getId(), 'type' => 'card-expire'));
                $job->setExecuteAfter($expiration);
                $em->persist($job);
                $em->flush($job);
            }
            $this->generateBillingReport($event_json, $em);
        } else if ($event_json["type"] == "customer.subscription.deleted") {// || $event_json["type"] == "customer.deleted"
            if (isset($event_json["data"]["object"]["customer"])) {
                $customerId = $event_json["data"]["object"]["customer"];
            } else {
                $customerId = $event_json["data"]["object"]["id"];
            }
            $user = $em->getRepository('ApplicationFrontBundle:Users')->findOneBy(array('stripeCustomerId' => $customerId));
            if ($user) {
                $job = new Job('avcc:stripe-notification', array('id' => $user->getId(), 'type' => 'subscription-cancel'));
                $date = new DateTime();
                $date->add(new DateInterval('PT5M'));
                $job->setExecuteAfter($date);
                $em->persist($job);
                $em->flush($job);
            }
        } else if ($event_json["type"] == "charge.succeeded") {
            $charge_id = $event_json["data"]["object"]["id"];
            $customer_id = $event_json["data"]["object"]["customer"];
            $user = $em->getRepository('ApplicationFrontBundle:Users')->findOneBy(array('stripeCustomerId' => $customer_id));
            if ($user && $user->getReceiptRecipients() != NULL) {
                $data["id"] = $charge_id;
                $data["emails"] = $user->getReceiptRecipients();
                $helper->updateCharge($data);
                echo "sent receipt to: " . $data["emails"];
            }
        }
        http_response_code(200);
        exit;
    }

    private function generateBillingReport($object, $em) {
        $customerId = $object["data"]["object"]["customer"];
        $date = $object["data"]["object"]["date"];
        $amount = $object["data"]["object"]["total"];
        $user = $em->getRepository('ApplicationFrontBundle:Users')->findOneBy(array('stripeCustomerId' => $customerId));
        if ($user) {
//            echo $user->getId() . " ". $user->getOrganizations()->getId();
//            exit;
            $plan = $em->getRepository('ApplicationFrontBundle:Plans')->findOneBy(array("planId" => $user->getStripePlanId()));
            $org_records = $em->getRepository('ApplicationFrontBundle:Records')->countOrganizationRecords($user->getOrganizations()->getId());
            $total = $org_records['total'];
            $chargeReport = new MonthlyChargeReport();
            $chargeReport->setChargeAt(date('Y-m-d', $date));
            $chargeReport->setChargeAmount((int) $amount / 100);
            $chargeReport->setOrganizationId($user->getOrganizations()->getId());
            $chargeReport->setPlans($plan);
            $chargeReport->setTotalRecords($total);
            $chargeReport->setYear(date('Y', $date));
            $em->persist($chargeReport);
            $em->flush($chargeReport);
            echo 'done billing <br>';
        }
    }

}
