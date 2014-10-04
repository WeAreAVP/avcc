<?php

// src/Application/Bundle/FrontBundle/Entity/Users.php

namespace Application\Bundle\FrontBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use \Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * @ORM\Entity 
 * 
 * @ORM\Table(name="users")
 *
 */
class Users extends BaseUser
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \DateTime
     */
    private $created_on;

    /**
     * @var \DateTime
     */
    private $updated_on;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Override the mapping of setRoles
     * 
     * @param array $roles
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * Get Roles
     * @return type
     */
    public function getRoles()
    {
        return $this->roles;
    }

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
     * @return Users
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
     * Set created_on
     *
     * @param \DateTime $createdOn
     * @return Users
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
     * @return Users
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
     * @return Users
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
     * @return Users
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
     * @var \Application\Bundle\FrontBundle\Entity\Organizations
     */
    private $organizations;

    /**
     * Set organizations
     *
     * @param \Application\Bundle\FrontBundle\Entity\Organizations $organizations
     * @return Users
     */
    public function setOrganizations(\Application\Bundle\FrontBundle\Entity\Organizations $organizations = null)
    {
        $this->organizations = $organizations;

        return $this;
    }

    /**
     * Get organizations
     *
     * @return \Application\Bundle\FrontBundle\Entity\Organizations 
     */
    public function getOrganizations()
    {
        return $this->organizations;
    }

    /**
     * Add created_by
     *
     * @param \Application\Bundle\FrontBundle\Entity\Users $createdBy
     * @return Users
     */
    public function addCreatedBy(\Application\Bundle\FrontBundle\Entity\Users $createdBy)
    {
        $this->created_by[] = $createdBy;

        return $this;
    }

    /**
     * Remove created_by
     *
     * @param \Application\Bundle\FrontBundle\Entity\Users $createdBy
     */
    public function removeCreatedBy(\Application\Bundle\FrontBundle\Entity\Users $createdBy)
    {
        $this->created_by->removeElement($createdBy);
    }

    /**
     * Add updated_by
     *
     * @param \Application\Bundle\FrontBundle\Entity\Users $updatedBy
     * @return Users
     */
    public function addUpdatedBy(\Application\Bundle\FrontBundle\Entity\Users $updatedBy)
    {
        $this->updated_by[] = $updatedBy;

        return $this;
    }

    /**
     * Remove updated_by
     *
     * @param \Application\Bundle\FrontBundle\Entity\Users $updatedBy
     */
    public function removeUpdatedBy(\Application\Bundle\FrontBundle\Entity\Users $updatedBy)
    {
        $this->updated_by->removeElement($updatedBy);
    }

    /**
     * @var \Application\Bundle\FrontBundle\Entity\Users
     */
    private $users;

    /**
     * Set users
     *
     * @param \Application\Bundle\FrontBundle\Entity\Users $users
     * @return Users
     */
    public function setUsers(\Application\Bundle\FrontBundle\Entity\Users $users = null)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * Get users
     *
     * @return \Application\Bundle\FrontBundle\Entity\Users 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @var \Application\Bundle\FrontBundle\Entity\Users
     */
    private $users_created;

    /**
     * @var \Application\Bundle\FrontBundle\Entity\Users
     */
    private $users_updated;

    /**
     * Set users_created
     *
     * @param \Application\Bundle\FrontBundle\Entity\Users $usersCreated
     * @return Users
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
     */
    public function getUsersCreated()
    {
        return $this->users_created;
    }

    /**
     * Set users_updated
     *
     * @param \Application\Bundle\FrontBundle\Entity\Users $usersUpdated
     * @return Users
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
