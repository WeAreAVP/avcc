<?php

namespace Application\Bundle\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Application\Bundle\FrontBundle\Entity\Users as Users;
use Application\Bundle\FrontBundle\Entity\Formats as Formats;
use Application\Bundle\FrontBundle\Entity\Commercial as Commercial;
use Application\Bundle\FrontBundle\Entity\Projects as Projects;
use Application\Bundle\FrontBundle\Entity\MediaTypes as MediaTypes;
use Application\Bundle\FrontBundle\Entity\AudioRecords as AudioRecords;
use Application\Bundle\FrontBundle\Entity\VideoRecords as VideoRecords;
use Application\Bundle\FrontBundle\Entity\FilmRecords as FilmRecords;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * Records
 *
 * @ORM\Table(name="records")
 * @ORM\Entity
 * @UniqueEntity(fields="unique_id", message="Unique id already exist.")
 */
class Records
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
     * @var \Application\Bundle\FrontBundle\Entity\Projects
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\FrontBundle\Entity\Projects")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     * })
     * @Assert\NotBlank(message="Projects is required")
     */
    private $project;

    /**
     * @var \DateTime $createdOn
     *
     * @ORM\Column(name="created_on", type="datetime")
     */
    private $createdOn;

    /**
     * @var \DateTime $updatedOn
     *
     * @ORM\Column(name="updated_on", type="datetime", nullable=true)
     */
    private $updatedOn;

    /**
     * @var \Application\Bundle\FrontBundle\Entity\Users
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\FrontBundle\Entity\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Application\Bundle\FrontBundle\Entity\MediaTypes
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\FrontBundle\Entity\MediaTypes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="media_type_id", referencedColumnName="id")
     * })
     */
    private $mediaType;

    /**
     * @ORM\Column(name="unique_id", type="string")
     * @var string
     * @Assert\NotBlank(message="Unique id is required")
     */
    private $uniqueId;

    /**
     * @var string
     * @Assert\NotBlank(message="Location is required")
     */
    private $location;

    /**
     * @var \Application\Bundle\FrontBundle\Entity\Formats
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\FrontBundle\Entity\Formats")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="format_id", referencedColumnName="id")
     * })
     * @Assert\NotBlank(message="Formats is required")
     */
    private $format;

    /**
     * @var string
     * @Assert\NotBlank(message="Title is required")
     */
    private $title;

    /**
     * @ORM\Column(name="collection_name", type="string")
     * @var string
     * @Assert\NotBlank(message="Title is required")
     */
    private $collectionName;

    /**
     *
     * @var string
     */
    private $description;

    /**
     * @var \Application\Bundle\FrontBundle\Entity\Commercial
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\FrontBundle\Entity\Commercial")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="commercial_id", referencedColumnName="id")
     * })
     */
    private $commercial;

    /**
     * @var \Application\Bundle\FrontBundle\Entity\ReelDiameters
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\FrontBundle\Entity\ReelDiameters")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="reel_diameter_id", referencedColumnName="id")
     * })
     */
    private $reelDiameters;

    /**
     * @var integer
     *
     * @ORM\Column(name="content_duration", type="integer")
     */
    private $contentDuration;

    /**
     * @var string
     *
     * @ORM\Column(name="creation_date", type="string")
     */
    private $creationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="content_date", type="string")
     */
    private $contentDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_review", type="boolean", options={"default" = 0}) )
     */
    private $isReview;

    /**
     * @var string
     *
     * @ORM\Column(name="genre_terms", type="string",length=250, nullable=true)
     */
    private $genreTerms;

    /**
     * @var string
     *
     * @ORM\Column(name="contributor", type="string",length=500, nullable=true)
     */
    private $contributor;

    /**
     * @var string
     *
     * @ORM\Column(name="generation", type="string",length=500, nullable=true)
     */
    private $generation;

    /**
     * @var string
     *
     * @ORM\Column(name="part", type="string",length=250, nullable=true)
     */
    private $part;

    /**
     * @var string
     *
     * @ORM\Column(name="copyright_restrictions", type="string",length=250, nullable=true)
     */
    private $copyrightRestrictions;

    /**
     * @var string
     *
     * @ORM\Column(name="duplicates_derivatives", type="string",length=250, nullable=true)
     */
    private $duplicatesDerivatives;

    /**
     * @var string
     *
     * @ORM\Column(name="related_material", type="string",length=250, nullable=true)
     */
    private $relatedMaterial;

    /**
     * @var string
     *
     * @ORM\Column(name="condition_note", type="string",length=500, nullable=true)
     */
    private $conditionNote;

    /**
     * @ORM\OneToOne(
     *     targetEntity="AudioRecords",
     *     mappedBy="record",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     */
    private $audioRecord;

    /**
     * @ORM\OneToOne(
     *     targetEntity="VideoRecords",
     *     mappedBy="record",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     */
    private $videoRecord;
    
    /**
     * @ORM\OneToOne(
     *     targetEntity="FilmRecords",
     *     mappedBy="record",
     *     cascade={"all","merge","persist","refresh","remove"}
     * )
     */
    private $filmRecord;
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
     * Get Created on time.
     *
     * @return \Datetime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * Get Update on time.
     *
     * @return \Datetime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * Set user.
     *
     * @param \Application\Bundle\FrontBundle\Entity\Users $user
     *
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function setUser(Users $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     * 
     * @return \Application\Bundle\FrontBundle\Entity\Users
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set unique id
     * 
     * @param string $uniqueId
     * 
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function setUniqueId($uniqueId)
    {
        $this->uniqueId = $uniqueId;

        return $this;
    }

    /**
     * Get unique id
     * 
     * @return string
     */
    public function getUniqueId()
    {
        return $this->uniqueId;
    }

    /**
     * Set media diameter.
     *
     * @param \Application\Bundle\FrontBundle\Entity\Projects $project
     *
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function setProject(Projects $project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get media diameter.
     *
     * @return \Application\Bundle\FrontBundle\Entity\Projects
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set MediaType.
     *
     * @param \Application\Bundle\FrontBundle\Entity\MediaTypes $mediaType
     *
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function setMediaType(MediaTypes $mediaType)
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
     * Set Location
     * 
     * @param string $location
     * 
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }

    /**
     * Get location
     * 
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set Format.
     *
     * @param \Application\Bundle\FrontBundle\Entity\Format $format
     *
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function setFormat(Formats $format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get format
     * 
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set title
     * 
     * @param string $title
     * 
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     * 
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set collection name
     * 
     * @param string $collectionName
     * 
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function setCollectionName($collectionName)
    {
        $this->collectionName = $collectionName;
        return $this;
    }

    /**
     * Get title
     * 
     * @return string
     */
    public function getCollectionName()
    {
        return $this->collectionName;
    }

    /**
     * Set description
     * 
     * @param string $description
     * 
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get title
     * 
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set commercial.
     *
     * @param \Application\Bundle\FrontBundle\Entity\Commercial $commercial
     *
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function setCommercial(Commercial $commercial)
    {
        $this->commercial = $commercial;

        return $this;
    }

    /**
     * Get commercial
     * 
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function getCommercial()
    {
        return $this->commercial;
    }

    /**
     * Set reel diameter.
     *
     * @param \Application\Bundle\FrontBundle\Entity\ReelDiameters $reeldiameter
     *
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function setReelDiameter(ReelDiameters $reeldiameter)
    {
        $this->reelDiameters = $reeldiameter;

        return $this;
    }

    /**
     * Get reel diameter
     * 
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function getReelDiameter()
    {
        return $this->reelDiameters;
    }

    /**
     * Set content duration
     * 
     * @param integer $contentDuration
     * 
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function setContentDuration($contentDuration)
    {
        $this->contentDuration = $contentDuration;
        return $this;
    }

    /**
     * Get title
     * 
     * @return integer
     */
    public function getContentDuration()
    {
        return $this->contentDuration;
    }

    /**
     * Set creation date
     * 
     * @param string $creationDate
     * 
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    /**
     * Get creation date
     * 
     * @return string
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set content date
     * 
     * @param string $contentDate
     * 
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function setContentDate($contentDate)
    {
        $this->contentDate = $contentDate;
        return $this;
    }

    /**
     * Get content date
     * 
     * @return string
     */
    public function getContentDate()
    {
        return $this->contentDate;
    }

    /**
     * Set flag for review
     * 
     * @param boolean $isReview
     * 
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function setIsReview($isReview)
    {
        $this->isReview = $isReview;
        return $this;
    }

    /**
     * Get content date
     * 
     * @return boolean
     */
    public function getIsReview()
    {
        return $this->isReview;
    }

    /**
     * Set genre terms
     * 
     * @param string $genreTerms
     * 
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function setGenreTerms($genreTerms)
    {
        $this->genreTerms = $genreTerms;
        return $this;
    }

    /**
     * Get genre terms
     * 
     * @return string
     */
    public function getGenreTerms()
    {
        return $this->genreTerms;
    }

    /**
     * Set contributor
     * 
     * @param string $contributor
     * 
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function setContributor($contributor)
    {
        $this->contributor = $contributor;
        return $this;
    }

    /**
     * Get Contributor
     * 
     * @return string
     */
    public function getContributor()
    {
        return $this->contributor;
    }

    /**
     * Set generation
     * 
     * @param string $generation
     * 
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function setGeneration($generation)
    {
        $this->generation = $generation;
        return $this;
    }

    /**
     * Get Contributor
     * 
     * @return string
     */
    public function getGeneration()
    {
        return $this->generation;
    }

    /**
     * Set part
     * 
     * @param string $part
     * 
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function setPart($part)
    {
        $this->part = $part;
        return $this;
    }

    /**
     * Get part
     * 
     * @return string
     */
    public function getPart()
    {
        return $this->part;
    }

    /**
     * Set copyright restriction
     * 
     * @param string $copyrightRestrictions
     * 
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function setCopyrightRestrictions($copyrightRestrictions)
    {
        $this->copyrightRestrictions = $copyrightRestrictions;
        return $this;
    }

    /**
     * Get copyright restriction
     * 
     * @return string
     */
    public function getCopyrightRestrictions()
    {
        return $this->copyrightRestrictions;
    }

    /**
     * Set duplicates derivatives
     * 
     * @param string $duplicatesDerivatives
     * 
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function setDuplicatesDerivatives($duplicatesDerivatives)
    {
        $this->duplicatesDerivatives = $duplicatesDerivatives;
        return $this;
    }

    /**
     * Get duplicates derivatives
     * 
     * @return string
     */
    public function getDuplicatesDerivatives()
    {
        return $this->duplicatesDerivatives;
    }

    /**
     * Set related material
     * 
     * @param string $relatedMaterial
     * 
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function setRelatedMaterial($relatedMaterial)
    {
        $this->relatedMaterial = $relatedMaterial;
        return $this;
    }

    /**
     * Get related material
     * 
     * @return string
     */
    public function getRelatedMaterial()
    {
        return $this->relatedMaterial;
    }

    /**
     * Set condition note
     * 
     * @param string $conditionNote
     * 
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function setConditionNote($conditionNote)
    {
        $this->conditionNote = $conditionNote;
        return $this;
    }

    /**
     * Get condition note
     * 
     * @return string
     */
    public function getConditionNote()
    {
        return $this->conditionNote;
    }

    /**
     * Set audio record
     * 
     * @param \Application\Bundle\FrontBundle\Entity\AudioRecords $ar
     * 
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function setAudioRecord(AudioRecords $ar)
    {
        $this->audioRecord = $ar;
        return $this;
    }
 
    /**
     * 
     * @return \Application\Bundle\FrontBundle\Entity\AudioRecords
     */
    public function getAudioRecord()
    {
        return $this->audioRecord;
    }
    
    /**
     * Set video record
     * 
     * @param \Application\Bundle\FrontBundle\Entity\VideoRecords $vr
     * 
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function setVideoRecord(VideoRecords $vr)
    {
        $this->videoRecord = $vr;
        return $this;
    }
 
    /**
     * 
     * @return \Application\Bundle\FrontBundle\Entity\VideoRecords
     */
    public function getVideoRecord()
    {
        return $this->videoRecord;
    }
    
    /**
     * Set film record
     * 
     * @param \Application\Bundle\FrontBundle\Entity\FilmsRecords $fr
     * 
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function setFilmRecord(FilmRecords $fr)
    {
        $this->filmRecord = $fr;
        return $this;
    }
 
    /**
     * 
     * @return \Application\Bundle\FrontBundle\Entity\FilmRecords
     */
    public function getFilmRecord()
    {
        return $this->filmRecord;
    }

}
