<?php
/**
 * AVCC
 * 
 * @category AVCC
 * @package  Application
 * @author   Nouman Tayyab <nouman@avpreserve.com>
 * @author   Rimsha Khalid <rimsha@avpreserve.com>
 * @license  AGPLv3 http://www.gnu.org/licenses/agpl-3.0.txt
 * @copyright Audio Visual Preservation Solutions, Inc
 * @link     http://avcc.avpreserve.com
 */
namespace Application\Bundle\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ImportExport
 *
 * @ORM\Table(name="import_export")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 *
 */
class ImportExport
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
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     * })
     */
    private $user;

    /**
     * @ORM\Column(name="type", type="string",length=50, nullable=true)
     * @var string
     *
     */
    private $type;

    /**
     * @ORM\Column(name="format", type="text", nullable=true)
     * @var string
     *
     */
    private $format;

    /**
     * @ORM\Column(name="query_or_id", type="text")
     * @var string
     */
    private $queryOrId;

    /**
     * @ORM\Column(name="file_name", type="string", nullable=true)
     * @var string
     */
    private $fileName;

    /**
     * @ORM\Column(name="organization_id", type="integer", nullable=true)
     * @var integer
     */
    private $organizationId;
    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=true, options={"default" = 0}) )
     */
    private $status;

    /**
     * @ORM\PrePersist
     */
    public function setCreatedOnValue()
    {
        if ( ! $this->getCreatedOn()) {
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
     * Returns title
     *
     * @return string
     */
    public function __toString()
    {
        return '';
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getFormat()
    {
        return $this->format;
    }

    public function getQueryOrId()
    {
        return $this->queryOrId;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setCreatedOn(\DateTime $createdOn)
    {
        $this->createdOn = $createdOn;
    }

    public function setUpdatedOn(\DateTime $updatedOn)
    {
        $this->updatedOn = $updatedOn;
    }

    public function setUser(\Application\Bundle\FrontBundle\Entity\Users $user)
    {
        $this->user = $user;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function setFormat($format)
    {
        $this->format = $format;
    }

    public function setQueryOrId($queryOrId)
    {
        $this->queryOrId = $queryOrId;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Set file name field     *
     *
     * @param string $file
     */
    public function setFileName($file)
    {
        $this->fileName = $file;
    }

    /**
     * Return file name
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }
    
    /**
     * Set organization Id field     *
     *
     * @param integer $organizationId
     */
    public function setOrganizationId($organizationId)
    {
        $this->organizationId = $organizationId;
    }

    /**
     * Return  organization Id
     *
     * @return integer
     */
    public function getOrganizationId()
    {
        return $this->organizationId;
    }
}
