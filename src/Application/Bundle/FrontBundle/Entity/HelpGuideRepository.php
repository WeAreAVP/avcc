<?php
/**
 * AVCC
 * 
 * @category AVCC
 * @package  Application
 * @author   Nouman Tayyab <nouman@weareavp.com>
 * @author   Rimsha Khalid <rimsha@weareavp.com>
 * @license  AGPLv3 http://www.gnu.org/licenses/agpl-3.0.txt
 * @copyright Audio Visual Preservation Solutions, Inc
 * @link     http://avcc.weareavp.com
 */
namespace Application\Bundle\FrontBundle\Entity;

use Doctrine\ORM\EntityRepository;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HelpGuideRepository
 *
 * @author rimsha
 */
class HelpGuideRepository extends EntityRepository{
    public function searchHelpGuide($search) {
        $query = $this->getEntityManager()
                ->createQuery("SELECT h from ApplicationFrontBundle:HelpGuide h "
                . "WHERE h.title LIKE '%".$search."%' OR h.description LIKE '%".$search."%' ORDER BY h.order ASC");
//        $query->setParameter('search', $search);
//        $query->setParameter('search1', $search);

        return $query->getResult();
    }
}
