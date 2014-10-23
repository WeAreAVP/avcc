<?php
namespace Application\Bundle\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Application\Bundle\FrontBundle\Entity\Formats as Formats;

/**
 * MediaTypes
 *
 * @ORM\Table(name="media_types")
 * @ORM\Entity
 */
class MediaTypes
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
     * @Assert\NotBlank(message="Media type name is required")
     */
    private $name;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Formats",
     *     mappedBy="mediaType",
     *     fetch="EAGER",
     *     indexBy="id"
     * )
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $formats;
    
    /**
     * Media types construct
     */
    public function __construct()
    {
        $this->formats = new ArrayCollection();
    }

    /**
     * Returns Media type name
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
     * @return \Application\Bundle\FrontBundle\Entity\MediaTypes
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Add format
     *
     * @param \Application\Bundle\FrontBundle\Entity\Formats $f     *
     */
    public function addFormat(Formats $f)
    {
         if (!$this->formats->contains($f)) {

             $this->formats[] = $f;
             $f->setMediaType($this);
         }
    }

    /**
     * Remove format
     *
     * @param \Application\Bundle\FrontBundle\Entity\Formats $f     *
     */
    public function removeFormat(Formats $f)
    {
         $this->formats->remove($f);
    }

}
