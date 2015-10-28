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

/**
 * OrganizationTerms
 *
 * @ORM\Table(name="organization_terms")
 * @ORM\Entity(repositoryClass="Application\Bundle\FrontBundle\Entity\OrganizationTermsRepository")
 * @ORM\HasLifecycleCallbacks
 */
class OrganizationTerms {

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
     * @ORM\Column(name="organization_id", type="integer")
     */
    private $organizationId;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=true)
     */
    private $userId;

    /**
     * @var int
     *
     * @ORM\Column(name="is_accepted", type="boolean", options={"default" = 0}, nullable=true)
     */
    private $isAccepted = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="terms_of_service_id", type="integer", nullable=true)
     */
    private $termsOfServiceId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_on", type="datetime")
     */
    private $createdOn;

    /**
     * @var \DateTime
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
     * Get Accepted terms of service
     *
     * @return int
     */
    public function getIsAccepted() {
        return (bool) $this->isAccepted;
    }

    /**
     * Set  Accepted
     *
     * @param int $isAccepted
     */
    public function setIsAccepted($isAccepted) {
        $this->isAccepted = $isAccepted;
    }

    /**
     * Get active terms Of Service id
     *
     * @return int
     */
    public function getTermsOfServiceId() {
        return $this->termsOfServiceId;
    }

    /**
     * Set  terms Of Service Id
     *
     * @param int $termsOfServiceId
     */
    public function setTermsOfServiceId($termsOfServiceId) {
        $this->termsOfServiceId = $termsOfServiceId;
    }

    /**
     * Get organization Id
     *
     * @return int
     */
    public function getOrganizationId() {
        return $this->organizationId;
    }

    /**
     * Set organization Id
     *
     * @param int $organizationId
     */
    public function setOrganizationId($organizationId) {
        $this->organizationId = $organizationId;
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

    /*   
     * Get user Id
     *
     * @return int
     */

    public function getUserId() {
        return $this->userId;
    }

    /**
     * Set user Id
     *
     * @param int $userId
     */
    public function setUserId($userId) {
        $this->userId = $userId;
    }
    
    
    /**
     * Get Created on.
     *
     * @return \Datetime
     */
    public function getCreatedOn() {
        return $this->createdOn;
    }

    /**
     * Get Updated on.
     *
     * @return \Datetime
     */
    public function getUpdatedOn() {
        return $this->updatedOn;
    }

}

