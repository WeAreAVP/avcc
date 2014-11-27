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
use Application\Bundle\FrontBundle\Entity\ImportExport;
use Application\Bundle\FrontBundle\Helper\SphinxHelper;
 
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
        $this->limit = 5;
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
        $facet['mediaType'] = $this->removeEmpty($sphinxSearch->facetSelect('media_type', $criteria, $parentFacet), 'media_type');
        $facet['formats'] = $this->removeEmpty($sphinxSearch->facetSelect('format', $criteria, $parentFacet), 'format');
        $facet['commercialUnique'] = $this->removeEmpty($sphinxSearch->facetSelect('commercial', $criteria, $parentFacet), 'commercial');
        $facet['bases'] = $this->removeEmpty($sphinxSearch->facetSelect('base', $criteria, $parentFacet), 'base');
        $facet['recordingStandards'] = $this->removeEmpty($sphinxSearch->facetSelect('recording_standard', $criteria, $parentFacet), 'recording_standard');
        $facet['printTypes'] = $this->removeEmpty($sphinxSearch->facetSelect('print_type', $criteria, $parentFacet), 'print_type');
        $facet['projectNames'] = $this->removeEmpty($sphinxSearch->facetSelect('project', $criteria, $parentFacet), 'project');
        $facet['reelDiameters'] = $this->removeEmpty($sphinxSearch->facetSelect('reel_diameter', $criteria, $parentFacet), 'reel_diameter');
        $facet['discDiameters'] = $this->removeEmpty($sphinxSearch->facetSelect('disk_diameter', $criteria, $parentFacet), 'disk_diameter');
        $facet['acidDetection'] = $this->removeEmpty($sphinxSearch->facetSelect('acid_detection', $criteria, $parentFacet), 'acid_detection');
        $facet['collectionNames'] = $this->removeEmpty($sphinxSearch->facetSelect('collection_name', $criteria, $parentFacet), 'collection_name');

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
        $session = $this->getRequest()->getSession();
        $tableView = $this->defaultFields->recordDatatableView($records, $session);

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
        $facetData = $this->getFacetFromSession();
        $makeCriteria = new SphinxHelper();
        $criteria = $makeCriteria->makeSphinxCriteria($facetData);
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

    protected function removeEmpty($facet, $index)
    {
        $result = array();
        foreach ($facet as $key => $value) {
            foreach ($value as $column => $row) {
                if ($column == $index && !empty($row))
                    $result[] = $value;
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

    /**
     * Make records to display for dataTables.
     *
     * @param Request $request
     *
     * @Route("/saveState", name="record_saveState")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:Records:saveState.html.php")
     * @return json
     */
    public function saveStateAction(Request $request)
    {
        $data = $request->request->all();
        $session = $this->getRequest()->getSession();
        $checked = array();
        $recordsIds = null;
        if ($data['is_all']) {
            $session->set("allRecords", $data['checked']);
            if (!$data['checked'])
                $session->remove("saveRecords");
            $recordsIds = 'all';
        } else {
            if ($session->has("saveRecords")) {
                $checked = $session->get("saveRecords");
            }
            $isChecked = $data['checked'];
            $recordIds = $data['id'];
            $recordsIdsArr = explode(',', rtrim($recordIds, ','));
            foreach ($recordsIdsArr as $recordId) {
                if ($isChecked) {
                    if (!in_array($recordId, $checked))
                        $checked[] = $recordId;
                } else {
                    if (($key = array_search($recordId, $checked)) !== false)
                        unset($checked[$key]);
                }
            }
            $session->set("saveRecords", $checked);
            $recordsIds = implode(",", $checked);
        }
        echo json_encode(array('success' => TRUE, 'recordIds' => $recordsIds));
        exit;
    }

    /**
     * Make records to display for dataTables.
     *
     * @param Request $request
     *
     * @Route("/export", name="record_export")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:Records:default.html.php")
     * @return json
     */
    public function exportAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $data = $request->request->all();
            $session = $this->getRequest()->getSession();
            $facetData = '';
            if ($session->has('facetData')) {
                $facetData = json_encode(array('criteria' => $session->get('facetData')));
            }
            $type = $data['type'];
            $records = $data['records'];
            $export = new ImportExport();
            $export->setUser($this->getUser());
            $export->setFormat($type);
            $export->setType("export");
            $export->setStatus(0);
            if ($records == 'all') {
                $export->setQueryOrId('all');
                if ($facetData) {
                    $export->setQueryOrId($facetData);
                }
            } else {
                $recordIds = explode(',', $records);
                if ($recordIds) {
                    $export->setQueryOrId(json_encode(array('ids' => $recordIds), JSON_NUMERIC_CHECK));
                }
            }
//            $em->persist($export);
//            $em->flush();

//            $job = new Job('avcc:export-report', array('id' => $export->getId()));
//            $em->persist($job);
//            $em->flush($job);
            if ($session->has("saveRecords")) {
                $session->remove("saveRecords");
            }
            echo json_encode(array('success' => true));
            exit;
        }
    }

}
