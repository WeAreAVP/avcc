<?php

namespace Application\Bundle\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Bundle\FrontBundle\Entity\DiskDiameters as DiskDiameters;
use Application\Bundle\FrontBundle\Entity\MediaDiameters as MediaDiameters ;
use Application\Bundle\FrontBundle\Entity\Bases as Bases ;
use Application\Bundle\FrontBundle\Entity\RecordingSpeed as RecordingSpeed;
use Application\Bundle\FrontBundle\Entity\TapeThickness as TapeThickness;
use Application\Bundle\FrontBundle\Entity\TrackTypes as TrackTypes;
use Application\Bundle\FrontBundle\Entity\MonoStereo as MonoStereo;

/**
 * AudioRecords
 *
 * @ORM\Table(name="audio_records")
 * @ORM\Entity
 */
class AudioRecords
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
     * @var \Application\Bundle\FrontBundle\Entity\DiskDiameters
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\FrontBundle\Entity\DiskDiameters")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="disk_diameter_id", referencedColumnName="id")
     * })
     */
    private $diskDiameters;

    /**
     * @var \Application\Bundle\FrontBundle\Entity\MediaDiameters
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\FrontBundle\Entity\MediaDiameters")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="media_diameter_id", referencedColumnName="id")
     * })
     */
    private $mediaDiameters;

    /**
     * @var \Application\Bundle\FrontBundle\Entity\Bases
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\FrontBundle\Entity\Bases")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="base_id", referencedColumnName="id")
     * })
     */
    private $bases;

    /**
     * @var integer
     *
     * @ORM\Column(name="media_duration", type="integer")
     */
    private $mediaDuration;

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
     * @var \Application\Bundle\FrontBundle\Entity\TapeThickness
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\FrontBundle\Entity\TapeThickness")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tape_thickness_id", referencedColumnName="id")
     * })
     */
    private $tapeThickness;

    /**
     * @var \Application\Bundle\FrontBundle\Entity\Sides
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\FrontBundle\Entity\Slides")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="side_id", referencedColumnName="id")
     * })
     */
    private $slides;

    /**
     * @var \Application\Bundle\FrontBundle\Entity\TrackTypes
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\FrontBundle\Entity\TrackTypes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="track_type_id", referencedColumnName="id")
     * })
     */
    private $trackTypes;

    /**
     * @var \Application\Bundle\FrontBundle\Entity\MonoStereo
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\FrontBundle\Entity\MonoStereo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="mono_stero_id", referencedColumnName="id")
     * })
     */
    private $monoStereo;

    /**
     * @var \Application\Bundle\FrontBundle\Entity\NoiceReduction
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\FrontBundle\Entity\NoiceReduction")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="noice_reduction_id", referencedColumnName="id")
     * })
     */
    private $noiceReduction;

    /**
     * @var \Application\Bundle\FrontBundle\Entity\Records
     *
     * @ORM\OneToOne(targetEntity="Application\Bundle\FrontBundle\Entity\Records", cascade={"all","merge","persist","refresh","remove"}, inversedBy="audioRecord")
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
     * Set disk diameter.
     *
     * @param \Application\Bundle\FrontBundle\Entity\DiskDiameters $diskDiameters
     *
     * @return \Application\Bundle\FrontBundle\Entity\AudtioRecords
     */
    public function setDiskDiameters(DiskDiameters $diskDiameters)
    {
        $this->diskDiameters = $diskDiameters;

        return $this;
    }

    /**
     * Get disk diameter.
     *
     * @return \Application\Bundle\FrontBundle\Entity\DiskDiameters
     */
    public function getDiskDiameters()
    {
        return $this->diskDiameters;
    }

    /**
     * Set media diameter.
     *
     * @param \Application\Bundle\FrontBundle\Entity\MediaDiameters $mediaDiameters
     *
     * @return \Application\Bundle\FrontBundle\Entity\AudtioRecords
     */
    public function setMediaDiameters(MediaDiameters $mediaDiameters)
    {
        $this->mediaDiameters = $mediaDiameters;

        return $this;
    }

    /**
     * Get media diameter.
     *
     * @return \Application\Bundle\FrontBundle\Entity\MediaDiameters
     */
    public function getMediaDiameters()
    {
        return $this->mediaDiameters;
    }

    /**
     * Set base.
     *
     * @param \Application\Bundle\FrontBundle\Entity\Bases $bases
     *
     * @return \Application\Bundle\FrontBundle\Entity\AudtioRecords
     */
    public function setBases(Bases $bases)
    {
        $this->bases = $bases;

        return $this;
    }

    /**
     * Get base.
     *
     * @return \Application\Bundle\FrontBundle\Entity\Bases
     */
    public function getBases()
    {
        return $this->bases;
    }

    /**
     * Set media duration.
     *
     * @return integer
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
     * @return \Application\Bundle\FrontBundle\Entity\AudtioRecords
     */
    public function setRecordingSpeed(RecordingSpeed $recordingSpeed)
    {
        $this->recordingSpeed = $recordingSpeed;

        return $this;
    }

    /**
     * Get recording speed.
     *
     * @return \Application\Bundle\FrontBundle\Entity\RecordingSpeed
     */
    public function getRecordingSpeed()
    {
        return $this->recordingSpeed;
    }

    /**
     * Set tape thickness.
     *
     * @param \Application\Bundle\FrontBundle\Entity\TapeThickness $tapeThickness
     *
     * @return \Application\Bundle\FrontBundle\Entity\AudtioRecords
     */
    public function setTapeThickness(TapeThickness $tapeThickness)
    {
        $this->tapeThickness = $tapeThickness;

        return $this;
    }

    /**
     * Get tapeThickness
     *
     * @return \Application\Bundle\FrontBundle\Entity\TapeThickness
     */
    public function getTapeThickness()
    {
        return $this->tapeThickness;
    }

    /**
     * Set slide.
     *
     * @param \Application\Bundle\FrontBundle\Entity\Slides $slides
     *
     * @return \Application\Bundle\FrontBundle\Entity\AudtioRecords
     */
    public function setSlide(Slides $slides)
    {
        $this->slides = $slides;

        return $this;
    }

    /**
     * Get slide
     *
     * @return \Application\Bundle\FrontBundle\Entity\Slides
     */
    public function getSlide()
    {
        return $this->slides;
    }

    /**
     * Set track type.
     *
     * @param \Application\Bundle\FrontBundle\Entity\TrackTypes $trackTypes
     *
     * @return \Application\Bundle\FrontBundle\Entity\AudtioRecords
     */
    public function setTrackType(TrackTypes $trackTypes)
    {
        $this->trackTypes = $trackTypes;

        return $this;
    }

    /**
     * Get track type
     *
     * @return \Application\Bundle\FrontBundle\Entity\TrackTypes
     */
    public function getTrackType()
    {
        return $this->trackTypes;
    }

    /**
     * Set mono stereo.
     *
     * @param \Application\Bundle\FrontBundle\Entity\MonoStero $monoStereo
     *
     * @return \Application\Bundle\FrontBundle\Entity\AudtioRecords
     */
    public function setMonoStereo(MonoStereo $monoStereo)
    {
        $this->monoStereo = $monoStereo;

        return $this;
    }

    /**
     * Get mono stereo.
     *
     * @return \Application\Bundle\FrontBundle\Entity\MonoStero
     */
    public function getMonoStereo()
    {
        return $this->monoStereo;
    }

    /**
     * Set noice reduction.
     *
     * @param \Application\Bundle\FrontBundle\Entity\NoiceReduction $noiceReduction
     *
     * @return \Application\Bundle\FrontBundle\Entity\AudtioRecords
     */
    public function setNoiceReduction(NoiceReduction $noiceReduction)
    {
        $this->noiceReduction = $noiceReduction;

        return $this;
    }

    /**
     * Get NoiceReduction.
     *
     * @return \Application\Bundle\FrontBundle\Entity\NoiceReduction
     */
    public function getNoiceReduction()
    {
        return $this->noiceReduction;
    }

    /**
     * Set record.
     *
     * @param \Application\Bundle\FrontBundle\Entity\Records $r
     *
     * @return \Application\Bundle\FrontBundle\Entity\AudtioRecords
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
