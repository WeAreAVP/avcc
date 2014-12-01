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
     * @ORM\Column(name="exported_file", type="sting", length=64, nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
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
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (is_file($this->getAbsolutePath())) {
            // store the old name to delete after the update
            $this->temp = $this->getAbsolutePath();
        } else {
            $this->path = 'initial';
        }
    }
    
    /**
     * Set user.
     *
     * @param \Application\Bundle\FrontBundle\Entity\Users $user
     *
     * @return \Application\Bundle\FrontBundle\Entity\MergeData
     */
    public function setUser(\Application\Bundle\FrontBundle\Entity\Users $user) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Application\Bundle\FrontBundle\Entity\Users
     */
    public function getUser() {
        return $this->user;
    }
}

