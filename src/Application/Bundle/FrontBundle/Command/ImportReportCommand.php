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
        @set_time_limit(0);
        @ini_set("memory_limit", "3000M"); # 1GB
        @ini_set("max_execution_time", 0); # unlimited
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $id = $input->getArgument('id');
       
        if ($id) {
            $entity = $em->getRepository('ApplicationFrontBundle:ImportExport')->findOneBy(array('id' => $id, 'type' => 'import', 'status' => 0));
            if ($entity) {

                $fileName = $entity->getFileName();
                $user = $entity->getUser();
                
                $organization = $em->getRepository('ApplicationFrontBundle:Organizations')->find($entity->getOrganizationId());
                $insertType = $entity->getInsertOption();
                $import = new ImportReport($this->getContainer());
                $existingRows = 0;
                $rows = (int) $import->getTotalRows($fileName);
                if ($insertType != 0) {
                    $existingRows = (int) $entity->getExistingRecords();
                    $rows = $rows - $existingRows;
                }
                $_import = true;
                $fieldsObj = new DefaultFields();
                if ($organization && $this->getContainer()->getParameter("enable_stripe")) {
                    $paidOrg = $fieldsObj->paidOrganizations($organization->getId(), $em);
                    if ($paidOrg || is_array($paidOrg)) {
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
                    $validateFields = $import->validateVocabulary($fileName, $entity->getOrganizationId(), true);
                    if ($validateFields) {
                        $baseUrl = $this->getContainer()->getParameter('baseUrl');
                        $templateParameters = array('user' => $entity->getUser(), 'fieldErrors' => $validateFields);
                    } else { 
                        $numberOfRecords = $import->getRecordsFromFile($fileName, $user, $insertType, $entity->getOrganizationId());
                         
                        if (isset($numberOfRecords['errors'])) {
                            $templateParameters = array('user' => $entity->getUser(), 'errors' => $numberOfRecords['errors']);
                        } else if ($numberOfRecords >= 0) {
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
                $em->flush($entity);
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
