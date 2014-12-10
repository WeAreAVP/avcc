<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Bulk Edit controller.
 *
 * @Route("/bulkedit")
 */
class BulkEditController extends Controller
{

    /**
     * Make records to display for dataTables.
     *
     * @param Request $request
     *
     * @Route("/validation", name="bulkedit_validation")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:Records:default.html.php")
     * @return json
     */
    public function validation(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            
            echo json_encode(array('success' => $success, 'msg' => $errorMsg, 'html' => $html, 'count' => $data['total_count']));
            exit;
        }
    }

    public function editAction()
    {
        
    }

}
