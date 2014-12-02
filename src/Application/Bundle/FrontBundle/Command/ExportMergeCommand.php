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
                ->setDescription('Export and merge the records that are in queue and email to user.')
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
            $entity = $em->getRepository('ApplicationFrontBundle:ImportExport')->findOneBy(array('id' => $id,'type'=>'export_merge', 'status' => 0));
            if ($entity) {
                
            }
        }
    }
}
