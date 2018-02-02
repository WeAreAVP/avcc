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

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * Users
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Application\Bundle\FrontBundle\Entity\UsersRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields="username", message="Username already in use.")
 * @UniqueEntity(fields="email", message="Email already in use.")
 */
class Users extends BaseUser {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

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
     * @var \Application\Bundle\FrontBundle\Entity\Organizations
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\FrontBundle\Entity\Organizations")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="organization_id", referencedColumnName="id",nullable=true, onDelete="CASCADE")
     * })
     */
    private $organizations;

    /**
     * @var \Application\Bundle\FrontBundle\Entity\Users $usersCreated
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\FrontBundle\Entity\Users",cascade={"refresh"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="created_by", referencedColumnName="id",nullable=true, onDelete="SET NULL")
     * })
     */
    private $usersCreated;

    /**
     * @var \Application\Bundle\FrontBundle\Entity\Users $usersUpdated
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\FrontBundle\Entity\Users",cascade={"refresh"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="updated_by", referencedColumnName="id",nullable=true, onDelete="SET NULL")
     * })
     */
    private $usersUpdated;

    /**
     * @ORM\OneToMany(
     *     targetEntity="UserSettings",
     *     mappedBy="user",
     *     fetch="EAGER",
     *     indexBy="user_id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $userSetting;

    /**
     * @ORM\ManyToMany(targetEntity="Application\Bundle\FrontBundle\Entity\Projects", inversedBy="projectUsers", cascade={"refresh", "persist"})
     * @ORM\JoinTable(
     *     name="users_projects",
     *     joinColumns={
     *         @ORM\JoinColumn(
     *             name="userId",
     *             referencedColumnName="id",
     *             nullable=false,
     *             onDelete="CASCADE"
     *         )
     *     },
     *     inverseJoinColumns={@ORM\JoinColumn(name="projectId", referencedColumnName="id", nullable=false, onDelete="CASCADE")}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $userProjects;

    /**
     * @var int
     *
     * @ORM\Column(name="message_display", type="boolean", options={"default" = 1}, nullable=true)
     */
    private $messageDisplay = 1;

    /**
     * @var string $stripeCustomerId
     *
     * @ORM\Column(name="stripe_customer_id", type="string", nullable=true)
     */
    private $stripeCustomerId;

    /**
     * @var string $stripeSubscribeId
     *
     * @ORM\Column(name="stripe_subscribe_id", type="string", nullable=true)
     */
    private $stripeSubscribeId;

    /**
     * @var string $stripePlanId
     *
     * @ORM\Column(name="stripe_plan_id", type="string", nullable=true)
     */
    private $stripePlanId;
    
    
    /**
     * @var string $stripePlanId
     *
     * @ORM\Column(name="receipt_recipients", type="text", nullable=true)
     */
    private $receiptRecipients;
    

    /**
     * Users constructor
     */
    public function __construct() {
        $this->userSetting = new ArrayCollection();
        $this->userProjects = new ArrayCollection();
        parent::__construct();
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
     * Returns user name
     *
     * @return string
     */
    public function __toString() {
        return $this->getName();
    }

    /**
     * Get Name of user.
     *
     * @return string
     */
    public function getName() {
        return $this->name;
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

    /**
     * Get user organization.
     *
     * @return \Application\Bundle\FrontBundle\Entity\Organizations
     */
    public function getOrganizations() {
        return $this->organizations;
    }

    /**
     * Get user created.
     *
     * @return \Application\Bundle\FrontBundle\Entity\Users
     */
    public function getUsersCreated() {
        return $this->usersCreated;
    }

    /**
     * Get user updated.
     *
     * @return \Application\Bundle\FrontBundle\Entity\Users
     */
    public function getUsersUpdated() {
        return $this->usersUpdated;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return \Application\Bundle\FrontBundle\Entity\Users
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Set Created on.
     *
     * @param \DateTime $createdOn
     *
     * @return \Application\Bundle\FrontBundle\Entity\Users
     */
    public function setCreatedOn(\DateTime $createdOn) {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * Set update on.
     *
     * @param \DateTime $updatedOn
     *
     * @return \Application\Bundle\FrontBundle\Entity\Users
     */
    public function setUpdatedOn(\DateTime $updatedOn) {
        $this->updatedOn = $updatedOn;

        return $this;
    }

    /**
     * Set Organization.
     *
     * @param \Application\Bundle\FrontBundle\Entity\Organizations $organizations
     *
     * @return \Application\Bundle\FrontBundle\Entity\Users
     */
    public function setOrganizations(\Application\Bundle\FrontBundle\Entity\Organizations $organizations) {
        $this->organizations = $organizations;

        return $this;
    }

    public function removeOrganizations() {
        $this->organizations = NULL;
        return $this;
    }

    /**
     * Set creator.
     *
     * @param \Application\Bundle\FrontBundle\Entity\Users $usersCreated
     *
     * @return \Application\Bundle\FrontBundle\Entity\Users
     */
    public function setUsersCreated(\Application\Bundle\FrontBundle\Entity\Users $usersCreated) {
        $this->usersCreated = $usersCreated;

        return $this;
    }

    /**
     * Set modifier.
     *
     * @param \Application\Bundle\FrontBundle\Entity\Users $usersUpdated
     *
     * @return \Application\Bundle\FrontBundle\Entity\Users
     */
    public function setUsersUpdated(\Application\Bundle\FrontBundle\Entity\Users $usersUpdated) {
        $this->usersUpdated = $usersUpdated;

        return $this;
    }

    /**
     * Add userSetting
     * @param \Application\Bundle\FrontBundle\Entity\UserSettings $us
     *
     */
    public function addUserSetting(UserSettings $us) {
        if (!$this->userSetting->contains($us)) {

            $this->userSetting[] = $us;
            $us->setUser($this);
        }
    }

    /**
     * Remove userSetting
     * @param \Application\Bundle\FrontBundle\Entity\UserSettings $us
     *
     */
    public function removeUserSetting(UserSettings $us) {
        $this->userSetting->remove($us);
    }

    /**
     * Add user project
     * @param \Application\Bundle\FrontBundle\Entity\Projects $project
     *
     */
    public function addUserProjects(\Application\Bundle\FrontBundle\Entity\Projects $project) {
        if (!$this->userProjects->contains($project)) {

            $this->userProjects[] = $project;
            $project->setProjectUsers($this);
        }
    }

    /**
     * Remove user project
     * @param \Application\Bundle\FrontBundle\Entity\Projects $project
     *
     */
    public function removeUserProjects(\Application\Bundle\FrontBundle\Entity\Projects $project) {
        $this->userProjects->removeElement($project);
    }

    /**
     *
     * @param  \Application\Bundle\FrontBundle\Entity\Projects $p
     * @return \Application\Bundle\FrontBundle\Entity\Users
     */
    public function setUserProjects(\Application\Bundle\FrontBundle\Entity\Projects $p) {
        $this->userProjects->add($p);
        return $this;
    }

    /**
     *
     * @return type
     */
    public function getUserProjects() {
        return $this->userProjects;
    }

    /**
     * Get  Enable Backup
     *
     * @return int
     */
    public function getMessageDisplay() {
        return (bool) $this->messageDisplay;
    }

    /**
     * Set  Enable Backup
     *
     * @param int $enableBackup
     */
    public function setMessageDisplay($messageDisplay) {
        $this->messageDisplay = $messageDisplay;
    }

    /**
     * Get  Enable Backup
     *
     * @return int
     */
    public function getStripeCustomerId() {
        return $this->stripeCustomerId;
    }

    /**
     * Set  Enable Backup
     *
     * @param int $enableBackup
     */
    public function setStripeCustomerId($stripeCustomerId) {
        $this->stripeCustomerId = $stripeCustomerId;
    }

    /**
     * Get  Enable Backup
     *
     * @return int
     */
    public function getStripeSubscribeId() {
        return $this->stripeSubscribeId;
    }

    /**
     * Set  Enable Backup
     *
     * @param int $enableBackup
     */
    public function setStripeSubscribeId($stripeCustomerId) {
        $this->stripeSubscribeId = $stripeCustomerId;
    }

    /**
     * Get  Enable Backup
     *
     * @return int
     */
    public function getStripePlanId() {
        return $this->stripePlanId;
    }

    /**
     * Set  Enable Backup
     *
     * @param int $enableBackup
     */
    public function setStripePlanId($stripePlanId) {
        $this->stripePlanId = $stripePlanId;
    }
            
            /**
     * Get  Enable Backup
     *
     * @return int
     */
    public function getReceiptRecipients() {
        return $this->receiptRecipients;
    }

    /**
     * Set  Enable Backup
     *
     * @param int $receiptRecipients
     */
    public function setReceiptRecipients($receiptRecipients) {
        $this->receiptRecipients = $receiptRecipients;
    }

}
