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
use Symfony\Component\Validator\Constraints as Assert;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * MonthlyCharges
 *
 * @ORM\Table(name="monthly_charges")
 * @ORM\Entity(repositoryClass="Application\Bundle\FrontBundle\Entity\MonthlyChargesRepository")
 * @ORM\HasLifecycleCallbacks
 */
class MonthlyCharges {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="start", type="integer")
     * @Assert\NotBlank(message="From is required")
     */
    private $start;

    /**
     * @var real
     *
     * @ORM\Column(name="end", type="integer")
     */
    private $end;

    /**
     * @var string
     *
     * @ORM\Column(name="charges", type="float")
     * @Assert\NotBlank(message="Charges are required")
     */
    private $charges;

    /**
     * @var \DateTime $createdOn
     *
     * @ORM\Column(name="created_on", type="datetime")
     */
    private $createdOn;

    /**
     * @var \DateTime $updatedOn
     *
     * @ORM\Column(name="updated_on", type="datetime", nullable=true)
     */
    private $updatedOn;

    /**
     * Get Id.
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get start.
     *
     * @return integer
     */
    public function getStart() {
        return $this->start;
    }

    /**
     * Set start.
     *
     * @param integer $start
     *
     * @return \Application\Bundle\FrontBundle\Entity\MonthlyCharges
     */
    public function setStart($start) {
        $this->start = $start;
        return $this;
    }

    /**
     * Get end.
     *
     * @return integer
     */
    public function getEnd() {
        return $this->end;
    }

    /**
     * Set end.
     *
     * @param integer $end
     *
     * @return \Application\Bundle\FrontBundle\Entity\MonthlyCharges
     */
    public function setEnd($end) {
        $this->end = $end;
        return $this;
    }

    /**
     * Get charges.
     *
     * @return float
     */
    public function getCharges() {
        return $this->charges;
    }

    /**
     * Set charges.
     *
     * @param float $charges
     *
     * @return \Application\Bundle\FrontBundle\Entity\MonthlyCharges
     */
    public function setCharges($charges) {
        $this->charges = $charges;
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
     * @ORM\PreUpdate
     */
    public function setUpdatedOnValue() {
        $this->updatedOn = new \DateTime();
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
     * Get Update on time.
     *
     * @return \Datetime
     */
    public function getUpdatedOn() {
        return $this->updatedOn;
    }

}

?>
