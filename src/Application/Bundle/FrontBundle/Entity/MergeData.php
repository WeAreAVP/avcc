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
     * @ORM\Column(name="exported_file", type="string", length=250, nullable=false)
     * @Assert\NotBlank(message="Exported file field is required")
     * @Assert\File(
     *     maxSize = "1024k"
     * )
     */
    private $exportedFile;

    /**
     * @var string
     *
     * @ORM\Column(name="merge_to_file", type="string", length=250, nullable=false)
     * @Assert\NotBlank(message="Merge to file field is required")
     * @Assert\File(
     *     maxSize = "1024k"
     * )
     */
    private $mergeToFile;

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
     * Set exported file name
     *
     * @param File $file
     */
    public function setExportedFile(File $file = null)
    {
        $this->exportedFile = $file;
    }

    /**
     * Get exported file
     *
     * @return type
     */
    public function getExportedFile()
    {
        return $this->exportedFile;
    }

    /**
     * Set merge to file field
     *
     * @param File $file
     */
    public function setMergeToFile(File $file = null)
    {
        $this->mergeToFile = $file;
    }

    /**
     * Return merge to file name
     *
     * @return string
     */
    public function getMergeToFile()
    {
        return $this->mergeToFile;
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
