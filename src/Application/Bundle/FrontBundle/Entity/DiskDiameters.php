<?php

namespace Application\Bundle\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DiskDiameters
 *
 * @ORM\Table(name="disk_diameters")
 * @ORM\Entity
 */
class DiskDiameters
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
     * @Assert\NotBlank(message="Disk diameter name is required")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Formats", fetch="EAGER", inversedBy="diskDiameter")
     * @ORM\JoinColumn(
     *     name="format_id",
     *     referencedColumnName="id",
     *     nullable=true,
     *     onDelete="SET NULL"
     * )
     * @var integer
     *
     */
    private $diskFormat;

    /**
     * @ORM\ManyToOne(targetEntity="Organizations", fetch="EAGER", inversedBy="diskDiamaeterOrg")
     * @ORM\JoinColumn(
     *     name="organization_id",
     *     referencedColumnName="id",
     *     nullable=true,
     *     onDelete="SET NULL"
     * )
     * @var integer
     */
    private $organization;

    /**
     * Returns disk diameter
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
     * @return \Application\Bundle\FrontBundle\Entity\DiskDiameters
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set disk formats
     *
     * @param \Application\Bundle\FrontBundle\Entity\Formats $diskFormat
     *
     * @return \Application\Bundle\FrontBundle\Entity\DiskDiameters
     */
    public function setDiskFormat(\Application\Bundle\FrontBundle\Entity\Formats $diskFormat)
    {
        $this->diskFormat = $diskFormat;

        return $this;
    }

    /**
     * Get disk formats
     *
     * @return \Application\Bundle\FrontBundle\Entity\Formats
     */
    public function getDiskFormat()
    {
        return $this->diskFormat;
    }

    /**
     * Set organization.
     *
     * @param \Application\Bundle\FrontBundle\Entity\Organizations $organization
     *
     * @return \Application\Bundle\FrontBundle\Entity\DiskDiameters
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
