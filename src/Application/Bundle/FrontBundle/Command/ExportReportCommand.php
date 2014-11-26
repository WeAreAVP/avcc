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

class ExportReportCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
                ->setName('avcc:export-report')
                ->setDescription('Export the Records that are in queue and email to user.')
                ->addArgument(
                        'id', InputArgument::REQUIRED, ' export db id?'
                )
                ->addOption(
                        'yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters'
                )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $id = $input->getArgument('id');
        if ($id) {
            $entity = $em->getRepository('ApplicationFrontBundle:ImportExport')->findOneBy(array('id' => $id, 'status' => 0));
            $text = $entity;
            if ($entity) {
                $ids = json_decode($entity->getQueryOrId());
                $recIds = implode(',', $ids);
                $records = $em->getRepository('ApplicationFrontBundle:Records')->findRecordsByIds($recIds);
                $text = $records;    
                if ($_records) {
                    $export = new ExportReport($this->getContainer());
                    $phpExcelObject = $export->generateReport($records);
                    $completePath = $export->saveReport($entity->getFormat(), $phpExcelObject);
                    $text = $completePath;
                }else{
                    $text = 'records not found';
                }
            } else {
                $text = 'export id not found';
            }
        } else {
            $text = 'Hello';
        }

        if ($input->getOption('yell')) {
            $text = strtoupper($text);
        }

        $output->writeln($text);
    }

}
