<?php

namespace Application\Bundle\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FieldSettings
 * @ORM\Entity(repositoryClass="Application\Bundle\FrontBundle\Entity\FieldSettingsRepository")
 * @ORM\Table(name="field_settings")
 * @ORM\HasLifecycleCallbacks
 */
class FieldSettings
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Application\Bundle\FrontBundle\Entity\Users
     * 
     * @ORM\ManyToOne(targetEntity="Application\Bundle\FrontBundle\Entity\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable = false)
     * })
     */
    private $user;
    
    /**
     * @var \Application\Bundle\FrontBundle\Entity\Projects
     *
     * @ORM\OneToOne(targetEntity="Application\Bundle\FrontBundle\Entity\Projects")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable = false)
     * })
     */
    private $project;

    /**
     * @var string
     *
     * @ORM\Column(name="view_setting", type="text")
     */
    private $viewSetting;


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
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get view setting.
     *
     * @return string
     */
    public function getViewSetting()
    {
        return $this->viewSetting;
    }

    /**
     * Set name.
     *
     * @param string $viewSetting
     *
     * @return \Application\Bundle\FrontBundle\Entity\UserSettings
     */
    public function setViewSetting($viewSetting)
    {
        $this->viewSetting = $viewSetting;

        return $this;
    }

    /**
     * Set user.
     *
     * @param \Application\Bundle\FrontBundle\Entity\Users $user
     *
     * @return \Application\Bundle\FrontBundle\Entity\UserSettings
     */
    public function setUser(\Application\Bundle\FrontBundle\Entity\Users $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Application\Bundle\FrontBundle\Entity\Users
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set media diameter.
     *
     * @param \Application\Bundle\FrontBundle\Entity\Projects $project
     *
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function setProject(Projects $project) {
        $this->project = $project;

        return $this;
    }

    /**
     * Get media diameter.
     *
     * @return \Application\Bundle\FrontBundle\Entity\Projects
     */
    public function getProject() {
        return $this->project;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedOnValue()
    {
        if (!$this->getCreatedOn()) {
            $this->createdOn = new \DateTime();
        }
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedOnValue()
    {
        $this->updatedOn = new \DateTime();
    }

    /**
     * Get Created on time.
     *
     * @return \Datetime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * Get Update on time.
     *
     * @return \Datetime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

}
