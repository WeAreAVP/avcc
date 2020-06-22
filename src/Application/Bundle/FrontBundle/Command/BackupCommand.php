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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Application\Bundle\FrontBundle\Components\ExportReport;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Application\Bundle\FrontBundle\Helper\EmailHelper;
use Application\Bundle\FrontBundle\Entity\UserSettings;

class BackupCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('avcc:backup-report')
                ->setDescription('backup of records')
//                ->addArgument('userId', InputArgument::REQUIRED, 'user id required')
//                ->addOption('yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $entity = $em->getRepository('ApplicationFrontBundle:UserSettings')->findBy(array('enableBackup' => 1));
        if ($entity) {

            foreach ($entity as $record) {
                $completePath = '';
                $notFound = '';
                $backupEmails = $record->getBackupEmail();
                $emailTo = $this->getEmailTo($backupEmails, $record);
                $records = false;
                $output->writeln("Running Record with ID: {$record->getId()}");
                if ($record->getUser()->getOrganizations()) {
                    $output->writeln("Organization ID: {$record->getUser()->getOrganizations()->getId()}");
                    $records = $em->getRepository('ApplicationFrontBundle:Records')->findOrganizationRecords($record->getUser()->getOrganizations()->getId());
                    if ($records) {
                        $output->writeln("Processing records for Organization ID: {$record->getUser()->getOrganizations()->getId()}");
                        $export = new ExportReport($this->getContainer());
                        $phpExcelObject = $export->generateReport($records);
                        $completePath = $export->saveReport('csv', $phpExcelObject, 2);
                        $text = $completePath;
                        $phpExcelObject = null;
                    } else {
                        $output->writeln("No Record found for Organization ID: {$record->getUser()->getOrganizations()->getId()}");
                        $notFound = "Records not found for Organization {$record->getUser()->getOrganizations()->getName()}";
                    }
                    $baseUrl = $this->getContainer()->getParameter('baseUrl');
                    $templateParameters = array('user' => $record->getUser(), 'baseUrl' => $baseUrl, 'fileUrl' => $completePath, 'notFound' => $notFound);

                    $rendered = $this->getContainer()->get('templating')->render('ApplicationFrontBundle:Records:export.email.html.twig', $templateParameters);
                    $email = new EmailHelper($this->getContainer());
                    $subject = 'AVCC - Record Backup';
                    foreach ($emailTo as $emailId) {
                        $email->sendEmail($rendered, $subject, $this->getContainer()->getParameter('from_email'), trim($emailId));
                    }
                    $text = $rendered;
                } else {
                    $output->writeln("No organization found for Record {$record->getId()}");
                }
            }
        } else {
            $text = 'No backup settings availables.';
        }
    }

    public function getEmailTo($backupEmails, $record) {
        $return = array();
        if (empty($backupEmails) || $backupEmails == NULL) {
            $return[] = $record->getUser()->getEmail();
        } else {
            $return = explode(',', $backupEmails);
        }

        return $return;
    }

}
