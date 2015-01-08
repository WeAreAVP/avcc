<?php

namespace Application\Bundle\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Bases
 *
 * @ORM\Table(name="bases")
 * @ORM\Entity(repositoryClass="Application\Bundle\FrontBundle\Entity\BasesRepository")
 */
class Bases
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
     * @Assert\NotBlank(message="Base name is required")
     */
    private $name;

    /**
     * @var real
     *
     * @ORM\Column(name="score", type="float", options={"default" = 0})
     * @Assert\NotBlank(message="Score is required")
     */
    private $score = 0;

    /**
     * @ORM\ManyToOne(targetEntity="Formats",  fetch="EAGER", inversedBy="base")
     * @ORM\JoinColumn(
     *     name="format_id",
     *     referencedColumnName="id",
     *     nullable=true,
     *     onDelete="SET NULL"
     * )
     *
     * @var integer
     */
    private $baseFormat;

    /**
     * @ORM\ManyToOne(targetEntity="Organizations", fetch="EAGER", inversedBy="baseOrg")
     * @ORM\JoinColumn(
     *     name="organization_id",
     *     referencedColumnName="id",
     *     nullable=true,
     *     onDelete="SET NULL"
     * )
     * @var integer
     */
    private $organization;

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
     * @return \Application\Bundle\FrontBundle\Entity\Bases
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set base formats
     *
     * @param \Application\Bundle\FrontBundle\Entity\Formats $baseFormat
     *
     * @return \Application\Bundle\FrontBundle\Entity\Bases
     */
    public function setBaseFormat(\Application\Bundle\FrontBundle\Entity\Formats $baseFormat)
    {
        $this->baseFormat = $baseFormat;

        return $this;
    }

    /**
     * Get base formats
     *
     * @return \Application\Bundle\FrontBundle\Entity\Formats
     */
    public function getBaseFormat()
    {
        return $this->baseFormat;
    }

    /**
     * Set organization.
     *
     * @param \Application\Bundle\FrontBundle\Entity\Organizations $organization
     *
     * @return \Application\Bundle\FrontBundle\Entity\Bases
     */
    public function setOrganization(\Application\Bundle\FrontBundle\Entity\Organizations $organization)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization
     *
     * @return \Application\Bundle\FrontBundle\Entity\Organizations
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * Get score
     *
     * @return float
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set score
     *
     * @param float $score
     *
     * @return float
     */
    public function setScore($score)
    {
        $this->score = $score;
    }

}
