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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Application\Bundle\FrontBundle\Components\ExportReport;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Application\Bundle\FrontBundle\Helper\EmailHelper;
use Application\Bundle\FrontBundle\Helper\SphinxHelper;

class ExportReportCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('avcc:export-report')
                ->setDescription('Export the Records that are in queue and email to user.')
                ->addArgument(
                        'id', InputArgument::REQUIRED, ' export db id?'
                )
//                ->addOption(
//                        'yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters'
//                )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $id = $input->getArgument('id');
        if ($id) {
            $entity = $em->getRepository('ApplicationFrontBundle:ImportExport')->findOneBy(array('id' => $id, 'type' => 'export', 'status' => 0));
            if ($entity) {

                $completePath = '';
                $user = $entity->getUser();

                if ($entity->getQueryOrId() != 'all') {
                    $criteria = json_decode($entity->getQueryOrId(), true);
                } else {
                    $criteria = $entity->getQueryOrId();
                }
                $export = new ExportReport($this->getContainer());

                if ($criteria != 'all' && array_key_exists('ids', $criteria)) {
                    $records = $em->getRepository('ApplicationFrontBundle:Records')->findRecordsByIds($criteria['ids']);
                    if ($records) {
                        $phpExcelObject = $export->generateReport($records);
                        $completePath = $export->saveReport($entity->getFormat(), $phpExcelObject, 2);
                        $text = $completePath;
                    } else {
                        $text = 'records not found';
                    }
                } else {
                    $search = isset($criteria['criteria']) ? $criteria['criteria'] : 'all';
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
                    $completePath = $export->saveReport($entity->getFormat(), $phpExcelObject, 2);
                    $text = $completePath;
                }
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
                $text = 'export id not found';
            }
        } else {
            $text = 'Hello';
        }

//        if ($input->getOption('yell')) {
//            $text = strtoupper($text);
//        }

        $output->writeln($text);

        return true;
    }

}
