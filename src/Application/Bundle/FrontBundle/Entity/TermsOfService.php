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
 * TermsOfService
 *
 * @ORM\Table(name="terms_of_service")
 * @ORM\Entity(repositoryClass="Application\Bundle\FrontBundle\Entity\TermsOfServiceRepository")
 * @ORM\HasLifecycleCallbacks
 */
class TermsOfService {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", options={"default" = 0}, nullable=true)
     */
    private $status;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_published", type="boolean", options={"default" = 0}, nullable=true)
     */
    private $isPublished;

    /**
     * @var string
     *
     * @ORM\Column(name="terms", type="text")
     */
    private $terms;

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
     * Get Id.
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get terms.
     *
     * @return string
     */
    public function getTerms() {
        return $this->terms;
    }

    /**
     * Set terms.
     *
     * @param string $terms
     *
     * @return \Application\Bundle\FrontBundle\Entity\TermsOfService
     */
    public function setTerms($terms) {
        $this->terms = $terms;
        return $this;
    }

    /**
     * Get status.
     *
     * @return integer
     */
    public function getStatus() {
        return (bool) $this->status;
    }

    /**
     * Set status.
     *
     * @param integer $status
     *
     * @return \Application\Bundle\FrontBundle\Entity\TermsOfService
     */
    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    /**
     * Get is Published.
     *
     * @return integer
     */
    public function getIsPublished() {
        return (bool) $this->isPublished;
    }

    /**
     * Set is Published.
     *
     * @param integer $isPublished
     *
     * @return \Application\Bundle\FrontBundle\Entity\TermsOfService
     */
    public function setIsPublished($isPublished) {
        $this->isPublished = $isPublished;
        return $this;
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

