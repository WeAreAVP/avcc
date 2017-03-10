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
class ManualChargeReportRepository extends EntityRepository {

    public function getAllYears($organizationID) {
        $query = $this->getEntityManager()
                ->createQuery("SELECT r.year from ApplicationFrontBundle:ManualChargeReport r "
                 . "WHERE r.createdOn IS NOT NULL AND r.organizationId =  :organization GROUP BY r.year");
        $query->setParameter('organization', $organizationID);
        return $query->getResult();
    }

    public function getRecordsForMonthlyCharges($organizationID, $year, $condition, $date = false) {
        $where = '';
        if ($condition) {
            $where = " AND DATE_FORMAT(r.createdOn, '%Y-%m') != :createdAt GROUP BY created_at";
        } else {
            $where = " AND DATE_FORMAT(r.createdOn, '%Y-%m') <= :createdAt";
        }
        $query = $this->getEntityManager()
                ->createQuery("SELECT COUNT(r.id) as total, DATE_FORMAT(r.createdOn, '%Y-%m') as created_at,DATE_FORMAT(r.createdOn, '%Y') as year, DATE_FORMAT(r.createdOn, '%M') as month from ApplicationFrontBundle:Records r "
                . "JOIN r.project u "
                . "JOIN u.organization o "
                . "WHERE r.createdOn IS NOT NULL AND o.id =  :organization " . $where);
        $query->setParameter('organization', $organizationID);
        $query->setParameter('createdAt', $date);
        return $query->getResult();
    }
    
}
