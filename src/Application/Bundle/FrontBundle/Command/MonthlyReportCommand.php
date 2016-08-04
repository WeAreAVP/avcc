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
use Application\Bundle\FrontBundle\Entity\MonthlyChargeReport;
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
        $entities = $em->getRepository('ApplicationFrontBundle:MonthlyChargeReport')->findAll();
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
            $years = $em->getRepository('ApplicationFrontBundle:MonthlyChargeReport')->getAllYears($organization['id']);
            $total = 0;
            if ($years) {
                foreach ($years as $year) {
                    $records = $em->getRepository('ApplicationFrontBundle:MonthlyChargeReport')->getRecordsForMonthlyCharges($organization['id'], $year['year'], $status, date('Y-m', strtotime('now')));
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
                            $chargeReport = new MonthlyChargeReport();
                            $chargeReport->setMonth($record['month']);
                            $chargeReport->setChargeRate($charge);
                            $chargeReport->setOrganizationId($organization['id']);
                            $chargeReport->setTotalRecords($total);
                            $chargeReport->setYear($year['year']);
                            $em->persist($chargeReport);
                        }
                    }
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
            $records = $em->getRepository('ApplicationFrontBundle:MonthlyChargeReport')->getRecordsForMonthlyCharges($organization['id'], $year, $status, date('Y-m', strtotime('last day of previous month')));
            $m_records = $em->getRepository('ApplicationFrontBundle:MonthlyChargeReport')->findBy(array('organizationId' => $organization['id']));
            $last_count = 0;
            if ($m_records) {
                $last_count = $m_records[count($m_records) - 1]->getTotalRecords();
            }
            if ($records) {
                $index = count($records) - 1;
                $total = 0;
                if (strtolower($records[$index]['month']) == strtolower($month) && $records[$index]['total'] > 0) {
                    $total = $last_count + (int) $records[$index]['total'];
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
                    $chargeReport = new MonthlyChargeReport();
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
