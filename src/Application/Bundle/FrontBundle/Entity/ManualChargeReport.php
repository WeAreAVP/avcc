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

namespace Application\Bundle\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * MonthlyCharges
 *
 * @ORM\Table(name="manual_charge_report")
 * @ORM\Entity(repositoryClass="Application\Bundle\FrontBundle\Entity\ManualChargeReportRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ManualChargeReport {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_records", type="integer")
     */
    private $totalRecords;

    /**
     * @var string
     *
     * @ORM\Column(name="month", type="string")
     */
    private $month;

    /**
     * @var integer
     *
     * @ORM\Column(name="year", type="integer")
     */
    private $year;

    /**
     * @var float
     *
     * @ORM\Column(name="charge_rate", type="float")
     */
    private $chargeRate;

    /**
     * @var integer
     *
     * @ORM\Column(name="organization_id", type="integer")
     */
    private $organizationId;

    /**
     * @var \DateTime $createdOn
     *
     * @ORM\Column(name="created_on", type="datetime")
     */
    private $createdOn;

    /**
     * Get Id.
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get total Records.
     *
     * @return integer
     */
    public function getTotalRecords() {
        return $this->totalRecords;
    }

    /**
     * Set total Records.
     *
     * @param integer $start
     *
     * @return \Application\Bundle\FrontBundle\Entity\MonthlyChargeReport
     */
    public function setTotalRecords($totalRecords) {
        $this->totalRecords = $totalRecords;
        return $this;
    }

    /**
     * Get month.
     *
     * @return integer
     */
    public function getMonth() {
        return $this->month;
    }

    /**
     * Set month.
     *
     * @param integer $month
     *
     * @return \Application\Bundle\FrontBundle\Entity\MonthlyChargeReport
     */
    public function setMonth($month) {
        $this->month = $month;
        return $this;
    }

    /**
     * Get year.
     *
     * @return float
     */
    public function getYear() {
        return $this->charges;
    }

    /**
     * Set year.
     *
     * @param float $year
     *
     * @return \Application\Bundle\FrontBundle\Entity\MonthlyChargeReport
     */
    public function setYear($year) {
        $this->year = $year;
    }

    /**
     * Get Charge Rate.
     *
     * @return float
     */
    public function getChargeRate() {
        return $this->chargeRate;
    }

    /**
     * Set Charge Rate.
     *
     * @param float $ChargeRate
     *
     * @return \Application\Bundle\FrontBundle\Entity\MonthlyChargeReport
     */
    public function setChargeRate($ChargeRate) {
        $this->chargeRate = $ChargeRate;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedOnValue() {
        if (!$this->getCreatedOn()) {
            $this->createdOn = new \DateTime();
        }
    }

    /**
     * Get Created on time.
     *
     * @return \Datetime
     */
    public function getCreatedOn() {
        return $this->createdOn;
    }

    /**
     * Get Organization Id.
     *
     * @return float
     */
    public function getOrganizationId() {
        return $this->organizationId;
    }

    /**
     * Set Organization Id.
     *
     * @param float $year
     *
     * @return \Application\Bundle\FrontBundle\Entity\MonthlyChargeReport
     */
    public function setOrganizationId($OrganizationId) {
        $this->organizationId = $OrganizationId;
    }

}

?>
