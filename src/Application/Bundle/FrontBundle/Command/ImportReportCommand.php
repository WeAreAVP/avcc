<?php

namespace Application\Bundle\FrontBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Application\Bundle\FrontBundle\Components\ImportReport;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Application\Bundle\FrontBundle\Helper\EmailHelper;

class ImportReportCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
                ->setName('avcc:import-report')
                ->setDescription('Import the Records that are in queue and email to user.')
                ->addArgument(
                        'id', InputArgument::REQUIRED, ' import db id?'
                )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $id = $input->getArgument('id');
        if ($id) {
            $entity = $em->getRepository('ApplicationFrontBundle:ImportExport')->findOneBy(array('id' => $id, 'type' => 'import', 'status' => 0));
            if ($entity) {
                $user = $entity->getUser();
                $fileName = $entity->getFileName();
                $import = new ImportReport($this->getContainer());
                $validateFields = $import->validateVocabulary($fileName, $entity->getOrganizationId());
                if ($validateFields) {
                    $baseUrl = $this->getContainer()->getParameter('baseUrl');
                    $templateParameters = array('user' => $entity->getUser(), 'fieldErrors' => $validateFields);
                } else {
                    $numberOfRecords = $import->getRecordsFromFile($fileName, $user);
                    if ($numberOfRecords) {
                        $baseUrl = $this->getContainer()->getParameter('baseUrl');
                        $templateParameters = array('user' => $entity->getUser(), 'numberOfRecords' => $numberOfRecords);
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
