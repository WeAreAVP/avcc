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
class MonthlyChargeReportRepository extends EntityRepository {

    public function getAllYears($organizationID) {
        $query = $this->getEntityManager()
                ->createQuery("SELECT DATE_FORMAT(r.createdOn, '%Y') as year from ApplicationFrontBundle:Records r "
                . "JOIN r.project u "
                . "JOIN u.organization o "
                . "WHERE r.createdOn IS NOT NULL AND o.id =  :organization GROUP BY year");
        $query->setParameter('organization', $organizationID);
        return $query->getResult();
    }

    public function getRecordsForMonthlyCharges($organizationID, $year, $condition, $date = false) {
        $where = '';
        if ($condition) {
            $where = " AND DATE_FORMAT(r.createdOn, '%Y-%m') != :createdAt";
        } else {
            $where = " AND DATE_FORMAT(r.createdOn, '%Y-%m') <= :createdAt";
        }
        $query = $this->getEntityManager()
                ->createQuery("SELECT COUNT(r.id) as total, DATE_FORMAT(r.createdOn, '%Y-%m') as created_at, DATE_FORMAT(r.createdOn, '%M') as month from ApplicationFrontBundle:Records r "
                . "JOIN r.project u "
                . "JOIN u.organization o "
                . "WHERE r.createdOn IS NOT NULL AND o.id =  :organization AND DATE_FORMAT(r.createdOn, '%Y') = :year " . $where . " GROUP BY created_at");
        $query->setParameter('organization', $organizationID);
        $query->setParameter('createdAt', $date);
        $query->setParameter('year', $year);
        return $query->getResult();
    }
    
}
