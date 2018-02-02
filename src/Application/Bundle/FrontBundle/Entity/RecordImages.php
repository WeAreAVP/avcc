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

/**
 * Colors
 *
 * @ORM\Table(name="record_images")
 * @ORM\Entity(repositoryClass="Application\Bundle\FrontBundle\Entity\RecordImagesRepository")
 * @ORM\HasLifecycleCallbacks
 */
class RecordImages {

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
     * @ORM\Column(name="record_id", type="integer")
     */ 
    private $recordId;

    /**
     * @var string
     *
     * @ORM\Column(name="aws_path", type="string")
     */
    private $awsPath;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string")
     */
    private $filename;

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
     * Returns color
     *
     * @return string
     */
    public function __toString() {
        return $this->getName();
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
     * Get name.
     *
     * @return string
     */
    public function getRecordId() {
        return $this->recordId;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return \Application\Bundle\FrontBundle\Entity\Colors
     */
    public function setRecordId($recordId) {
        $this->recordId = $recordId;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer
     */
    public function getAwsPath() {
        return $this->awsPath;
    }

    /**
     * Set order
     *
     * @param integer $order
     *
     * @return \Application\Bundle\FrontBundle\Entity\Colors
     */
    public function setAwsPath($awsPath) {
        $this->awsPath = $awsPath;
    }

    /**
     * Get order
     *
     * @return integer
     */
    public function getFilename() {
        return $this->filename;
    }

    /**
     * Set order
     *
     * @param integer $order
     *
     * @return \Application\Bundle\FrontBundle\Entity\Colors
     */
    public function setFilename($filename) {
        $this->filename = $filename;
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
