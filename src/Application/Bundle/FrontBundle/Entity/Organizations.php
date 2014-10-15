<?php

namespace Application\Bundle\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

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
     * @ORM\OneToMany(
     *     targetEntity="AcidDetectionStrips",
     *     mappedBy="organization",
     *     fetch="EAGER",
     *     indexBy="id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $acidDetectionStripOrg;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Bases",
     *     mappedBy="organization",
     *     fetch="EAGER",
     *     indexBy="id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $baseOrg;

    /**
     * @ORM\OneToMany(
     *     targetEntity="CassetteSizes",
     *     mappedBy="organization",
     *     fetch="EAGER",
     *     indexBy="id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $cassetteOrg;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Colors",
     *     mappedBy="organization",
     *     fetch="EAGER",
     *     indexBy="id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $colorOrg;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Commercial",
     *     mappedBy="organization",
     *     fetch="EAGER",
     *     indexBy="id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $commercialOrg;

    /**
     * @ORM\OneToMany(
     *     targetEntity="DiskDiameters",
     *     mappedBy="organization",
     *     fetch="EAGER",
     *     indexBy="id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $diskDiamaeterOrg;

    /**
     * @ORM\OneToMany(
     *     targetEntity="FormatVersions",
     *     mappedBy="organization",
     *     fetch="EAGER",
     *     indexBy="id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $formatOrg;

    /**
     * @ORM\OneToMany(
     *     targetEntity="FrameRates",
     *     mappedBy="organization",
     *     fetch="EAGER",
     *     indexBy="id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $frameRateOrg;

    /**
     * @ORM\OneToMany(
     *     targetEntity="MediaDiameters",
     *     mappedBy="organization",
     *     fetch="EAGER",
     *     indexBy="id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $mediaDiameterOrg;

    /**
     * @ORM\OneToMany(
     *     targetEntity="MonoStereo",
     *     mappedBy="organization",
     *     fetch="EAGER",
     *     indexBy="id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $monoOrg;

    /**
     * @ORM\OneToMany(
     *     targetEntity="NoiceReduction",
     *     mappedBy="organization",
     *     fetch="EAGER",
     *     indexBy="id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $noiceReductionOrg;

    /**
     * @ORM\OneToMany(
     *     targetEntity="PrintTypes",
     *     mappedBy="organization",
     *     fetch="EAGER",
     *     indexBy="id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $printTypeOrg;

    /**
     * @ORM\OneToMany(
     *     targetEntity="RecordingSpeed",
     *     mappedBy="organization",
     *     fetch="EAGER",
     *     indexBy="id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $recordingSpeedOrg;

    /**
     * @ORM\OneToMany(
     *     targetEntity="RecordingStandards",
     *     mappedBy="organization",
     *     fetch="EAGER",
     *     indexBy="id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $recordingStandardOrg;

    /**
     * @ORM\OneToMany(
     *     targetEntity="ReelCore",
     *     mappedBy="organization",
     *     fetch="EAGER",
     *     indexBy="id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $reelCoreOrg;

    /**
     * @ORM\OneToMany(
     *     targetEntity="ReelDiameters",
     *     mappedBy="organization",
     *     fetch="EAGER",
     *     indexBy="id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $reelDiameterOrg;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Slides",
     *     mappedBy="organization",
     *     fetch="EAGER",
     *     indexBy="id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $slidesOrg;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Sounds",
     *     mappedBy="organization",
     *     fetch="EAGER",
     *     indexBy="id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $soundsOrg;

    /**
     * @ORM\OneToMany(
     *     targetEntity="TapeThickness",
     *     mappedBy="organization",
     *     fetch="EAGER",
     *     indexBy="id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $tapeThicknessOrg;

    /**
     * @ORM\OneToMany(
     *     targetEntity="TrackTypes",
     *     mappedBy="organization",
     *     fetch="EAGER",
     *     indexBy="id",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $trackTypeOrg;

    public function __construct()
    {
        $this->acidDetectionStripOrg = new ArrayCollection();
        $this->baseOrg = new ArrayCollection();
        $this->cassetteOrg = new ArrayCollection();
        $this->colorOrg = new ArrayCollection();
        $this->commercialOrg = new ArrayCollection();
        $this->diskDiamaeterOrg = new ArrayCollection();
        $this->formatOrg = new ArrayCollection();
        $this->frameRateOrg = new ArrayCollection();
        $this->mediaDiameterOrg = new ArrayCollection();
        $this->monoOrg = new ArrayCollection();
        $this->noiceReductionOrg = new ArrayCollection();
        $this->printTypeOrg = new ArrayCollection();
        $this->recordingSpeedOrg = new ArrayCollection();
        $this->recordingStandardOrg = new ArrayCollection();
        $this->reelCoreOrg = new ArrayCollection();
        $this->reelDiameterOrg = new ArrayCollection();
        $this->slidesOrg = new ArrayCollection();
        $this->soundsOrg = new ArrayCollection();
        $this->tapeThicknessOrg = new ArrayCollection();
        $this->trackTypeOrg = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedOnValue()
    {
        if (!$this->getCreatedOn()) {
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

    /**
     * Add acid detection strips
     * 
     * @param \Application\Bundle\FrontBundle\Entity\AcidDetectionStrips $ads
     * 
     */
    public function addAcidDetectionStripOrg(AcidDetectionStrips $ads)
    {
        if (!$this->acidDetectionStripOrg->contains($ads)) {

            $this->acidDetectionStripOrg[] = $ads;
            $ads->setOrganization($this);
        }
    }

    /**
     * Remove acid detection strip
     * 
     * @param \Application\Bundle\FrontBundle\Entity\AcidDetectionStrips $ads
     * 
     */
    public function removeAcidDetectionStripOrg(AcidDetectionStrips $ads)
    {
        $this->acidDetectionStripOrg->remove($ads);
    }

    /**
     * Add base
     * 
     * @param \Application\Bundle\FrontBundle\Entity\Bases $base
     * 
     */
    public function addBaseOrg(Bases $base)
    {
        if (!$this->baseOrg->contains($base)) {

            $this->baseOrg[] = $base;
            $base->setOrganization($this);
        }
    }

    /**
     * Remove base org
     * 
     * @param \Application\Bundle\FrontBundle\Entity\Bases $base
     * 
     */
    public function removeBaseOrg(Bases $base)
    {
        $this->baseOrg->remove($base);
    }

    /**
     * Add cassette size
     * 
     * @param \Application\Bundle\FrontBundle\Entity\CassetteSizes $cs
     * 
     */
    public function addCassetteOrg(CassetteSizes $cs)
    {
        if (!$this->cassetteOrg->contains($cs)) {

            $this->cassetteOrg[] = $cs;
            $cs->setOrganization($this);
        }
    }

    /**
     * Remove cassette size org
     * 
     * @param \Application\Bundle\FrontBundle\Entity\CassetteSizes $cs
     * 
     */
    public function removeCassetteOrg(CassetteSizes $cs)
    {
        $this->cassetteOrg->remove($cs);
    }

    /**
     * Add color
     * 
     * @param \Application\Bundle\FrontBundle\Entity\Colors $color
     * 
     */
    public function addColorOrg(Colors $color)
    {
        if (!$this->colorOrg->contains($color)) {

            $this->colorOrg[] = $color;
            $color->setOrganization($this);
        }
    }

    /**
     * Remove color org
     * 
     * @param \Application\Bundle\FrontBundle\Entity\Colors $color
     * 
     */
    public function removeColorOrg(Colors $color)
    {
        $this->colorOrg->remove($color);
    }
    
    /**
     * Add Commercial
     * 
     * @param \Application\Bundle\FrontBundle\Entity\Commercial $commercial
     * 
     */
    public function addCommercialOrg(Colors $commercial)
    {
        if (!$this->commercialOrg->contains($commercial)) {

            $this->commercialOrg[] = $commercial;
            $commercial->setOrganization($this);
        }
    }

    /**
     * Remove Commercial org
     * 
     * @param \Application\Bundle\FrontBundle\Entity\Commercial $commercial
     * 
     */
    public function removeCommercialOrg(Commercial $commercial)
    {
        $this->commercialOrg->remove($commercial);
    }
    
    /**
     * Add diskDiamaeterOrg
     * 
     * @param \Application\Bundle\FrontBundle\Entity\DiskDiameters $dd
     * 
     */
    public function addDiskDiamaeterOrg(DiskDiameters $dd)
    {
        if (!$this->diskDiamaeterOrg->contains($dd)) {

            $this->diskDiamaeterOrg[] = $dd;
            $dd->setOrganization($this);
        }
    }

    /**
     * Remove diskDiamaeterOrg
     * 
     * @param \Application\Bundle\FrontBundle\Entity\DiskDiameters $dd
     * 
     */
    public function removeDiskDiamaeterOrg(DiskDiameters $dd)
    {
        $this->diskDiamaeterOrg->remove($dd);
    }
    
    /**
     * Add formatOrg
     * 
     * @param \Application\Bundle\FrontBundle\Entity\Formats $f
     * 
     */
    public function addFormatOrg(Formats $f)
    {
        if (!$this->formatOrg->contains($f)) {

            $this->formatOrg[] = $f;
            $f->setOrganization($this);
        }
    }

    /**
     * Remove formatOrg
     * 
     * @param \Application\Bundle\FrontBundle\Entity\Formats $f
     * 
     */
    public function removeFormatOrg(Formats $f)
    {
        $this->formatOrg->remove($f);
    }
    
    /**
     * Add frameRateOrg
     * 
     * @param \Application\Bundle\FrontBundle\Entity\FrameRates $fr
     * 
     */
    public function addFrameRateOrg(FrameRates $fr)
    {
        if (!$this->frameRateOrg->contains($fr)) {

            $this->frameRateOrg[] = $fr;
            $fr->setOrganization($this);
        }
    }

    /**
     * Remove frameRateOrg
     * 
     * @param \Application\Bundle\FrontBundle\Entity\FrameRates $fr
     * 
     */
    public function removeFrameRateOrg(FrameRates $fr)
    {
        $this->frameRateOrg->remove($fr);
    }
    
    /**
     * Add mediaDiameterOrg
     * 
     * @param \Application\Bundle\FrontBundle\Entity\MediaDiameters $md
     * 
     */
    public function addMediaDiameterOrg(MediaDiameters $md)
    {
        if (!$this->mediaDiameterOrg->contains($md)) {

            $this->mediaDiameterOrg[] = $md;
            $md->setOrganization($this);
        }
    }

    /**
     * Remove mediaDiameterOrg
     * 
     * @param \Application\Bundle\FrontBundle\Entity\MediaDiameters $md
     * 
     */
    public function removeMediaDiameterOrg(MediaDiameters $md)
    {
        $this->mediaDiameterOrg->remove($md);
    }
    
    /**
     * Add monoOrg
     * 
     * @param \Application\Bundle\FrontBundle\Entity\MonoStereo $ms
     * 
     */
    public function addMonoOrg(MonoStereo $ms)
    {
        if (!$this->monoOrg->contains($ms)) {

            $this->monoOrg[] = $ms;
            $ms->setOrganization($this);
        }
    }

    /**
     * Remove monoOrg
     * 
     * @param \Application\Bundle\FrontBundle\Entity\MonoStereo $ms
     * 
     */
    public function removeMonoOrg(MonoStereo $ms)
    {
        $this->monoOrg->remove($ms);
    }
    
    /**
     * Add NoiceReduction
     * 
     * @param \Application\Bundle\FrontBundle\Entity\NoiceReduction $nr
     * 
     */
    public function addNoiceReductionOrg(NoiceReduction $nr)
    {
        if (!$this->noiceReductionOrg->contains($nr)) {

            $this->noiceReductionOrg[] = $nr;
            $nr->setOrganization($this);
        }
    }

    /**
     * Remove NoiceReduction
     * 
     * @param \Application\Bundle\FrontBundle\Entity\NoiceReduction $nr
     * 
     */
    public function removeNoiceReductionOrg(NoiceReduction $nr)
    {
        $this->noiceReductionOrg->remove($nr);
    }
    
    /**
     * Add printTypeOrg
     * 
     * @param \Application\Bundle\FrontBundle\Entity\PrintTypes $pt
     * 
     */
    public function addPrintTypeOrg(PrintTypes $pt)
    {
        if (!$this->printTypeOrg->contains($pt)) {

            $this->printTypeOrg[] = $pt;
            $pt->setOrganization($this);
        }
    }

    /**
     * Remove printTypeOrg
     * 
     * @param \Application\Bundle\FrontBundle\Entity\PrintTypes $pt
     * 
     */
    public function removePrintTypeOrg(PrintTypes $pt)
    {
        $this->printTypeOrg->remove($pt);
    }
    
    /**
     * Add recordingSpeedOrg
     * 
     * @param \Application\Bundle\FrontBundle\Entity\RecordingSpeed $rs
     * 
     */
    public function addRecordingSpeedOrg(RecordingSpeed $rs)
    {
        if (!$this->recordingSpeedOrg->contains($rs)) {

            $this->recordingSpeedOrg[] = $rs;
            $rs->setOrganization($this);
        }
    }

    /**
     * Remove recordingSpeedOrg
     * 
     * @param \Application\Bundle\FrontBundle\Entity\RecordingSpeed $rs
     * 
     */
    public function removeRecordingSpeedOrg(RecordingSpeed $rs)
    {
        $this->recordingSpeedOrg->remove($rs);
    }
    
    /**
     * Add recordingStandardOrg
     * 
     * @param \Application\Bundle\FrontBundle\Entity\RecordingStandards $rst
     * 
     */
    public function addRecordingStandardOrg(RecordingStandards $rst)
    {
        if (!$this->recordingStandardOrg->contains($rst)) {

            $this->recordingStandardOrg[] = $rst;
            $rst->setOrganization($this);
        }
    }

    /**
     * Remove recordingStandardOrg
     * 
     * @param \Application\Bundle\FrontBundle\Entity\RecordingStandards $rst
     * 
     */
    public function removeRecordingStandardOrg(RecordingStandards $rst)
    {
        $this->recordingStandardOrg->remove($rst);
    }
    
    /**
     * Add reelCoreOrg
     * 
     * @param \Application\Bundle\FrontBundle\Entity\ReelCore $rc
     * 
     */
    public function addReelCoreOrg(ReelCore $rc)
    {
        if (!$this->reelCoreOrg->contains($rc)) {

            $this->reelCoreOrg[] = $rc;
            $rc->setOrganization($this);
        }
    }

    /**
     * Remove reelCoreOrg
     * 
     * @param \Application\Bundle\FrontBundle\Entity\ReelCore $rc
     * 
     */
    public function removeReelCoreOrg(ReelCore $rc)
    {
        $this->reelCoreOrg->remove($rc);
    }
    
    /**
     * Add reelDiameterOrg
     * 
     * @param \Application\Bundle\FrontBundle\Entity\ReelDiameters $rd
     * 
     */
    public function addReelDiameterOrg(ReelDiameters $rd)
    {
        if (!$this->reelDiameterOrg->contains($rd)) {

            $this->reelDiameterOrg[] = $rd;
            $rd->setOrganization($this);
        }
    }

    /**
     * Remove reelDiameterOrg
     * 
     * @param \Application\Bundle\FrontBundle\Entity\ReelDiameters $rd
     * 
     */
    public function removeReelDiameterOrg(ReelDiameters $rd)
    {
        $this->reelDiameterOrg->remove($rd);
    }
    
    /**
     * Add slidesOrg
     * 
     * @param \Application\Bundle\FrontBundle\Entity\Slides $s
     * 
     */
    public function addSlidesOrg(Slides $s)
    {
        if (!$this->slidesOrg->contains($s)) {

            $this->slidesOrg[] = $s;
            $s->setOrganization($this);
        }
    }

    /**
     * Remove slidesOrg
     * 
     * @param \Application\Bundle\FrontBundle\Entity\Slides $s
     * 
     */
    public function removeSlidesOrg(Slides $s)
    {
        $this->slidesOrg->remove($s);
    }
    
    /**
     * Add soundsOrg
     * 
     * @param \Application\Bundle\FrontBundle\Entity\Sounds $sound
     * 
     */
    public function addSoundsOrg(Sounds $sound)
    {
        if (!$this->soundsOrg->contains($sound)) {

            $this->soundsOrg[] = $sound;
            $sound->setOrganization($this);
        }
    }

    /**
     * Remove soundsOrg
     * 
     * @param \Application\Bundle\FrontBundle\Entity\Sounds $sound
     * 
     */
    public function removeSoundsOrg(Sounds $sound)
    {
        $this->soundsOrg->remove($sound);
    }
    
    /**
     * Add tapeThicknessOrg
     * 
     * @param \Application\Bundle\FrontBundle\Entity\TapeThickness $tt
     * 
     */
    public function addTapeThicknessOrg(TapeThickness $tt)
    {
        if (!$this->tapeThicknessOrg->contains($tt)) {

            $this->tapeThicknessOrg[] = $tt;
            $tt->setOrganization($this);
        }
    }

    /**
     * Remove tapeThicknessOrg
     * 
     * @param \Application\Bundle\FrontBundle\Entity\TapeThickness $tt
     * 
     */
    public function removeTapeThicknessOrg(TapeThickness $tt)
    {
        $this->tapeThicknessOrg->remove($tt);
    }
    
    /**
     * Add trackTypeOrg
     * 
     * @param \Application\Bundle\FrontBundle\Entity\TrackTypes $tts
     * 
     */
    public function addTrackTypeOrg(TrackTypes $tts)
    {
        if (!$this->trackTypeOrg->contains($tts)) {

            $this->trackTypeOrg[] = $tts;
            $tts->setOrganization($this);
        }
    }

    /**
     * Remove trackTypeOrg
     * 
     * @param \Application\Bundle\FrontBundle\Entity\TrackTypes $tts
     * 
     */
    public function removeTrackTypeOrg(TrackTypes $tts)
    {
        $this->trackTypeOrg->remove($tts);
    }
}
