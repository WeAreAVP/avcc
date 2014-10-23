<?php

namespace Application\Bundle\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CassetteSizes
 *
 * @ORM\Table(name="cassette_sizes")
 * @ORM\Entity
 */
class CassetteSizes
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
     * @Assert\NotBlank(message="Cassette size is required")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Formats", fetch="EAGER", inversedBy="cassetteSize")
     * @ORM\JoinColumn(
     *     name="format_id",
     *     referencedColumnName="id",
     *     nullable=true,
     *     onDelete="SET NULL"
     * )
     * @var integer
     *
     */
    private $cassetteSizeFormat;

    /**
     * @ORM\ManyToOne(targetEntity="Organizations", fetch="EAGER", inversedBy="cassetteOrg")
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
     * Returns Cassestte size
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
     * @return \Application\Bundle\FrontBundle\Entity\CassetteSizes
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set base formats
     *
     * @param \Application\Bundle\FrontBundle\Entity\Formats $cassetteSizeFormat
     *
     * @return \Application\Bundle\FrontBundle\Entity\CassetteSizes
     */
    public function setCassetteSizeFormat(\Application\Bundle\FrontBundle\Entity\Formats $cassetteSizeFormat)
    {
        $this->cassetteSizeFormat = $cassetteSizeFormat;

        return $this;
    }

    /**
     * Get base formats
     *
     * @return \Application\Bundle\FrontBundle\Entity\Formats
     */
    public function getCassetteSizeFormat()
    {
        return $this->cassetteSizeFormat;
    }

    /**
     * Set organization.
     *
     * @param \Application\Bundle\FrontBundle\Entity\Organizations $organization
     *
     * @return \Application\Bundle\FrontBundle\Entity\CassettSizes
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
}
