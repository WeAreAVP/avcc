<?php

namespace Application\Bundle\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserSettings
 * @ORM\Entity(repositoryClass="Application\Bundle\FrontBundle\Entity\UserSettingsRepository")
 * @ORM\Table(name="user_settings")
 * @ORM\Entity
 */
class UserSettings
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
     * @ORM\ManyToOne(targetEntity="Users", cascade={"all","merge","persist","refresh","remove"}, fetch="EAGER", inversedBy="userSetting")
     * @ORM\JoinColumn(
     *     name="user_id",
     *     referencedColumnName="id",
     *     nullable=true,
     *     onDelete="CASCADE"
     * )
     * @var integer
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="view_setting", type="text")
     */
    private $viewSetting;

    /**
     * @ORM\ManyToOne(targetEntity="MediaTypes", fetch="EAGER", inversedBy="mediaSetting")
     * @ORM\JoinColumn(
     *     name="media_type_id",
     *     referencedColumnName="id",
     *     nullable=true,
     *     onDelete="SET NULL"
     * )
     * @var integer
     */
    private $mediaType;

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
     * Set mediaType.
     *
     * @param \Application\Bundle\FrontBundle\Entity\MediaTypes $mediaType
     *
     * @return \Application\Bundle\FrontBundle\Entity\UserSettings
     */
    public function setMediaType(\Application\Bundle\FrontBundle\Entity\MediaTypes $mediaType)
    {
        $this->mediaType = $mediaType;

        return $this;
    }

    /**
     * Get mediaType
     *
     * @return \Application\Bundle\FrontBundle\Entity\MediaTypes
     */
    public function getMediaType()
    {
        return $this->mediaType;
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
