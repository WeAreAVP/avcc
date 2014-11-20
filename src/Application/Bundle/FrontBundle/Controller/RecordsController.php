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
        $this->keywords = array('title',
            'description', 'collection_name',
            'creation_date', 'content_date', 'genre_terms', 'contributor',
        );
        $this->defaultFields = new DefaultFields();
        $this->limit = 10;
    }

    /**
     * Lists all AudioRecords entities.
     *
     * @Route("/", name="record_list")
     * @Method("GET")
     * @Template("ApplicationFrontBundle:Records:index.html.php") 
     * @return array
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $shpinxInfo = $this->getSphinxInfo();
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo);

        $isAjax = FALSE;
        if ($request->isXmlHttpRequest()) {
            $isAjax = TRUE;
            $this->getFacetRequest($request);
            $criteria = $this->criteria();
            $facet['mediaType'] = $sphinxSearch->facetSelect('media_type', $criteria);
            $facet['formats'] = $sphinxSearch->facetSelect('format', $criteria);
            $facet['commercialUnique'] = $sphinxSearch->facetSelect('commercial', $criteria);
            $facet['bases'] = $sphinxSearch->facetSelect('base', $criteria);
            $facet['recordingStandards'] = $sphinxSearch->facetSelect('recording_standard', $criteria);
            $facet['printTypes'] = $sphinxSearch->facetSelect('print_type', $criteria);
            $facet['projectNames'] = $sphinxSearch->facetSelect('project', $criteria);
            $facet['reelDiameters'] = $sphinxSearch->facetSelect('reel_diameter', $criteria);
            $facet['discDiameters'] = $sphinxSearch->facetSelect('disk_diameter', $criteria);
            $facet['acidDetection'] = $sphinxSearch->facetSelect('acid_detection', $criteria);
            $facet['collectionNames'] = $sphinxSearch->facetSelect('collection_name', $criteria);
//            $facetArr = $this->removeEmpty($facet);
//            print_r($facetArr);
//            exit;
            $html = $this->render('ApplicationFrontBundle:Records:index.html.php', array(
                'facets' => $facet,
                'columns' => $this->columns,
                'isAjax' => $isAjax
            ));
            echo json_encode(array('html' => $html->getContent()));
            exit;
        }
        $facet['mediaType'] = $sphinxSearch->facetSelect('media_type');
        $facet['formats'] = $sphinxSearch->facetSelect('format');
        $facet['commercialUnique'] = $sphinxSearch->facetSelect('commercial');
        $facet['bases'] = $sphinxSearch->facetSelect('base');
        $facet['recordingStandards'] = $sphinxSearch->facetSelect('recording_standard');
        $facet['printTypes'] = $sphinxSearch->facetSelect('print_type');
        $facet['projectNames'] = $sphinxSearch->facetSelect('project');
        $facet['reelDiameters'] = $sphinxSearch->facetSelect('reel_diameter');
        $facet['discDiameters'] = $sphinxSearch->facetSelect('disk_diameter');
        $facet['acidDetection'] = $sphinxSearch->facetSelect('acid_detection');
        $facet['collectionNames'] = $sphinxSearch->facetSelect('collection_name');
//        print_r($facet);exit;
        return array(
            'facets' => $facet,
            'columns' => $this->columns,
            'isAjax' => $isAjax
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

        $shpinxInfo = $this->getSphinxInfo();
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo);
        $criteria = $this->criteria();
        $result = $sphinxSearch->select($offset, $limit, $sortIndex, $sortOrder, $criteria);
//        print_r($result);
//        exit;
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

    /**
     * Get sphinx parameters
     * 
     * @return array
     */
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
            $session->remove('facetData');
            $session->set('facetData', $data);
        } else {
            $session->remove('facetData');
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
        $facetData = $session->get('facetData');
        if (isset($facetData['mediaType'])) {
            $criteriaArr['s_media_type'] = $facetData['mediaType'];
        }
        if (isset($facetData['commercial'])) {
            $criteriaArr['s_commercial'] = $facetData['commercial'];
        }
        if (isset($facetData['format'])) {
            $criteriaArr['s_format'] = $facetData['format'];
        }
        if (isset($facetData['base'])) {
            $criteriaArr['s_base'] = $facetData['base'];
        }
        if (isset($facetData['collectionName'])) {
            $criteriaArr['s_collection_name'] = $facetData['collectionName'];
        }
        if (isset($facetData['recordingStandard'])) {
            $criteriaArr['s_recording_standard'] = $facetData['recordingStandard'];
        }
        if (isset($facetData['printType'])) {
            $criteriaArr['s_print_type'] = $facetData['printType'];
        }
        if (isset($facetData['reelDiameter'])) {
            $criteriaArr['s_reel_diameter'] = $facetData['reelDiameter'];
        }
        if (isset($facetData['discDiameter'])) {
            $criteriaArr['s_disk_diameter'] = $facetData['discDiameter'];
        }
        if (isset($facetData['acidDetection'])) {
            $criteriaArr['s_acid_detection'] = $facetData['acidDetection'];
        }
        if (isset($facetData['project'])) {
            $criteriaArr['s_project'] = $facetData['project'];
        }
        if (isset($facetData['is_review_check'])) {
            $criteriaArr['is_review'] = $facetData['is_review_check'];
        }
        if (isset($facetData['creationDate'])) {
            $criteriaArr['s_creation_date'] = $facetData['creationDate'];
        }
        if (isset($facetData['contentDate'])) {
            $criteriaArr['s_content_date'] = $facetData['contentDate'];
        }
        if ($facetData['facet_keyword_search']) {
            $keywords = json_decode($facetData['facet_keyword_search'], true);
            foreach ($keywords as $keyword) {
                if ($keyword['type'] == 'all') {
                    foreach ($this->keywords as $key) {
                        $criteriaArr['*'] = $keyword['value'];
                    }
                } else {
                    $criteriaArr['s_' . $keyword['type']] = $keyword['value'];
                }
            }
        }
        return $criteriaArr;
    }

    protected function getFacetRequest(Request $request)
    {
        $data = $request->query->all();
        $session = $this->getRequest()->getSession();
        if ($data) {
            $session->remove('facetData');
            $session->set('facetData', $data);
        } else {
            $session->remove('facetData');
        }
    }

    protected function removeEmpty($array)
    {
        $result = array();
        foreach ($array as $facet) {
            foreach ($facet as $key => $value) {
                if ($key != "total") {
                    if ($value == '' && $value == null && empty($value)) {
                        $result[][$key] = $value;
                    }
                }
            }
        }

        return $result;
    }

}
