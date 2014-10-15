<?php

namespace Application\Bundle\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ReelDiameters
 *
 * @ORM\Table(name="reel_diameters")
 * @ORM\Entity
 */
class ReelDiameters
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
     * @Assert\NotBlank(message="Reel diameter name is required")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Formats", cascade={"all","merge","persist","refresh","remove"}, fetch="EAGER", inversedBy="reelDiameter")
     * @ORM\JoinColumn(
     *     name="format_id",
     *     referencedColumnName="id",
     *     nullable=false,
     *     onDelete="CASCADE"
     * )
     * @var integer 
     */
    private $reelFormat;

    /**
     * @ORM\ManyToOne(targetEntity="Organizations", cascade={"all","merge","persist","refresh","remove"}, fetch="EAGER", inversedBy="reelDiameterOrg")
     * @ORM\JoinColumn(
     *     name="organization_id",
     *     referencedColumnName="id",
     *     nullable=true,
     *     onDelete="CASCADE"
     * )
     * @var integer 
     */
    private $organization;
    
    /**
     * Returns reel diameter
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
     * Set name.
     *
     * @param string $name
     *
     * @return \Application\Bundle\FrontBundle\Entity\ReelDiameters
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set reel formats
     * 
     * @param \Application\Bundle\FrontBundle\Entity\Formats $reelFormat
     * 
     * @return \Application\Bundle\FrontBundle\Entity\ReelDiameters
     */
    public function setReelFormat(\Application\Bundle\FrontBundle\Entity\Formats $reelFormat)
    {
        $this->reelFormat = $reelFormat;

        return $this;
    }

    /**
     * Get reel formats
     * 
     * @return \Application\Bundle\FrontBundle\Entity\Formats
     */
    public function getReelFormat()
    {
        return $this->reelFormat;
    }

    /**
     * Set organization.
     *
     * @param \Application\Bundle\FrontBundle\Entity\Organizations $organization
     *
     * @return \Application\Bundle\FrontBundle\Entity\ReelDiameters
     */
    public function setOrganization(\Application\Bundle\FrontBundle\Entity\Organizations $organization)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization
     * 
     * @return \Application\Bundle\FrontBundle\Entity\Organizations
     */
    public function getOrganization()
    {
        return $this->organization;
    }
}
