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

namespace Application\Bundle\FrontBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Application\Bundle\FrontBundle\Components\ImportReport;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Application\Bundle\FrontBundle\Helper\EmailHelper;
use Application\Bundle\FrontBundle\Helper\DefaultFields;

class ImportReportCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('avcc:import-report')
                ->setDescription('Import the Records that are in queue and email to user.')
                ->addArgument(
                        'id', InputArgument::REQUIRED, ' import db id?'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        @set_time_limit(0);
        @ini_set("memory_limit", "1000M"); # 1GB
        @ini_set("max_execution_time", 0); # unlimited
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $id = $input->getArgument('id');
        if ($id) {
            $entity = $em->getRepository('ApplicationFrontBundle:ImportExport')->findOneBy(array('id' => $id, 'type' => 'import', 'status' => 0));
            if ($entity) {
                $import = new ImportReport($this->getContainer());
                $user = $entity->getUser();
                $fileName = $entity->getFileName();
                $organization = $em->getRepository('ApplicationFrontBundle:Organizations')->find($entity->getOrganizationId());
                $rows = $import->getTotalRows($fileName) - 1;
                $_import = true;
                $fieldsObj = new DefaultFields();
                
                if ($organization) {
                    $paidOrg = $fieldsObj->paidOrganizations($organization->getId());
                    if ($paidOrg) {
                        $plan_limit = 2500;
                        $plan_id = "";
                        $org_records = $em->getRepository('ApplicationFrontBundle:Records')->findOrganizationRecords($entity->getOrganizationId());
                        $counter = count($org_records) + $rows;
                        $creator = $organization->getUsersCreated();
                        $contact_person = "avcc@avpreserve.com";
                        if (in_array("ROLE_ADMIN", $creator->getRoles())) {
                            $plan_id = $creator->getStripePlanId();
                            $contact_person = $creator->getEmail();
                        }
                        if ($plan_id != NULL && $plan_id != "") {
                            $plan = $em->getRepository('ApplicationFrontBundle:Plans')->findBy(array("planId" => $plan_id));
                            $plan_limit = $plan[0]->getRecords();
                        }
                        if ($counter > $plan_limit) {
                            $_import = false;
                            $templateParameters = array('user' => $entity->getUser(), 'organization' => $organization->getName(), 'plan_limit' => $plan_limit, 'contact_person' => $contact_person);
                        }
                    }
                }
                if ($_import) {
                    $validateFields = $import->validateVocabulary($fileName, $entity->getOrganizationId());
                    if ($validateFields) {
                        $baseUrl = $this->getContainer()->getParameter('baseUrl');
                        $templateParameters = array('user' => $entity->getUser(), 'fieldErrors' => $validateFields);
                    } else {
                        $numberOfRecords = $import->getRecordsFromFile($fileName, $user);
                        if (isset($numberOfRecords['errors'])) {
                            $templateParameters = array('user' => $entity->getUser(), 'errors' => $numberOfRecords['errors']);
                        } else if ($numberOfRecords) {
                            $baseUrl = $this->getContainer()->getParameter('baseUrl');
                            $templateParameters = array('user' => $entity->getUser(), 'numberOfRecords' => $numberOfRecords);
                        }
                    }
                }
                $rendered = $this->getContainer()->get('templating')->render('ApplicationFrontBundle:Records:import.email.html.php', $templateParameters);
                $email = new EmailHelper($this->getContainer());
                $subject = 'Import Report';
                $email->sendEmail($rendered, $subject, $this->getContainer()->getParameter('from_email'), $user->getEmail());
                $entity->setStatus(1);
                $em->persist($entity);
                $em->flush();
                $text = $rendered;
            } else {
                $text = 'import id not found';
            }
        } else {
            $text = 'Hello';
        }

        $output->writeln($text);

        return true;
    }

    
}
