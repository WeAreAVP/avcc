<?php

namespace Application\Bundle\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TapeThickness
 *
 * @ORM\Table(name="tape_thickness")
 * @ORM\Entity
 */
class TapeThickness
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
     * @Assert\NotBlank(message="Tape thickness name is required")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Formats", cascade={"all","merge","persist","refresh","remove"}, fetch="EAGER", inversedBy="tapeThickness")
     * @ORM\JoinColumn(
     *     name="format_id",
     *     referencedColumnName="id",
     *     nullable=false,
     *     onDelete="CASCADE"
     * )
     * @var integer 
     */
    private $tapeThicknessFormat;

    /**
     * Returns tape thickness
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
     * @return \Application\Bundle\FrontBundle\Entity\TapeThickness
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set base formats
     * 
     * @param \Application\Bundle\FrontBundle\Entity\Formats $tapeThicknessFormat
     * 
     * @return \Application\Bundle\FrontBundle\Entity\TapeThickness
     */
    public function setTapeThicknessFormat(\Application\Bundle\FrontBundle\Entity\Formats $tapeThicknessFormat)
    {
        $this->tapeThicknessFormat = $tapeThicknessFormat;

        return $this;
    }

    /**
     * Get base formats
     * 
     * @return \Application\Bundle\FrontBundle\Entity\Formats
     */
    public function getTapeThicknessFormat()
    {
        return $this->tapeThicknessFormat;
    }

}
