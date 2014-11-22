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

    private $defaultFields;
    private $columns = array();
    private $limit;

    public function __construct()
    {
        $this->columns = array(
            'checkbox_Col' => 'checkboxCol',
            'Project_Name' => 'projectName',
            'Format' => 'format',
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
        $this->limit = 100;
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
        $searchOn = $this->criteria();
        $parentFacet = isset($searchOn['parent_facet']) ? $searchOn['parent_facet'] : null;
        $criteria = $searchOn['criteriaArr'];
        if ($request->isXmlHttpRequest()) {
            $isAjax = TRUE;
            $this->getFacetRequest($request);
            $searchOn = $this->criteria();
            $criteria = $searchOn['criteriaArr'];
            $parentFacet = isset($searchOn['parent_facet']) ? $searchOn['parent_facet'] : null;
        }
        $facet['mediaType'] = $sphinxSearch->facetSelect('media_type', $criteria, $parentFacet);
        $facet['formats'] = $sphinxSearch->facetSelect('format', $criteria, $parentFacet);
        $facet['commercialUnique'] = $sphinxSearch->facetSelect('commercial', $criteria, $parentFacet);
        $facet['bases'] = $sphinxSearch->facetSelect('base', $criteria, $parentFacet);
        $facet['recordingStandards'] = $sphinxSearch->facetSelect('recording_standard', $criteria, $parentFacet);
        $facet['printTypes'] = $sphinxSearch->facetSelect('print_type', $criteria, $parentFacet);
        $facet['projectNames'] = $sphinxSearch->facetSelect('project', $criteria, $parentFacet);
        $facet['reelDiameters'] = $sphinxSearch->facetSelect('reel_diameter', $criteria, $parentFacet);
        $facet['discDiameters'] = $sphinxSearch->facetSelect('disk_diameter', $criteria, $parentFacet);
        $facet['acidDetection'] = $sphinxSearch->facetSelect('acid_detection', $criteria, $parentFacet);
        $facet['collectionNames'] = $sphinxSearch->facetSelect('collection_name', $criteria, $parentFacet);
        $view = array(
            'facets' => $facet,
            'columns' => $this->columns,
            'isAjax' => $isAjax
        );
        if ($request->isXmlHttpRequest()) {
            $html = $this->render('ApplicationFrontBundle:Records:index.html.php', $view);
            echo json_encode(array('html' => $html->getContent()));
            exit;
        } else {
            return $view;
        }
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
            2 => 'format',
            3 => 'unique_id',
            4 => 'title',
            5 => 'collection_name',
            6 => 'location'
        );
        $em = $this->getDoctrine()->getManager();
        $sEcho = $request->query->get('sEcho');
        $sortOrder = $request->query->get('sSortDir_0') ? $request->query->get('sSortDir_0') : 'asc';
        $sortIndex = $request->query->get('iSortCol_0') ? $columns[$request->query->get('iSortCol_0')] : 'title';

        $offset = $request->query->get('iDisplayStart') ? $request->query->get('iDisplayStart') : 0;
        $limit = $request->query->get('iDisplayLength') ? $request->query->get('iDisplayLength') : $this->limit;

        $shpinxInfo = $this->getSphinxInfo();
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo);
        $searchOn = $this->criteria();
        $criteria = $searchOn['criteriaArr'];
        $result = $sphinxSearch->select($this->getUser(), $offset, $limit, $sortIndex, $sortOrder, $criteria);

        $records = $result[0];
        $currentPageTotal = count($records);
        $totalRecords = $result[1][0]['Value'];

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
        $criteria = null;
        $criteriaArr = null;
        $facetData = $this->getFacetFromSession();
        $searchColumns = array(
            'mediaType' => 's_media_type',
            'commercial' => 's_commercial',
            'format' => 's_format',
            'base' => 's_base',
            'collectionName' => 's_collection_name',
            'recordingStandard' => 's_recording_standard',
            'printType' => 's_print_type',
            'reelDiameter' => 's_reel_diameter',
            'discDiameter' => 's_disk_diameter',
            'acidDetection' => 's_acid_detection',
            'project' => 's_project',
            'is_review_check' => 'is_review',
            'creationDate' => 's_creation_date',
            'contentDate' => 's_content_date',
            'contentDate' => 's_content_date',
        );
        foreach ($searchColumns as $key => $value) {
            if (isset($facetData[$key]))
                $criteriaArr[$value] = $facetData[$key];
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
        if ($facetData['parent_facet']) {
            $criteria['parent_facet'] = $facetData['parent_facet'];
        }

        if ($criteriaArr) {
            $criteria['criteriaArr'] = $criteriaArr;
        }

        return $criteria;
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

    /**
     * Get facets from session
     *
     * @return array
     */
    protected function getFacetFromSession()
    {
        $session = $this->getRequest()->getSession();
        $facetData = $session->get('facetData');

        return $facetData;
    }

}
