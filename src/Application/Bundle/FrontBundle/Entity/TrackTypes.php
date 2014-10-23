<?php

namespace Application\Bundle\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TrackTypes
 *
 * @ORM\Table(name="track_types")
 * @ORM\Entity
 */
class TrackTypes
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
     * @Assert\NotBlank(message="Track type name is required")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Formats", fetch="EAGER", inversedBy="trackType")
     * @ORM\JoinColumn(
     *     name="format_id",
     *     referencedColumnName="id",
     *     nullable=true,
     *     onDelete="SET NULL"
     * )
     * @var integer
     *
     */
    private $trackTypeFormat;

    /**
     * @ORM\ManyToOne(targetEntity="Organizations", fetch="EAGER", inversedBy="trackTypeOrg")
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
     * Returns track type
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
     * @return \Application\Bundle\FrontBundle\Entity\TrackTypes
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set base formats
     *
     * @param \Application\Bundle\FrontBundle\Entity\Formats $trackTypeFormat
     *
     * @return \Application\Bundle\FrontBundle\Entity\TrackTypes
     */
    public function setTrackTypeFormat(\Application\Bundle\FrontBundle\Entity\Formats $trackTypeFormat)
    {
        $this->trackTypeFormat = $trackTypeFormat;

        return $this;
    }

    /**
     * Get base formats
     *
     * @return \Application\Bundle\FrontBundle\Entity\Formats
     */
    public function getTrackTypeFormat()
    {
        return $this->trackTypeFormat;
    }

    /**
     * Set organization.
     *
     * @param \Application\Bundle\FrontBundle\Entity\Organizations $organization
     *
     * @return \Application\Bundle\FrontBundle\Entity\TrackTypes
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
