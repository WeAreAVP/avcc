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
use Doctrine\ORM\EntityRepository;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of MonthlyChargesRepository
 *
 * @author rimsha
 */
class MonthlyChargesRepository extends EntityRepository {
    public function getByTotalRecord($total, $condition) {
        $where = '';
        if ($condition) {
            $where = 'mc.end >= :total';
        } else {
            $where = 'mc.end IS NULL';
        }
        $query = $this->getEntityManager()
                ->createQuery("SELECT mc.charges from ApplicationFrontBundle:MonthlyCharges mc "
                . "WHERE mc.start <= :total AND " . $where);
        $query->setParameter('total', $total);
        return $query->getResult();
    }
}