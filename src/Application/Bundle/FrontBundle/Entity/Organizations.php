<?php

namespace Application\Bundle\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Organizations
 *
 * @ORM\Table(name="organizations")
 * @ORM\Entity(repositoryClass="Application\Bundle\FrontBundle\Entity\OrganizationRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Organizations
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50)
     * @Assert\NotBlank(message="Organization name is required")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="department_name", type="string", nullable=true)
     */
    private $departmentName;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_person_name", type="string", length=255, nullable=true)
     */
    private $contactPersonName;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_person_email", type="string", length=255, nullable=true)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     */
    private $contactPersonEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_person_phone", type="string", length=255, nullable=true)
     */
    private $contactPersonPhone;

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
     * @var \Application\Bundle\FrontBundle\Entity\Users
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\FrontBundle\Entity\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     * })
     */
    private $usersCreated;

    /**
     * @var \Application\Bundle\FrontBundle\Entity\Users
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\FrontBundle\Entity\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="updated_by", referencedColumnName="id")
     * })
     */
    private $usersUpdated;

    /**
     * @ORM\PrePersist
     */
    public function setCreatedOnValue()
    {
        if ( ! $this->getCreatedOn()) {
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
     * Returns Orgnaizaition name
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

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
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get department Name.
     *
     * @return string
     */
    public function getDepartmentName()
    {
        return $this->departmentName;
    }

    /**
     * Get Address.
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Get Contact person name.
     *
     * @return string
     */
    public function getContactPersonName()
    {
        return $this->contactPersonName;
    }

    /**
     * Get Contact person email.
     *
     * @return string
     */
    public function getContactPersonEmail()
    {
        return $this->contactPersonEmail;
    }

    /**
     * Get Contact person phone.
     *
     * @return string
     */
    public function getContactPersonPhone()
    {
        return $this->contactPersonPhone;
    }

    /**
     * Get Created on.
     *
     * @return \Datetime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * Get Updated on.
     *
     * @return \Datetime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * Get Creater.
     *
     * @return \Application\Bundle\FrontBundle\Entity\Users
     */
    public function getUsersCreated()
    {
        return $this->usersCreated;
    }

    /**
     * Get Modifier.
     *
     * @return \Application\Bundle\FrontBundle\Entity\Users
     */
    public function getUsersUpdated()
    {
        return $this->usersUpdated;
    }

    /**
     * Set id.
     *
     * @param integer $id
     *
     * @return \Application\Bundle\FrontBundle\Entity\Organizations
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return \Application\Bundle\FrontBundle\Entity\Organizations
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set dapartment name.
     *
     * @param string $departmentName
     *
     * @return \Application\Bundle\FrontBundle\Entity\Organizations
     */
    public function setDepartmentName($departmentName)
    {
        $this->departmentName = $departmentName;

        return $this;
    }

    /**
     * Set Address.
     *
     * @param string $address
     *
     * @return \Application\Bundle\FrontBundle\Entity\Organizations
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Set contact person name.
     *
     * @param string $contactPersonName
     *
     * @return \Application\Bundle\FrontBundle\Entity\Organizations
     */
    public function setContactPersonName($contactPersonName)
    {
        $this->contactPersonName = $contactPersonName;

        return $this;
    }

    /**
     * Set contact person email.
     *
     * @param string $contactPersonEmail
     *
     * @return \Application\Bundle\FrontBundle\Entity\Organizations
     */
    public function setContactPersonEmail($contactPersonEmail)
    {
        $this->contactPersonEmail = $contactPersonEmail;

        return $this;
    }

    /**
     * Set contact person phone.
     *
     * @param string $contactPersonPhone
     *
     * @return \Application\Bundle\FrontBundle\Entity\Organizations
     */
    public function setContactPersonPhone($contactPersonPhone)
    {
        $this->contactPersonPhone = $contactPersonPhone;

        return $this;
    }

    /**
     * Set created on.
     *
     * @param \DateTime $createdOn
     *
     * @return \Application\Bundle\FrontBundle\Entity\Organizations
     */
    public function setCreatedOn(\DateTime $createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * Set updated on.
     *
     * @param \DateTime $updatedOn
     *
     * @return \Application\Bundle\FrontBundle\Entity\Organizations
     */
    public function setUpdatedOn(\DateTime $updatedOn)
    {
        $this->updatedOn = $updatedOn;

        return $this;
    }

    /**
     * Set creator.
     *
     * @param \Application\Bundle\FrontBundle\Entity\Users $usersCreated
     *
     * @return \Application\Bundle\FrontBundle\Entity\Organizations
     */
    public function setUsersCreated(\Application\Bundle\FrontBundle\Entity\Users $usersCreated)
    {
        $this->usersCreated = $usersCreated;

        return $this;
    }

    /**
     * Set modifier.
     *
     * @param \Application\Bundle\FrontBundle\Entity\Users $usersUpdated
     *
     * @return \Application\Bundle\FrontBundle\Entity\Organizations
     */
    public function setUsersUpdated(\Application\Bundle\FrontBundle\Entity\Users $usersUpdated)
    {
        $this->usersUpdated = $usersUpdated;

        return $this;
    }

}
