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
     * @ORM\ManyToOne(targetEntity="Formats", cascade={"all","merge","persist","refresh","remove"}, fetch="EAGER", inversedBy="trackType")
     * @ORM\JoinColumn(
     *     name="format_id",
     *     referencedColumnName="id",
     *     nullable=false,
     *     onDelete="CASCADE"
     * )
     * @var integer 
     */
    private $trackTypeFormat;

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

}
