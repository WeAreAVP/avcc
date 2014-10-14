<?php

namespace Application\Bundle\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FormatVersions
 *
 * @ORM\Table(name="format_versions")
 * @ORM\Entity
 */
class FormatVersions
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
     * @Assert\NotBlank(message="Format version is required")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Formats", cascade={"all","merge","persist","refresh","remove"}, fetch="EAGER", inversedBy="formatVersion")
     * @ORM\JoinColumn(
     *     name="format_id",
     *     referencedColumnName="id",
     *     nullable=false,
     *     onDelete="CASCADE"
     * )
     * @var integer 
     */
    private $formatVersionFormat;

    /**
     * Returns Base
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
     * @return \Application\Bundle\FrontBundle\Entity\FormatVersions
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set base formats
     * 
     * @param \Application\Bundle\FrontBundle\Entity\Formats $formatVersionFormat
     * 
     * @return \Application\Bundle\FrontBundle\Entity\FormatVersions
     */
    public function setFormatVersionFormat(\Application\Bundle\FrontBundle\Entity\Formats $formatVersionFormat)
    {
        $this->formatVersionFormat = $formatVersionFormat;

        return $this;
    }

    /**
     * Get base formats
     * 
     * @return \Application\Bundle\FrontBundle\Entity\Formats
     */
    public function getFormatVersionFormat()
    {
        return $this->formatVersionFormat;
    }

}
