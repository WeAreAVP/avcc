<?php

namespace Application\Bundle\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Bundle\FrontBundle\Entity\CassetteSizes as CassetteSizes;
use Application\Bundle\FrontBundle\Entity\FormatVersions as FormatVersions;
use Application\Bundle\FrontBundle\Entity\RecordingSpeed as RecordingSpeed;
use Application\Bundle\FrontBundle\Entity\RecordingStandards as RecordingStandards;
use Application\Bundle\FrontBundle\Entity\Records as Records;

/**
 * VideoRecords
 *
 * @ORM\Table(name="video_records")
 * @ORM\Entity
 */
class VideoRecords
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
     * @var \Application\Bundle\FrontBundle\Entity\CassetteSizes
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\FrontBundle\Entity\CassetteSizes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cassete_size_id", referencedColumnName="id")
     * })
     */
    private $cassetteSize;

    /**
     * @var integer
     *
     * @ORM\Column(name="media_duration", type="integer", nullable = true)
     */
    private $mediaDuration;

    /**
     * @var \Application\Bundle\FrontBundle\Entity\FormatVersions
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\FrontBundle\Entity\FormatVersions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="format_version_id", referencedColumnName="id")
     * })
     */
    private $formatVersion;

    /**
     * @var \Application\Bundle\FrontBundle\Entity\RecordingSpeed
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\FrontBundle\Entity\RecordingSpeed")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="recording_speed_id", referencedColumnName="id")
     * })
     */
    private $recordingSpeed;

    /**
     * @var \Application\Bundle\FrontBundle\Entity\RecordingStandards
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\FrontBundle\Entity\RecordingStandards")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="recording_standard_id", referencedColumnName="id")
     * })
     */
    private $recordingStandard;

    /**
     * @var \Application\Bundle\FrontBundle\Entity\Records
     *
     * @ORM\OneToOne(targetEntity="Application\Bundle\FrontBundle\Entity\Records", cascade={"all","merge","persist","refresh","remove"}, inversedBy="videoRecord")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="record_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * })
     */
    private $record;

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
     * Set cassette size.
     *
     * @param \Application\Bundle\FrontBundle\Entity\CassetteSizes $cs
     *
     * @return \Application\Bundle\FrontBundle\Entity\VideoRecords
     */
    public function setCassetteSize(CassetteSizes $cs)
    {
        $this->cassetteSize = $cs;

        return $this;
    }

    /**
     * Get cassette size.
     *
     * @return \Application\Bundle\FrontBundle\Entity\CassetteSizes
     */
    public function getCassetteSize()
    {
        return $this->cassetteSize;
    }

    /**
     * Set format version.
     *
     * @param \Application\Bundle\FrontBundle\Entity\FormatVersions $fv
     *
     * @return \Application\Bundle\FrontBundle\Entity\VideoRecords
     */
    public function setFormatVersion(FormatVersions $fv)
    {
        $this->formatVersion = $fv;

        return $this;
    }

    /**
     * Get format verison.
     *
     * @return \Application\Bundle\FrontBundle\Entity\FormatVersions
     */
    public function getFormatVersion()
    {
        return $this->formatVersion;
    }

    /**
     * Set media duration.
     * 
     * @param  string $mediaDuration
     * 
     * @return \Application\Bundle\FrontBundle\Entity\VideoRecords
     */
    public function setMediaDuration($mediaDuration)
    {
        $this->mediaDuration = $mediaDuration;

        return $this;
    }

    /**
     * Get media duration.
     *
     * @return integer
     */
    public function getMediaDuration()
    {
        return $this->mediaDuration;
    }

    /**
     * Set recording speed.
     *
     * @param \Application\Bundle\FrontBundle\Entity\RecordingSpeed $recordingSpeed
     *
     * @return \Application\Bundle\FrontBundle\Entity\VideoRecords
     */
    public function setRecordingSpeed(RecordingSpeed $recordingSpeed)
    {
        $this->recordingSpeed = $recordingSpeed;

        return $this;
    }

    /**
     * get Recording speed
     *
     * @return \Application\Bundle\FrontBundle\Entity\RecordingSpeed
     */
    public function getRecordingSpeed()
    {
        return $this->recordingSpeed;
    }

    /**
     * Set recording standard.
     *
     * @param \Application\Bundle\FrontBundle\Entity\RecordingStandards $recordingStandard
     *
     * @return \Application\Bundle\FrontBundle\Entity\VideoRecords
     */
    public function setRecordingStandard(RecordingStandards $recordingStandard)
    {
        $this->recordingStandard = $recordingStandard;

        return $this;
    }

    /**
     * get Recording standard
     *
     * @return \Application\Bundle\FrontBundle\Entity\RecordingStandards
     */
    public function getRecordingStandard()
    {
        return $this->recordingStandard;
    }

    /**
     * Set record.
     *
     * @param \Application\Bundle\FrontBundle\Entity\Records $r
     *
     * @return \Application\Bundle\FrontBundle\Entity\VideoRecords
     */
    public function setRecord(Records $r)
    {
        $this->record = $r;

        return $this;
    }

    /**
     * Get record.
     *
     * @return \Application\Bundle\FrontBundle\Entity\Records
     */
    public function getRecord()
    {
        return $this->record;
    }

}
