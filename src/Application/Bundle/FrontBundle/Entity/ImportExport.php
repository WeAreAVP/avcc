<?php

namespace Application\Bundle\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
	 *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
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
	 * @ORM\Column(name="query_or_id", type="string")
	 * @var string
	 */
	private $queryOrId;

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
		if ( ! $this->getCreatedOn())
		{
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

	function getId()
	{
		return $this->id;
	}

	function getCreatedOn()
	{
		return $this->createdOn;
	}

	function getUpdatedOn()
	{
		return $this->updatedOn;
	}

	function getUser()
	{
		return $this->user;
	}

	function getType()
	{
		return $this->type;
	}

	function getFormat()
	{
		return $this->format;
	}

	function getQueryOrId()
	{
		return $this->queryOrId;
	}

	function getStatus()
	{
		return $this->status;
	}

	function setId($id)
	{
		$this->id = $id;
	}

	function setCreatedOn(\DateTime $createdOn)
	{
		$this->createdOn = $createdOn;
	}

	function setUpdatedOn(\DateTime $updatedOn)
	{
		$this->updatedOn = $updatedOn;
	}

	function setUser(\Application\Bundle\FrontBundle\Entity\Users $user)
	{
		$this->user = $user;
	}

	function setType($type)
	{
		$this->type = $type;
	}

	function setFormat($format)
	{
		$this->format = $format;
	}

	function setQueryOrId($queryOrId)
	{
		$this->queryOrId = $queryOrId;
	}

	function setStatus($status)
	{
		$this->status = $status;
	}

}
