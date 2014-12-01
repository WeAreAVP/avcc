<?php

namespace Application\Bundle\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;

/**
 * MergeData
 *
 * @ORM\Table(name="merge_data")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class MergeData
{

    private $exportedTemp;

    /**
     * @var string
     *
     * @ORM\Column(name="exported_file", type="string", length=64, nullable=false)
     * @Assert\NotBlank(message="Exported file field is required")
     */
    private $exportedFile;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Users", cascade={"all","merge","persist","refresh","remove"}, fetch="EAGER", inversedBy="userMergeData")
     * @ORM\JoinColumn(
     *     name="user_id",
     *     referencedColumnName="id",
     *     nullable=true,
     *     onDelete="CASCADE"
     * )
     * @var integer
     */
    private $user;

    /**
     * 
     * @param File $file
     */
    public function setExportedFile(File $file = null)
    {
        $this->exportedFile = $file;
    }

    /**
     * 
     * @return type
     */
    public function getExportedFile()
    {
        return $this->exportedFile;
    }

    /**
     * Set user.
     *
     * @param \Application\Bundle\FrontBundle\Entity\Users $user
     *
     * @return \Application\Bundle\FrontBundle\Entity\MergeData
     */
    public function setUser(\Application\Bundle\FrontBundle\Entity\Users $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Application\Bundle\FrontBundle\Entity\Users
     */
    public function getUser()
    {
        return $this->user;
    }

}
