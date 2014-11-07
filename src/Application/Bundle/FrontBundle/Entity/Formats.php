<?php

namespace Application\Bundle\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Application\Bundle\FrontBundle\Entity\Bases as Bases ;
use Application\Bundle\FrontBundle\Entity\RecordingSpeed as RecordingSpeed;
use Application\Bundle\FrontBundle\Entity\FormatVersions as FormatVersions;

/**
 * Formats
 *
 * @ORM\Table(name="formats")
 * @ORM\Entity
 */
class Formats
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
     * @Assert\NotBlank(message="Format name is required")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="MediaTypes", fetch="EAGER", inversedBy="formats")
     * @ORM\JoinColumn(
     *     name="media_format_id",
     *     referencedColumnName="id",
     *     nullable=true,
     *     onDelete="SET NULL"
     * )
     * @var integer
     * @Assert\NotBlank(message="Media type is required")
     */
    private $mediaType;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Bases",
     *     mappedBy="baseFormat",
     *     fetch="EAGER",
     *     indexBy="id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $base;

    /**
     * @ORM\OneToMany(
     *     targetEntity="RecordingSpeed",
     *     mappedBy="recSpeedFormat",
     *     fetch="EAGER",
     *     indexBy="id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $recordingSpeed;

    /**
     * @ORM\OneToMany(
     *     targetEntity="FormatVersions",
     *     mappedBy="formatVersionFormat",
     *     fetch="EAGER",
     *     indexBy="id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $formatVersion;
    
    /**
     * @ORM\OneToMany(
     *     targetEntity="ReelDiameters",
     *     mappedBy="reelFormat",
     *     fetch="EAGER",
     *     indexBy="id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $reelDiameter;

    /**
     * Formats constructor
     */
    public function __construct()
    {
        $this->base = new ArrayCollection();
        $this->recordingSpeed = new ArrayCollection();
        $this->formatVersion = new ArrayCollection();
        $this->reelDiameter = new ArrayCollection();
    }

    /**
     * Returns format
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
     * @return \Application\Bundle\FrontBundle\Entity\Formats
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set MediaType.
     *
     * @param \Application\Bundle\FrontBundle\Entity\MediaTypes $mediaType
     *
     * @return \Application\Bundle\FrontBundle\Entity\Formats
     */
    public function setMediaType(\Application\Bundle\FrontBundle\Entity\MediaTypes $mediaType)
    {
        $this->mediaType = $mediaType;

        return $this;
    }

    /**
     * Get formate media type
     *
     * @return \Application\Bundle\FrontBundle\Entity\MediaTypes
     */
    public function getMediaType()
    {
        return $this->mediaType;
    }

    /**
     * Add base
     * @param \Application\Bundle\FrontBundle\Entity\Bases $b
     *
     */
    public function addBase(Bases $b)
    {
         if (!$this->base->contains($b)) {

             $this->base[] = $b;
             $b->setBaseFormat($this);
         }
    }

    /**
     * Remove base
     * @param \Application\Bundle\FrontBundle\Entity\Bases $b
     *
     */
    public function removeBase(Bases $b)
    {
         $this->base->remove($b);
    }

    /**
     * Add recording speed
     * @param \Application\Bundle\FrontBundle\Entity\RecordingSpeed $rs
     *
     */
    public function addRecordingSpeed(RecordingSpeed $rs)
    {
         if (!$this->recordingSpeed->contains($rs)) {

             $this->recordingSpeed[] = $rs;
             $rs->setRecSpeedFormat($this);
         }
    }

    /**
     * Remove recording speed
     * @param \Application\Bundle\FrontBundle\Entity\RecordingSpeed $rs
     *
     */
    public function removeRecordingSpeed(RecordingSpeed $rs)
    {
         $this->recordingSpeed->remove($rs);
    }

     /**
     * Add format versions
     * @param \Application\Bundle\FrontBundle\Entity\FormatVersions $fv
     *
     */
    public function addFormatVersion(FormatVersions $fv)
    {
         if (!$this->formatVersion->contains($fv)) {

             $this->formatVersion[] = $fv;
             $fv->setFormatVersionFormat($this);
         }
    }

    /**
     * Remove format versions
     * @param \Application\Bundle\FrontBundle\Entity\FormatVersions $fv
     *
     */
    public function removeFormatVersion(FormatVersions $fv)
    {
         $this->formatVersion->remove($fv);
    }
    
    /**
     * Add reel diameters
     * @param \Application\Bundle\FrontBundle\Entity\ReelDiameters $rm
     *
     */
    public function addReelDiameter(ReelDiameters $rm)
    {
         if (!$this->reelDiameter->contains($rm)) {

             $this->reelDiameter[] = $rm;
             $rm->setReelFormat($this);
         }
    }

    /**
     * Remove reel diameter
     * @param \Application\Bundle\FrontBundle\Entity\ReelDiameters $rm
     *
     */
    public function removeReelDiameter(ReelDiameters $fv)
    {
         $this->reelDiameter->remove($fv);
    }

}
