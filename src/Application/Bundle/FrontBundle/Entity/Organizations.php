<?php

namespace Application\Bundle\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Organizations
 */
class Organizations
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $department_name;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $contact_person_name;

    /**
     * @var string
     */
    private $contact_person_email;

    /**
     * @var string
     */
    private $contact_person_phone;

    /**
     * @var \DateTime
     */
    private $created_on;

    /**
     * @var \DateTime
     */
    private $updated_on;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * 
     * @return Organizations
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set department_name
     *
     * @param string $departmentName
     * 
     * @return Organizations
     */
    public function setDepartmentName($departmentName)
    {
        $this->department_name = $departmentName;

        return $this;
    }

    /**
     * Get department_name
     *
     * @return string 
     */
    public function getDepartmentName()
    {
        return $this->department_name;
    }

    /**
     * Set address
     *
     * @param string $address
     * 
     * @return Organizations
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set contact_person_name
     *
     * @param string $contactPersonName
     * 
     * @return Organizations
     */
    public function setContactPersonName($contactPersonName)
    {
        $this->contact_person_name = $contactPersonName;

        return $this;
    }

    /**
     * Get contact_person_name
     *
     * @return string 
     */
    public function getContactPersonName()
    {
        return $this->contact_person_name;
    }

    /**
     * Set contact_person_email
     *
     * @param string $contactPersonEmail
     * 
     * @return Organizations
     */
    public function setContactPersonEmail($contactPersonEmail)
    {
        $this->contact_person_email = $contactPersonEmail;

        return $this;
    }

    /**
     * Get contact_person_email
     *
     * @return string 
     */
    public function getContactPersonEmail()
    {
        return $this->contact_person_email;
    }

    /**
     * Set contact_person_phone
     *
     * @param string $contactPersonPhone
     * 
     * @return Organizations
     */
    public function setContactPersonPhone($contactPersonPhone)
    {
        $this->contact_person_phone = $contactPersonPhone;

        return $this;
    }

    /**
     * Get contact_person_phone
     *
     * @return string 
     */
    public function getContactPersonPhone()
    {
        return $this->contact_person_phone;
    }

    /**
     * Set created_on
     *
     * @param \DateTime $createdOn
     * 
     * @return Organizations
     */
    public function setCreatedOn($createdOn)
    {
        $this->created_on = $createdOn;
        return $this;
    }

    /**
     * Get created_on
     *
     * @return \DateTime 
     */
    public function getCreatedOn()
    {
        return $this->created_on;
    }

    /**
     * Set updated_on
     *
     * @param \DateTime $updatedOn
     * @return Organizations
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updated_on = $updatedOn;
        return $this;
    }

    /**
     * Get updated_on
     *
     * @return \DateTime 
     */
    public function getUpdatedOn()
    {
        return $this->updated_on;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedOnValue()
    {
        if ( ! $this->getCreatedOn())
        {
            $this->created_on = new \DateTime();
        }
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedOnValue()
    {
        $this->updated_on = new \DateTime();
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
     * @var \Application\Bundle\FrontBundle\Entity\Users
     */
    private $created_by;

    /**
     * @var \Application\Bundle\FrontBundle\Entity\Users
     */
    private $updated_by;

    /**
     * Set created_by
     *
     * @param \Application\Bundle\FrontBundle\Entity\Users $createdBy
     * 
     * @return Organizations
     */
    public function setCreatedBy(\Application\Bundle\FrontBundle\Entity\Users $createdBy = null)
    {
        $this->created_by = $createdBy;

        return $this;
    }

    /**
     * Get created_by
     *
     * @return \Application\Bundle\FrontBundle\Entity\Users 
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * Set updated_by
     *
     * @param \Application\Bundle\FrontBundle\Entity\Users $updatedBy
     * 
     * @return Organizations
     */
    public function setUpdatedBy(\Application\Bundle\FrontBundle\Entity\Users $updatedBy = null)
    {
        $this->updated_by = $updatedBy;

        return $this;
    }

    /**
     * Get updated_by
     *
     * @return \Application\Bundle\FrontBundle\Entity\Users 
     */
    public function getUpdatedBy()
    {
        return $this->updated_by;
    }

    /**
     * @var \Application\Bundle\FrontBundle\Entity\Users
     */
    private $users_created;

    /**
     * @var \Application\Bundle\FrontBundle\Entity\Users
     * 
     */
    private $users_updated;

    /**
     * Set users_created
     *
     * @param \Application\Bundle\FrontBundle\Entity\Users $usersCreated
     * 
     * @return Organizations
     */
    public function setUsersCreated(\Application\Bundle\FrontBundle\Entity\Users $usersCreated = null)
    {
        $this->users_created = $usersCreated;

        return $this;
    }

    /**
     * Get users_created
     *
     * @return \Application\Bundle\FrontBundle\Entity\Users 
     * 
     */
    public function getUsersCreated()
    {
        return $this->users_created;
    }

    /**
     * Set users_updated
     *
     * @param \Application\Bundle\FrontBundle\Entity\Users $usersUpdated
     * 
     * @return Organizations
     */
    public function setUsersUpdated(\Application\Bundle\FrontBundle\Entity\Users $usersUpdated = null)
    {
        $this->users_updated = $usersUpdated;

        return $this;
    }

    /**
     * Get users_updated
     *
     * @return \Application\Bundle\FrontBundle\Entity\Users 
     */
    public function getUsersUpdated()
    {
        return $this->users_updated;
    }

}
