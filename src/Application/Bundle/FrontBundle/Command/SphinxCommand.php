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
        $shpinxInfo = $this->getSphinxInfo();

        $records = $em->getRepository('ApplicationFrontBundle:Records')->findAll();
//        
        foreach ($records as $record) {
            $recordId = $record->getId();
            $sphinxSearch = new SphinxSearch($em, $shpinxInfo, $recordId);

            $result = $sphinxSearch->search();
            if (count($result) == 0) {
                $sphinxSearch = new SphinxSearch($em, $shpinxInfo, $recordId, $record->getMediaType()->getId());
                $sphinxSearch->insert();
                $output->writeln("Inserted record -- " . $recordId);
            } else {
                $sphinxSearch = new SphinxSearch($em, $shpinxInfo, $recordId, $record->getMediaType()->getId());
                $sphinxSearch->replace();
                $output->writeln("Updated record -- " . $recordId);
            }
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
