<?php

/**
 * AVCC
 * 
 * @category AVCC
 * @package  Application
 * @author   Nouman Tayyab <nouman@weareavp.com>
 * @author   Rimsha Khalid <rimsha@weareavp.com>
 * @license  AGPLv3 http://www.gnu.org/licenses/agpl-3.0.txt
 * @copyright Audio Visual Preservation Solutions, Inc
 * @link     http://avcc.weareavp.com
 */

namespace Application\Bundle\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Plans
 *
 * @ORM\Table(name="plans")
 * @ORM\Entity(repositoryClass="Application\Bundle\FrontBundle\Entity\PlansRepository")
 * @UniqueEntity(
 *     fields={"planId"},
 *     message="This Plan Id is already in use"
 * )
 */
class Plans {

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
     * @ORM\Column(name="name", type="string")
     * @Assert\NotBlank(message="Name is required")
     */
    private $name;

    /**
     * @var real
     *
     * @ORM\Column(name="plan_id", type="string", unique = true)
     * @Assert\NotBlank(message="Plan Id is required")
     */
    private $planId;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string") 
     * @Assert\Length(
     *      max = 22,
     *      maxMessage = "Statement descriptor cannot be longer than {{ limit }} characters"
     * )
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer")
     * @Assert\NotBlank(message="Amount is required")
     */
    private $amount;
    
        /**
     * @var integer
     *
     * @ORM\Column(name="records", type="integer")
     * @Assert\NotBlank(message="Records count is required")
     */
    private $records;

    /**
     * @var real
     *
     * @ORM\Column(name="currency", type="string")
     */
    private $currency;

    /**
     * @var real
     *
     * @ORM\Column(name="plan_interval", type="string")
     * @Assert\NotBlank(message="Plan Interval is required") 
     */
    private $planInterval;

    /**
     * Get Id.
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
     /**
     * Get title.
     *
     * @return string
     */
    public function getPlanId() {
        return $this->planId;
    }

    /**
     * Set name.
     *
     * @param string $title
     *
     * @return \Application\Bundle\FrontBundle\Entity\Plans
     */
    public function setName($title) {
        $this->name = $title;
        return $this;
    }

  
    /**
     * Set PartId.
     *
     * @param string $slug
     *
     * @return \Application\Bundle\FrontBundle\Entity\Plans
     */
    public function setPlanId($slug) {
        $this->planId = $slug;
        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return \Application\Bundle\FrontBundle\Entity\Plans
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * Get order
     *
     * @return integer
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     *
     * @return \Application\Bundle\FrontBundle\Entity\Plans
     */
    public function setAmount($amount) {
        $this->amount = $amount;
    }

     /**
     * Get order
     *
     * @return integer
     */
    public function getCurrency() {
        return $this->currency;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     *
     * @return \Application\Bundle\FrontBundle\Entity\Plans
     */
    public function setCurrency($amount) {
        $this->currency = $amount;
    }
    
     /**
     * Get order
     *
     * @return integer
     */
    public function getPlanInterval() {
        return $this->planInterval;
    }

    /**
     * Set amount
     *
     * @param integer $plan_interval
     *
     * @return \Application\Bundle\FrontBundle\Entity\Plans
     */
    public function setPlanInterval($plan_interval) {
        $this->planInterval = $plan_interval;
    }

    /**
     * Get order
     *
     * @return integer
     */
    public function getRecords() {
        return $this->records;
    }

    /**
     * Set amount
     *
     * @param integer $records
     *
     * @return \Application\Bundle\FrontBundle\Entity\Plans
     */
    public function setRecords($records) {
        $this->records = $records;
    }

}
