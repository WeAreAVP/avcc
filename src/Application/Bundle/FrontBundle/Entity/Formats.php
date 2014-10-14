<?php

namespace Application\Bundle\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Application\Bundle\FrontBundle\Entity\DiskDiameters as DiskDiameters;
use Application\Bundle\FrontBundle\Entity\ReelDiameters as ReelDiameters;
use Application\Bundle\FrontBundle\Entity\MediaDiameters as MediaDiameters ;
use Application\Bundle\FrontBundle\Entity\Bases as Bases ;
use Application\Bundle\FrontBundle\Entity\RecordingSpeed as RecordingSpeed;
use Application\Bundle\FrontBundle\Entity\TapeThickness as TapeThickness;
use Application\Bundle\FrontBundle\Entity\TrackTypes as TrackTypes;
use Application\Bundle\FrontBundle\Entity\CassetteSizes as CassetteSizes;
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
     * @ORM\ManyToOne(targetEntity="MediaTypes", cascade={"all","merge","persist","refresh","remove"}, fetch="EAGER", inversedBy="formats")
     * @ORM\JoinColumn(
     *     name="media_format_id",
     *     referencedColumnName="id",
     *     nullable=false,
     *     onDelete="CASCADE"
     * )
     * @var integer 
     */
    private $mediaType;

    /**
     * @ORM\OneToMany(
     *     targetEntity="DiskDiameters",
     *     mappedBy="diskFormat",
     *     fetch="EAGER",
     *     indexBy="id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */    
    private $diskDiameter;
    
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
     * @ORM\OneToMany(
     *     targetEntity="MediaDiameters",
     *     mappedBy="mediaDiameterFormat",
     *     fetch="EAGER",
     *     indexBy="id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */ 
    private $mediaDiameter;
    
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
     *     targetEntity="TapeThickness",
     *     mappedBy="tapeThicknessFormat",
     *     fetch="EAGER",
     *     indexBy="id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */ 
    private $tapeThickness;
    
    /**
     * @ORM\OneToMany(
     *     targetEntity="TrackTypes",
     *     mappedBy="trackTypeFormat",
     *     fetch="EAGER",
     *     indexBy="id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */ 
    private $trackType;
    
    /**
     * @ORM\OneToMany(
     *     targetEntity="CassetteSizes",
     *     mappedBy="cassetteSizeFormat",
     *     fetch="EAGER",
     *     indexBy="id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */ 
    private $cassetteSize;
    
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
    
    public function __construct()
    {
        $this->diskDiameter = new ArrayCollection();   
        $this->reelDiameter = new ArrayCollection();   
        $this->mediaDiameter = new ArrayCollection();   
        $this->base = new ArrayCollection();
        $this->recordingSpeed = new ArrayCollection();
        $this->tapeThickness = new ArrayCollection();
        $this->trackType = new ArrayCollection();
        $this->cassetteSize = new ArrayCollection();
        $this->formatVersion = new ArrayCollection();
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
     * Add disk diameter
     * 
     * @param \Application\Bundle\FrontBundle\Entity\DiskDiameters $dd
     * 
     */
    public function addDiskDiameter(DiskDiameters $dd)
    {
         if (!$this->diskDiameter->contains($dd)) {

             $this->diskDiameter[] = $dd;
             $dd->setDiskFormat($this);
         }
    }
    
    /**
     * Remove disk diameter
     * 
     * @param \Application\Bundle\FrontBundle\Entity\DiskDiameters $dd
     * 
     */
    public function removeDiskDiameter(DiskDiameters $dd)
    {
         $this->diskDiameter->remove($dd);
    }
    
    /**
     * Add reel diameter
     * 
     * @param \Application\Bundle\FrontBundle\Entity\ReelDiameters $rd
     * 
     */
    public function addReelDiameter(ReelDiameters $rd)
    {
         if (!$this->reelDiameter->contains($rd)) {

             $this->reelDiameter[] = $rd;
             $rd->setReelFormat($this);
         }
    }
    
    /**
     * Remove reel diameter
     * 
     * @param \Application\Bundle\FrontBundle\Entity\ReelDiameters $rd
     * 
     */
    public function removeReelDiameter(ReelDiameters $rd)
    {
         $this->reelDiameter->remove($rd);
    }
    
    /**
     * Add media diameter
     * 
     * @param \Application\Bundle\FrontBundle\Entity\MediaDiameters $md
     * 
     */
    public function addMediaDiameterFormat(MediaDiameters $md)
    {
         if (!$this->mediaDiameter->contains($md)) {

             $this->mediaDiameter[] = $md;
             $md->setMediaDiameterFormat($this);
         }
    }
    
    /**
     * Remove media diameter
     * 
     * @param \Application\Bundle\FrontBundle\Entity\MediaDiameters $md
     * 
     */
    public function removeMediaDiameterFormat(MediaDiameters $md)
    {
         $this->mediaDiameter->remove($md);
    }
    
    /**
     * Add base
     * 
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
     * 
     * @param \Application\Bundle\FrontBundle\Entity\Bases $b
     * 
     */
    public function removeBase(Bases $b)
    {
         $this->base->remove($b);
    }
    
    /**
     * Add recording speed
     * 
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
     * 
     * @param \Application\Bundle\FrontBundle\Entity\RecordingSpeed $rs
     * 
     */
    public function removeRecordingSpeed(RecordingSpeed $rs)
    {
         $this->recordingSpeed->remove($rs);
    }
    
    /**
     * Add tape Thickness
     * 
     * @param \Application\Bundle\FrontBundle\Entity\TapeThickness $tt
     * 
     */
    public function addTapeThickness(TapeThickness $tt)
    {
         if (!$this->tapeThickness->contains($tt)) {

             $this->tapeThickness[] = $tt;
             $tt->setTapeThicknessFormat($this);
         }
    }
    
    /**
     * Remove tape thickness
     * 
     * @param \Application\Bundle\FrontBundle\Entity\TapeThickness $tt
     * 
     */
    public function removeTapeThickness(TapeThickness $tt)
    {
         $this->tapeThickness->remove($tt);
    }
    
    /**
     * Add track type
     * 
     * @param \Application\Bundle\FrontBundle\Entity\TrackTypes $tty
     * 
     */
    public function addTrackType(TrackTypes $tty)
    {
         if (!$this->trackType->contains($tty)) {

             $this->trackType[] = $tty;
             $tty->setTrackTypeFormat($this);
         }
    }
    
    /**
     * Remove tape thickness
     * 
     * @param \Application\Bundle\FrontBundle\Entity\TapeThickness $tt
     * 
     */
    public function removeTrackType(TrackTypes $tty)
    {
         $this->trackType->remove($tty);
    }    
    
    /**
     * Add cassetteSize
     * 
     * @param \Application\Bundle\FrontBundle\Entity\CassetteSizes $cs
     * 
     */
    public function addCassetteSize(CassetteSizes $cs)
    {
         if (!$this->cassetteSize->contains($cs)) {

             $this->cassetteSize[] = $cs;
             $cs->setCassetteSizeFormat($this);
         }
    }
    
    /**
     * Remove cassette sizes
     * 
     * @param \Application\Bundle\FrontBundle\Entity\CassetteSizes $cs
     * 
     */
    public function removeCassetteSize(CassetteSizes $cs)
    {
         $this->cassetteSize->remove($cs);
    }
    
     /**
     * Add format versions
     * 
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
     * 
     * @param \Application\Bundle\FrontBundle\Entity\FormatVersions $fv
     * 
     */
    public function removeFormatVersion(FormatVersions $fv)
    {
         $this->formatVersion->remove($fv);
    }
}
