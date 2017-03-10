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

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Entity\MonthlyChargeReport;
use Application\Bundle\FrontBundle\Entity\MonthlyChargeReportRepository;

/**
 * MonthlyChargeReport controller.
 *
 * @Route("/monthly_charge_report")
 */
class MonthlyChargeReportController extends MyController {

    /**
     * Lists all Monthly charges entities.
     *
     * @Route("/", name="monthly_charge_report")
     * @Route("/{organizationId}/", name="monthly_report_org")
     * @Route("/{organizationId}/{year}", name="monthly_report_orgYear")
     * @Route("/year/{year}", name="monthly_report_year")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function indexAction($organizationId = null, $year = null) {
        @set_time_limit(0);
        @ini_set("memory_limit", -1); # 1GB
        @ini_set("max_execution_time", 0); # unlimited
        $em = $this->getDoctrine()->getManager();
        $entities = '';
        $years = '';
        $name = '';
        $selYear = '';
        if (in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles())) {
            if ($organizationId && $year) {
                $selYear = $year;
                $org = $em->getRepository('ApplicationFrontBundle:Organizations')->find($organizationId);
                $name = $org->getName();
                $years = $em->getRepository('ApplicationFrontBundle:ManualChargeReport')->getAllYears($organizationId);
                $entities = $em->getRepository('ApplicationFrontBundle:ManualChargeReport')->findBy(array('organizationId' => $organizationId, 'year' => $year));
            } else if ($organizationId) {
                $org = $em->getRepository('ApplicationFrontBundle:Organizations')->find($organizationId);
                $name = $org->getName();
                $years = $em->getRepository('ApplicationFrontBundle:ManualChargeReport')->getAllYears($organizationId);
            }  
        } else {
            if ($year) {
                $selYear = $year;
                $entities = $em->getRepository('ApplicationFrontBundle:MonthlyChargeReport')->findBy(array('organizationId' => $this->getUser()->getOrganizations()->getId(), 'year' => $year));
            }
            $years = $em->getRepository('ApplicationFrontBundle:MonthlyChargeReport')->getAllYears($this->getUser()->getOrganizations()->getId());
        }
        return array(
            'entities' => $entities,
            'years' => $years,
            'selYear' => $selYear,
            'org_name' => $name,
            'org_id' => $organizationId        
        );
    }
    

}

?>
