<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Datatables\RecordsDatatable;
use Application\Bundle\FrontBundle\Helper\DefaultFields;

/**
 * Records controller.
 * 
 * @Route("/")
 *
 */
class RecordsController extends Controller
{

    private $columnOrder = array();
    private $defaultFields;
    
    public function __construct()
    {
        $this->columnOrder = array(
            'checkboxCol' => 'checkboxCol',
            'MediaType' => 'mediaType',
            'Format' => 'format'
        );
        
        $this->defaultFields = new DefaultFields();
    }
    /**
     * Lists all AudioRecords entities.
     *
     * @Route("/", name="record_list")
     * @Method("GET")
     * @Template("ApplicationFrontBundle:AudioRecords:index.html.twig")
     * @return array
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationFrontBundle:Records')->findAll();

        return array(
            'entities' => $entities,
        );
        
//        $postDatatable = $this->get("sg_datatables.records");
//        print_r($postDatatable); exit;
//        $postDatatable->buildDatatableView();
//
//        return array(
//            "datatable" => $postDatatable,
//        );
    }
    
    /**
     * Make records to display for dataTables.
     * 
     * @Template("ApplicationFrontBundle:AudioRecords:dataTable.html.php") 
     * @return json
     */
    public function dataTableAction()
    {
        $em = $this->getDoctrine()->getManager();
        $column = $this->columnOrder;
        $this->session->remove('column');
        $this->session->remove('jscolumn');
        $this->session->remove('columnOrder');
        $this->session->set('jscolumn', 1);
        $this->session->set('column', $this->columnOrder["checkboxCol"]);
        $this->session->set('columnOrder', $this->input->get('sSortDir_0'));
//        $offset = isset($this->session->get('offset')) ? $this->session->get('offset') : 0;
//        $records = $this->sphinx->carrier_list($offset, 100, TRUE);
        $records = $em->getRepository('ApplicationFrontBundle:Records')->findAll();
        $data['total'] = count($records);
//        $record_ids = array_map(array($this, 'map_array'), $records['records']);
        $data['count'] = count($records);
        $tableView = $this->defaultFields->recordDatatableView($records, $this->columnOrder);

        $dataTable = array(
//            'sEcho' => intval($this->input->get('sEcho')),
            'iTotalRecords' => intval($data['count']),
            'iTotalDisplayRecords' => intval($data['count']),
            'aaData' => $tableView
        );
        echo json_encode($dataTable);
    }

    
}
