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
use Application\Bundle\FrontBundle\Entity\ManualChargeReport;
use JMS\JobQueueBundle\Entity\Job;
use DateTime;

class MonthlyReportCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('avcc:generate-monthly-report')
                ->setDescription('Generate monthly charge report')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $entities = $em->getRepository('ApplicationFrontBundle:ManualChargeReport')->findAll();
        if (count($entities) == 0) {
            $this->generatePreviousReport();
            $output->writeln("Previous charge report generated successfully.");
        } else {
            $this->generateMonthlyReport();
            $output->writeln("Monthly charge report generated successfully.");
        }
        $job = new Job($this->getName());
        $date = new DateTime(date('Y-m-d', strtotime('first day of next month')));
        $job->setExecuteAfter($date);
        $em->persist($job);
        $em->flush();
        exit;
    }

    /**
     * generate Previous Report
     *
     * @return array
     */
    protected function generatePreviousReport() {
        $status = true;
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $organizations = $em->getRepository('ApplicationFrontBundle:Organizations')->getAll();
        foreach ($organizations as $organization) {
            $total = 0;
            $records = $em->getRepository('ApplicationFrontBundle:ManualChargeReport')->getRecordsForMonthlyCharges($organization['id'], "2017", $status, date('Y-m', strtotime('now')));
            if ($records) {
                foreach ($records as $record) {
                    $total = $total + (int) $record['total'];
                    $charges = $em->getRepository('ApplicationFrontBundle:MonthlyCharges')->getByTotalRecord($total, TRUE);
                    if (count($charges) == 0) {
                        $charges = $em->getRepository('ApplicationFrontBundle:MonthlyCharges')->getByTotalRecord($total, FALSE);
                    } else {
                        $charge = $charges[0]['charges'];
                    }
                    if (count($charges) == 0) {
                        $charge = 0;
                    } else {
                        $charge = $charges[0]['charges'];
                    }
                    $chargeReport = new ManualChargeReport();
                    $chargeReport->setMonth($record['month']);
                    $chargeReport->setChargeRate($charge);
                    $chargeReport->setOrganizationId($organization['id']);
                    $chargeReport->setTotalRecords($total);
                    $chargeReport->setYear($record['year']);
                    $em->persist($chargeReport);
                }
            }
        }
        $em->flush();
    }

    /**
     * generate Monthly charge Report
     *
     * @return array
     */
    protected function generateMonthlyReport() {
        $status = false;
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $organizations = $em->getRepository('ApplicationFrontBundle:Organizations')->getAll();
        $month = date('F', strtotime('last day of previous month'));
        $year = date('Y', strtotime('last day of previous month'));
        foreach ($organizations as $organization) {
            $records = $em->getRepository('ApplicationFrontBundle:ManualChargeReport')->getRecordsForMonthlyCharges($organization['id'], $year, $status, date('Y-m', strtotime('last day of previous month')));

            if ($records) {
                if (strtolower($records[0]['month']) == strtolower($month) && $records[0]['total'] > 0) {
                    $total = $records[0]['total'];
                    $charges = $em->getRepository('ApplicationFrontBundle:MonthlyCharges')->getByTotalRecord($total, TRUE);
                    if (count($charges) == 0) {
                        $charges = $em->getRepository('ApplicationFrontBundle:MonthlyCharges')->getByTotalRecord($total, FALSE);
                    } else {
                        $charge = $charges[0]['charges'];
                    }
                    if (count($charges) == 0) {
                        $charge = 0;
                    } else {
                        $charge = $charges[0]['charges'];
                    }
                    $chargeReport = new ManualChargeReport();
                    $chargeReport->setMonth($month);
                    $chargeReport->setChargeRate($charge);
                    $chargeReport->setOrganizationId($organization['id']);
                    $chargeReport->setTotalRecords($total);
                    $chargeReport->setYear($year);
                    $em->persist($chargeReport);
                }
            }
        }
        $em->flush();
    }

}
