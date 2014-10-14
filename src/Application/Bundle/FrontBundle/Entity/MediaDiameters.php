<?php

namespace Application\Bundle\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MediaDiameters
 *
 * @ORM\Table(name="media_diameters")
 * @ORM\Entity
 */
class MediaDiameters
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
     * @Assert\NotBlank(message="Media diameter name is required")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Formats", cascade={"all","merge","persist","refresh","remove"}, fetch="EAGER", inversedBy="mediaDiameter")
     * @ORM\JoinColumn(
     *     name="format_id",
     *     referencedColumnName="id",
     *     nullable=false,
     *     onDelete="CASCADE"
     * )
     * @var integer 
     */
    private $mediaDiameterFormat;

    /**
     * Returns media diameter
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
     * @return \Application\Bundle\FrontBundle\Entity\MediaDiameters
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set media diameter formats
     * 
     * @param \Application\Bundle\FrontBundle\Entity\Formats $mediaDiameterFormat
     * 
     * @return \Application\Bundle\FrontBundle\Entity\MediaDiameters
     */
    public function setMediaDiameterFormat(\Application\Bundle\FrontBundle\Entity\Formats $mediaDiameterFormat)
    {
        $this->mediaDiameterFormat = $mediaDiameterFormat;

        return $this;
    }

    /**
     * Get media diameter formats
     * 
     * @return \Application\Bundle\FrontBundle\Entity\Formats
     */
    public function getMediaDiameterFormat()
    {
        return $this->mediaDiameterFormat;
    }

}
