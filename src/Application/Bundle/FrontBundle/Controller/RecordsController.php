<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Helper\DefaultFields;
use Application\Bundle\FrontBundle\Entity\Records;
use Symfony\Component\HttpFoundation\Session\Session;
use Application\Bundle\FrontBundle\SphinxSearch\SphinxSearch;

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
    private $columns = array();
    private $userFields = array();
    private $session;
    private $offset;
    private $limit;

    public function __construct()
    {
        $this->columns = array(
            'checkbox_Col' => 'checkboxCol',
            'Project_Name' => 'projectName',
            'Unique_ID' => 'uniqueId',
            'Title' => 'title',
            'Collection_Name' => 'collectionName',
            'Location' => 'location'
        );
        ;
        $this->defaultFields = new DefaultFields();
        $this->limit = 10;
    }

    /**
     * Lists all AudioRecords entities.
     *
     * @Route("/", name="record_list")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sphinxSearch = new SphinxSearch($em);
        $facet['mediaType'] = $sphinxSearch->facetSelect('media_type');
        $facet['commercialUnique'] = $sphinxSearch->facetSelect('commercial');
        $facet['bases'] = $sphinxSearch->facetSelect('base');
        $facet['review'] = $sphinxSearch->facetSelect('is_review');
        return array(
            'facets' => $facet,
            'columns' => $this->columns
        );
    }

    /**
     * Make records to display for dataTables.
     * 
     * @param Request $request 
     * 
     * @Route("/dataTable", name="record_dataTable")
     * @Method("GET")
     * @Template("ApplicationFrontBundle:AudioRecords:dataTable.html.php") 
     * @return json
     */
    public function dataTableAction(Request $request)
    {
        $columns = array(0 => '',
            1 => 'title',
            2 => 'unique_id',
            3 => 'title',
            4 => 'collection_name',
            5 => 'location'
        );
        $em = $this->getDoctrine()->getManager();
        $sEcho = $request->query->get('sEcho');
        $sortOrder = $request->query->get('sSortDir_0') ? $request->query->get('sSortDir_0') : 'asc';
        $sortIndex = $request->query->get('iSortCol_0') ? $columns[$request->query->get('iSortCol_0')] : 'title';


        $offset = $request->query->get('iDisplayStart') ? $request->query->get('iDisplayStart') : 0;
        $limit = $request->query->get('iDisplayLength') ? $request->query->get('iDisplayLength') : 10;


        $sphinxSearch = new SphinxSearch($em);
        $criteria = $this->criteria();
        $result = $sphinxSearch->select($offset, $limit, $sortIndex, $sortOrder, $criteria);
        $records = $result[0];
        $currentPageTotal = count($records);
        $resultMeta = $sphinxSearch->selectCount($offset, $limit, $sortIndex, $sortOrder);
        $totalRecords = $resultMeta[1][0]['Value'];
//        print_r($result);exit;

        $tableView = $this->defaultFields->recordDatatableView($records);

        $dataTable = array(
            'sEcho' => intval($sEcho),
            'iTotalRecords' => intval($currentPageTotal),
            'iTotalDisplayRecords' => intval($totalRecords),
            'aaData' => $tableView
        );
        echo json_encode($dataTable);
        exit;
    }

    protected function getSphinxInfo()
    {
        return $this->container->getParameter('sphinx_param');
    }

    /**
     * 
     * @param Request $request
     * 
     * @Route("/facets", name="record_facets")
     * @Method("POST")
     */
    public function facetsAction(Request $request)
    {
        $data = $request->request->all();
        $session = $this->getRequest()->getSession();
        if ($data) {
            if (isset($data['mediaType'])) {
                if ($session->get('mediaType'))
                    $session->remove('mediaType');
                $session->set('mediaType', $data['mediaType']);
            }
            if (isset($data['commercial'])) {
                if ($session->get('commercial'))
                    $session->remove('commercial');
                $session->set('commercial', $data['commercial']);
            }
        }else{
//            $session->clear();
            if ($session->get('mediaType'))
                $session->remove('mediaType');
            if ($session->get('commercial'))
                $session->remove('commercial');
        }
        echo json_encode(array('success' => true));
        exit;
    }

    /**
     * Set criteria for facets
     * 
     * @return array
     */
    protected function criteria()
    {
        $criteriaArr = null;
        $session = $this->getRequest()->getSession();
        if ($session->get('mediaType')) {
            $criteriaArr['mediaType'] = $session->get('mediaType');
        }
        if ($session->get('commercial')) {
            $criteriaArr['commercial'] = $session->get('commercial');
        }
        return $criteriaArr;
    }

}
