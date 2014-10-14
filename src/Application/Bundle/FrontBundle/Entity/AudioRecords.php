<?php

namespace Application\Bundle\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * Get Id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
     * Set disk diameter.
     *
     * @param \Application\Bundle\FrontBundle\Entity\DiskDiameters $diskDiameters
     *
     * @return \Application\Bundle\FrontBundle\Entity\AudtioRecords
     */
    public function setDiskDiameters(\Application\Bundle\FrontBundle\Entity\Organizations $diskDiameters)
    {
        $this->diskDiameters = $diskDiameters;

        return $this;
    }

    

}
