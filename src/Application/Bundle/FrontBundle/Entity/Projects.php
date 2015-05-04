<?php

namespace Application\Bundle\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * Projects
 *
 * @ORM\Table(name="projects")
 * @ORM\Entity(repositoryClass="Application\Bundle\FrontBundle\Entity\ProjectsRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Projects {

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
     * @var string
     *
     * @ORM\Column(name="view_setting", type="text", nullable=true)
     */
    private $viewSetting;

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
     *   @ORM\JoinColumn(name="organization_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     * })
     */
    private $organization;

    /**
     * @var \Application\Bundle\FrontBundle\Entity\Users $usersCreated
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\FrontBundle\Entity\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="created_by", referencedColumnName="id" , nullable=true, onDelete="SET NULL")
     * })
     */
    private $usersCreated;

    /**
     * @var \Application\Bundle\FrontBundle\Entity\Users $usersUpdated
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\FrontBundle\Entity\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="updated_by", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * })
     */
    private $usersUpdated;

    /**
     * @var int
     * 
     * @ORM\Column(name="status", type="boolean", options={"default" = 1}, nullable=true)
     */
    private $status = 1;

    /**
     * @ORM\ManyToMany(targetEntity="Application\Bundle\FrontBundle\Entity\Users", mappedBy="userProjects")
     */
    private $projectUsers;

    public function __construct() {
        $this->projectUsers = new ArrayCollection();
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
     * Get id of project.
     *
     * @return string
     */
    public function getId() {
        return $this->id;
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
    public function getOrganization() {
        return $this->organization;
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
     * @param \Application\Bundle\FrontBundle\Entity\Organizations $organization
     *
     * @return \Application\Bundle\FrontBundle\Entity\Users
     */
    public function setOrganization(\Application\Bundle\FrontBundle\Entity\Organizations $organization) {
        $this->organization = $organization;

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
     * Add user project
     * @param \Application\Bundle\FrontBundle\Entity\Users $user
     *
     */
    public function addProjectUsers(\Application\Bundle\FrontBundle\Entity\Users $user) {
        if (!$this->projectUsers->contains($user)) {
            $this->projectUsers[] = $user;
            $user->setUserProjects($this);
        }
    }

    /**
     * Remove user project
     * @param \Application\Bundle\FrontBundle\Entity\Users $user
     *
     */
    public function removeProjectUsers(\Application\Bundle\FrontBundle\Entity\Users $user) {
        $this->projectUsers->remove($user);
    }

    /**
     *
     * @param  \Application\Bundle\FrontBundle\Entity\Users    $u
     * @return \Application\Bundle\FrontBundle\Entity\Projects
     */
    public function setProjectUsers(\Application\Bundle\FrontBundle\Entity\Users $u) {
        $this->projectUsers = $u;

        return $this;
    }

    /**
     *
     * @return type
     */
    public function getProjectUsers() {
        return $this->projectUsers;
    }

    /**
     * Get view setting.
     *
     * @return string
     */
    public function getViewSetting() {
        return $this->viewSetting;
    }

    /**
     * Set name.
     *
     * @param string $viewSetting
     *
     * @return \Application\Bundle\FrontBundle\Entity\UserSettings
     */
    public function setViewSetting($viewSetting) {
        $this->viewSetting = $viewSetting;

        return $this;
    }

    public function setStatus($boolean) {
        $this->status = $boolean;

        return $this;
    }

    public function getStatus() {
        return (bool) $this->status;
    }

}
