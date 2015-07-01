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

/**
 * ReelCore
 *
 * @ORM\Table(name="reel_core")
 * @ORM\Entity(repositoryClass="Application\Bundle\FrontBundle\Entity\ReelCoreRepository")
 */
class ReelCore {

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
     * @Assert\NotBlank(message="Reel core name is required")
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
     * @var integer
     *
     * @ORM\Column(name="sort_order", type="integer", options={"default" = 9999})
     */
    private $order = 9999;

    /**
     * @ORM\ManyToOne(targetEntity="Organizations", fetch="EAGER", inversedBy="reelCoreOrg")
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
     * Returns reel core
     *
     * @return string
     */
    public function __toString() {
        return $this->getName();
    }

    /**
     * Get Id.
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return \Application\Bundle\FrontBundle\Entity\ReelCore
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Set organization.
     *
     * @param \Application\Bundle\FrontBundle\Entity\Organizations $organization
     *
     * @return \Application\Bundle\FrontBundle\Entity\ReelCore
     */
    public function setOrganization(\Application\Bundle\FrontBundle\Entity\Organizations $organization) {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization
     *
     * @return \Application\Bundle\FrontBundle\Entity\Organizations
     */
    public function getOrganization() {
        return $this->organization;
    }

    /**
     * Get score
     *
     * @return real number
     */
    public function getScore() {
        return $this->score;
    }

    /**
     * Set score
     *
     * @param float $score
     *
     * @return \Application\Bundle\FrontBundle\Entity\Colors
     */
    public function setScore($score) {
        $this->score = $score;
    }

    /**
     * Get order
     *
     * @return integer
     */
    public function getOrder() {
        return $this->order;
    }

    /**
     * Set order
     *
     * @param integer $order
     *
     * @return \Application\Bundle\FrontBundle\Entity\Colors
     */
    public function setOrder($order) {
        $this->order = $order;
    }

}
