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

namespace Application\Bundle\FrontBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Application\Bundle\FrontBundle\Components\ExportReport;
use Application\Bundle\FrontBundle\Helper\EmailHelper;
use Application\Bundle\FrontBundle\Helper\SphinxHelper;
use Application\Bundle\FrontBundle\Helper\StripeHelper;

class StripeCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('avcc:stripe-notification')
                ->setDescription('Stripe Notification')
                ->addArgument('id', InputArgument::REQUIRED, ' User Id is required')
                ->addArgument('type', InputArgument::REQUIRED, ' Notification type is required');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $helper = new StripeHelper($this->getContainer());
        $id = $input->getArgument('id');
        $type = $input->getArgument('type');
        $user = $em->getRepository('ApplicationFrontBundle:Users')->find($id);
        $customerId = $user->getStripeCustomerId();
        if ($type == "card-expire") {
            $card = $helper->getCardInfo($customerId);
            $message = "Your card (" . $card["last4"] . ") will expire by " . $card["exp_year"] . '-' . $card["exp_month"] . ". Please update your card or your organization will be suspended.";
            $templateParameters = array('user' => $user, 'message' => $message);
            $rendered = $this->getContainer()->get('templating')->render('ApplicationFrontBundle:Plans:notification.email.html.twig', $templateParameters);
            $email = new EmailHelper($this->getContainer());
            $subject = 'Card Expire Notification';
            $email->sendEmail($rendered, $subject, $this->getContainer()->getParameter('from_email'), $user->getEmail());
        } else {
            if ($customerId != NULL && $customerId != "" && $user->getStripeSubscribeId() != NULL && $user->getStripeSubscribeId() != "") {
                $res = $helper->deleteCustomer($customerId);
                if ($res == TRUE) {
                    $free = 2500;
                    $freePlan = $em->getRepository('ApplicationFrontBundle:Plans')->findOneBy(array("amount" => 0));
                    if (!empty($freePlan)) {
                        $free = $freePlan->getRecords();
                    }
                    $org_records = $em->getRepository('ApplicationFrontBundle:Records')->countOrganizationRecords($user->getOrganizations()->getId());
                    $counter = $org_records['total'];
                    if ($counter > $free) {
                        $rendered = $this->sendOrgRecords($user, $em);
                    }                    
                    $organization = $user->getOrganizations();
                    $organization->setIsPaid(0);
                    $em->persist($organization);
                    $user->setStripeCustomerId(NULL);
                    $user->setStripePlanId(NULL);
                    $user->setStripeSubscribeId(NULL);
                    $em->persist($user);
                    $em->flush();
                    $output->writeln($rendered);
                } else {
                    $output->writeln($res);
                }
            } else if ($user->getOrganizations()->getCancelSubscription() == 1 || ($customerId != NULL && $customerId != "")) {
                $rendered = $this->sendOrgRecords($user, $em);
                $user->setStripeCustomerId(NULL);
                $em->persist($user);
                $em->flush();
                $output->writeln($rendered);
            }
        }
        $output->writeln("Process completed");
    }

    private function sendOrgRecords($user, $em) {
        $export = new ExportReport($this->getContainer());
        $_cri = '{"criteria":{"org_filter":"","organization_name":["' . $user->getOrganizations()->getId() . '"],"formt_filter":"","collection_filter":"","project_filter":"","is_review_check":"0","is_reformatting_priority_check":"0","parent_facet":"organization_name","total_checked":"1","facet_keyword_search":""}}';
        $criteria = json_decode($_cri, true);
        $search = $criteria['criteria'];
        $sphinxCriteria = null;
        if ($search != 'all' && is_array($search)) {
            if ($search['total_checked'] > 0 || count($search['facet_keyword_search']) > 0) {
                $sphinxHelper = new SphinxHelper();
                $allCriteria = $sphinxHelper->makeSphinxCriteria($search);
                $sphinxCriteria = $allCriteria['criteriaArr'];
            }
        }
        $sphinxInfo = $this->getContainer()->getParameter('sphinx_param');
        $phpExcelObject = $export->fetchFromSphinx($user, $sphinxInfo, $sphinxCriteria, $em);
        $completePath = $export->saveReport('xlsx', $phpExcelObject, 2);
        $message = "Your Organization " . $user->getOrganizations()->getName() . " has been suspended.";
        $baseUrl = $this->getContainer()->getParameter('baseUrl');
        $templateParameters = array('user' => $user, 'baseUrl' => $baseUrl, 'fileUrl' => $completePath, 'message' => $message);
        $rendered = $this->getContainer()->get('templating')->render('ApplicationFrontBundle:Plans:notification.email.html.twig', $templateParameters);
        $email = new EmailHelper($this->getContainer());
        $subject = 'Account Suspended Notification';
        $email->sendEmail($rendered, $subject, $this->getContainer()->getParameter('from_email'), $user->getEmail());
        return $rendered;
    }

}
