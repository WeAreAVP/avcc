<?php

namespace Application\Bundle\FrontBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Application\Bundle\FrontBundle\SphinxSearch\SphinxSearch;

class SphinxCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('avcc:sphinx')
                ->setDescription('Insert all records in sphinx')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $records = $em->getRepository('ApplicationFrontBundle:Records')->findAll();
        $shpinxInfo = $this->getSphinxInfo();

        foreach ($records as $record) {
            $recordId = $record->getId();
            $sphinxSearch = new SphinxSearch($em, $shpinxInfo, $recordId);
            $result = $sphinxSearch->search();
            $output->writeln("record -- " . $recordId . ' count == '. count($result));
             if ($result) {
                 $output->writeln("record found");
             }else{
                 $output->writeln("record not found ");
             }
//            if (count($result) == 0) {
//                $sphinxSearch = new SphinxSearch($em, $shpinxInfo, $recordId , $record->getMediaType()->getId());
//                $sphinxSearch->insert();
//                $output->writeln("Inserted record -- " . $recordId . '<br />');
//            } else {
//                $sphinxSearch = new SphinxSearch($em, $shpinxInfo, $recordId,  $record->getMediaType()->getId());
//                $sphinxSearch->replace();
//                $output->writeln("Updated record -- " . $recordId . '<br />');
//            }
        }
        exit;
    }

    /**
     * Get sphinx parameters
     *
     * @return array
     */
    protected function getSphinxInfo() {
        return $this->getContainer()->getParameter('sphinx_param');
    }

}
