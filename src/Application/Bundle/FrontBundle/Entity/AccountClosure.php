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
 * UserSettings
 * @ORM\Entity(repositoryClass="Application\Bundle\FrontBundle\Entity\AccountClosureRepository")
 * @ORM\Table(name="account_closure")
 * @ORM\HasLifecycleCallbacks
 */
class AccountClosure {

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
     * @ORM\Column(name="reason", type="integer")
     * @Assert\NotBlank(message="Reason name is required")
     */
    private $reason;

    /**
     * @var string
     *
     * @ORM\Column(name="explanation", type="text")
     */
    private $explanation;

    /**
     * @var string
     *
     * @ORM\Column(name="other_service", type="string")
     */
    private $otherService;

    /**
     * @var string
     *
     * @ORM\Column(name="feedback", type="text")
     */
    private $feedback;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="\Application\Bundle\FrontBundle\Entity\Organizations",
     *     
     * )
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", nullable=true)
     */
    private $organization;

    /**
     * @var \DateTime
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
     * Set user.
     *
     * @param \Application\Bundle\FrontBundle\Entity\Users $org
     *
     * @return \Application\Bundle\FrontBundle\Entity\AccountClosure
     */
    public function setOrganization(\Application\Bundle\FrontBundle\Entity\Organizations $org) {
        $this->organization = $org;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Application\Bundle\FrontBundle\Entity\Organizations
     */
    public function getOrganization() {
        return $this->organization;
    }

    /**
     * Set mediaType.
     *
     * @param $reason
     *
     * @return \Application\Bundle\FrontBundle\Entity\AccountClosure
     */
    public function setReason($reason) {
        $this->reason = $reason;
    }

    /**
     * Get reason
     *
     * @return $reason
     */
    public function getReason() {
        return $this->reason;
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
     * Get  explanation
     *
     * @return int
     */
    public function getExplanation() {
        return $this->explanation;
    }

    /**
     * Set  explanation
     *
     * @param string $explanation
     */
    public function setExplanation($explanation) {
        $this->explanation = $explanation;
    }

    /**
     * get other Service
     *
     * @return string
     */
    public function getOtherService() {
        return $this->otherService;
    }

    /**
     * set other Service
     *
     * @param string $otherService
     */
    public function setOtherService($otherService) {
        $this->otherService = $otherService;
    }

    /**
     * get feedback
     *
     * @return string
     */
    public function getFeedback() {
        return $this->feedback;
    }

    /**
     * set feedback
     *
     * @param string $feedback
     */
    public function setFeedback($feedback) {
        $this->feedback = $feedback;
    }

}
