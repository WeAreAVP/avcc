<?php

namespace Application\Bundle\FrontBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Application\Bundle\FrontBundle\Components\ExportReport;
use Application\Bundle\FrontBundle\SphinxSearch\SphinxSearch;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Application\Bundle\FrontBundle\Helper\EmailHelper;
use Application\Bundle\FrontBundle\Helper\SphinxHelper;

class ExportMergeCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
                ->setName('avcc:export-merge-report')
                ->setDescription('Merge and export the records that are in queue and email to user.')
                ->addArgument(
                        'id', InputArgument::REQUIRED, ' export db id?'
                )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $id = $input->getArgument('id');
        if ($id) {
            $entity = $em->getRepository('ApplicationFrontBundle:ImportExport')->findOneBy(array('id' => $id, 'type' => 'export_merge', 'status' => 0));
            if ($entity) {
                if ($entity->getQueryOrId() != 'all') {
                    $criteria = json_decode($entity->getQueryOrId(), true);
                } else {
                    $criteria = $entity->getQueryOrId();
                }
                $export = new ExportReport($this->getContainer());

                if ($criteria != 'all' && array_key_exists('ids', $criteria)) {
                    $records = $em->getRepository('ApplicationFrontBundle:Records')->findRecordsByIds($criteria['ids']);
                    if ($records) {
                        $mergeToFile = $entity->getMergeToFile();
                        $phpExcelObject = $export->megerRecords($records, $mergeToFile);
                        $completePath = $export->saveReport($entity->getFormat(), $phpExcelObject);
                        $text = $completePath;
                        if ($completePath) {
                            $baseUrl = $this->getContainer()->getParameter('baseUrl');
                            $templateParameters = array('user' => $entity->getUser(), 'baseUrl' => $baseUrl, 'fileUrl' => $completePath);
                            $rendered = $this->getContainer()->get('templating')->render('ApplicationFrontBundle:Records:export.email.html.twig', $templateParameters);
                            $email = new EmailHelper($this->getContainer());
                            $subject = 'Record Export';
                            $email->sendEmail($rendered, $subject, $this->getContainer()->getParameter('from_email'), $user->getEmail());
                            $entity->setStatus(1);
                            $em->persist($entity);
                            $em->flush();
                            $text = $rendered;
                        }
                    } else {
                        $text = 'records not found';
                    }
                }
            }
        }
        $output->writeln($text);
        return true;
    }

}
