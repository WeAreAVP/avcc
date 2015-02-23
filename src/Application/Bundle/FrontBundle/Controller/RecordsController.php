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
use JMS\JobQueueBundle\Entity\Job;
use DateInterval;
use DateTime;
use PHPExcel_Cell;

/**
 * Records controller.
 *
 * @Route("/records")
 *
 */
class RecordsController extends Controller {

    /**
     * Object of DefaultFields
     * @var DefaultFields
     */
    private $defaultFields;

    /**
     * Columns for datatable.
     * @var array
     */
    private $columns = array();

    /**
     * Default limit for query.
     * @var integer
     */
    private $limit;

    /**
     * Constructor of RecordsController
     */
    public function __construct() {
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
     * @param Request $request
     *
     * @Route("/", name="record_list")
     * @Method("GET")
     * @Template("ApplicationFrontBundle:Records:index.html.php")
     * @return array
     */
    public function indexAction(Request $request) {
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
        $facet['mediaType'] = $this->removeEmpty($sphinxSearch->facetSelect('media_type', $this->getUser(), $criteria, $parentFacet), 'media_type');
        $facet['formats'] = $this->removeEmpty($sphinxSearch->facetSelect('format', $this->getUser(), $criteria, $parentFacet), 'format');
        $facet['commercialUnique'] = $this->removeEmpty($sphinxSearch->facetSelect('commercial', $this->getUser(), $criteria, $parentFacet), 'commercial');
        $facet['bases'] = $this->removeEmpty($sphinxSearch->facetSelect('base', $this->getUser(), $criteria, $parentFacet), 'base');
        $facet['recordingStandards'] = $this->removeEmpty($sphinxSearch->facetSelect('recording_standard', $this->getUser(), $criteria, $parentFacet), 'recording_standard');
        $facet['printTypes'] = $this->removeEmpty($sphinxSearch->facetSelect('print_type', $this->getUser(), $criteria, $parentFacet), 'print_type');
        $facet['projectNames'] = $this->removeEmpty($sphinxSearch->facetSelect('project', $this->getUser(), $criteria, $parentFacet), 'project');
        $facet['reelDiameters'] = $this->removeEmpty($sphinxSearch->facetSelect('reel_diameter', $this->getUser(), $criteria, $parentFacet), 'reel_diameter');
        $facet['discDiameters'] = $this->removeEmpty($sphinxSearch->facetSelect('disk_diameter', $this->getUser(), $criteria, $parentFacet), 'disk_diameter');
        $facet['acidDetection'] = $this->removeEmpty($sphinxSearch->facetSelect('acid_detection', $this->getUser(), $criteria, $parentFacet), 'acid_detection');
        $facet['collectionNames'] = $this->removeEmpty($sphinxSearch->facetSelect('collection_name', $this->getUser(), $criteria, $parentFacet), 'collection_name');
        $organizations = $em->getRepository('ApplicationFrontBundle:Organizations')->findAll();
        $view = array(
            'facets' => $facet,
            'columns' => $this->columns,
            'isAjax' => $isAjax,
            'organizations' => $organizations
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
    public function dataTableAction(Request $request) {
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
    protected function getSphinxInfo() {
        return $this->container->getParameter('sphinx_param');
    }

    /**
     * Set/unset facet values from session.
     *
     * @param Request $request
     *
     * @Route("/facets", name="record_facets")
     * @Method("POST")
     */
    public function facetsAction(Request $request) {
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
    protected function criteria() {
        $facetData = $this->getFacetFromSession();
        $makeCriteria = new SphinxHelper();
        $criteria = $makeCriteria->makeSphinxCriteria($facetData);

        return $criteria;
    }

    /**
     * Set/unset facet values from session.
     *
     * @param Request $request
     *
     */
    protected function getFacetRequest(Request $request) {
        $data = $request->query->all();
        $session = $this->getRequest()->getSession();
        if ($data) {
            $session->remove('facetData');
            $session->set('facetData', $data);
        } else {
            $session->remove('facetData');
        }
    }

    /**
     * Remove empty values from array.
     *
     * @param array  $facet
     * @param string $index
     *
     * @return array
     */
    protected function removeEmpty($facet, $index) {
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
    protected function getFacetFromSession() {
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
    public function saveStateAction(Request $request) {
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
            if (!$data['checked']) {
                $session->remove("saveRecords");
            }
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
    public function exportAction(Request $request) {
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
            $em->persist($export);
            $em->flush();

            $job = new Job('avcc:export-report', array('id' => $export->getId()));
            $date = new DateTime();
            $date->add(new DateInterval('PT1M'));
            $job->setExecuteAfter($date);
            $em->persist($job);
            $em->flush($job);
            $session->remove("saveRecords");
            $session->remove("allRecords");
            echo json_encode(array('success' => true));
            exit;
        }
    }

    /**
     * Insert all records in sphinx
     *
     * @Route("/sphinx", name="record_sphinx")
     * @Template("ApplicationFrontBundle:Records:default.html.php")
     */
    public function allInSphinx() {
        $em = $this->getDoctrine()->getManager();
        $records = $em->getRepository('ApplicationFrontBundle:Records')->findAll();
        $shpinxInfo = $this->getSphinxInfo();
        foreach ($records as $record) {
            $recordId = $record->getId();
            $sphinxSearch = new SphinxSearch($em, $shpinxInfo, $recordId, $record->getMediaType()->getId());
            $row = $sphinxSearch->insert();

            echo "affected row --" . $row;
            echo '<br />';
        }
        exit;
    }

    /**
     * Make records to display for dataTables.
     *
     * @param Request $request
     *
     * @Route("/exportMerge", name="record_export_merge")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:Records:default.html.php")
     * @return json
     */
    public function exportMergeAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $data = $request->request->all();
        $session = $this->getRequest()->getSession();
        $facetData = '';
        if ($session->has('facetData')) {
            $facetData = json_encode(array('criteria' => $session->get('facetData')));
        }
        $type = $data['emfiletype'];
        $records = $data['emrecordIds'];
        if ($request->files->get('mergetofile')) {
            $originalFileName = $request->files->get('mergetofile')->getClientOriginalName();
            $uploadedFileSize = $request->files->get('mergetofile')->getClientSize();
            $newFileName = null;
            if ($uploadedFileSize > 0) {
                $folderPath = $this->container->getParameter('webUrl') . 'merge/' . date('Y') . '/' . date('m') . '/';
                if (!is_dir($folderPath))
                    mkdir($folderPath, 0777, TRUE);
                $extension = $request->files->get('mergetofile')->getClientOriginalExtension();
                $newFileName = $this->getUser()->getId() . "_exportmerge" . time() . "." . $extension;
                if ($type == $extension) {
                    $request->files->get('mergetofile')->move($folderPath, $newFileName);
                    if (!$request->files->get('mergetofile')->isValid()) {
                        echo 'file uploaded';
                    }
                    $export = new ImportExport();
                    $export->setUser($this->getUser());
                    $export->setFormat($type);
                    $export->setType("export_merge");
                    $export->setFileName($newFileName);
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
                    $em->persist($export);
                    $em->flush();
//
                    $job = new Job('avcc:export-merge-report', array('id' => $export->getId()));
                    $date = new DateTime();
                    $date->add(new DateInterval('PT1M'));
                    $job->setExecuteAfter($date);
                    $em->persist($job);
                    $em->flush($job);
                    $message = array('heading' => 'Export Merge', 'message' => 'Merge and export request successfully sent. You will receive an email shortly with download link.');
                    $this->get('session')->getFlashBag()->add('report_success', $message);
                } else {
                    $message = array('heading' => 'Export Merge', 'message' => 'File formate is not correct. Please try again.');
                    $this->get('session')->getFlashBag()->add('report_error', $message);
                }
            } else {
                $message = array('heading' => 'Export Merge', 'message' => 'File is empty. Please try again.');
                $this->get('session')->getFlashBag()->add('report_error', $message);
            }
        } else {
            $message = array('heading' => 'Export Merge', 'message' => 'Select file that require to merge. Please try again.');
            $this->get('session')->getFlashBag()->add('report_error', $message);
        }
        $session->remove("saveRecords");
        $session->remove("allRecords");

        return $this->redirect($this->generateUrl('record_list'));
    }

    /**
     * Finds and displays a AudioRecords entity.
     *
     * @param integer $id
     *
     * @Route("/record/{id}", name="record_show")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationFrontBundle:Records')->findOneBy(array('id' => $id));
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Records entity.');
        }
        $entityArray = array();
        $entityArray["mediaType"] = $entity->getMediaType()->getName();
        $entityArray["uniqueId"] = $entity->getUniqueId();
        $entityArray["project"] = $entity->getProject()->getName();
        $entityArray["location"] = $entity->getLocation();
        $entityArray["format"] = $entity->getFormat()->getName();
        $entityArray["title"] = $entity->getTitle();
        $entityArray["collectionName"] = $entity->getCollectionName();
        $entityArray["description"] = $entity->getDescription();
        $entityArray["contentDuration"] = $entity->getContentDuration();
        $entityArray["creationDate"] = $entity->getCreationDate();
        $entityArray["contentDate"] = $entity->getContentDate();
        $entityArray["isReview"] = $entity->getIsReview();
        $entityArray["genreTerms"] = $entity->getGenreTerms();
        $entityArray["contributor"] = $entity->getContributor();
        $entityArray["generation"] = $entity->getGeneration();
        $entityArray["part"] = $entity->getPart();
        $entityArray["copyrightRestrictions"] = $entity->getCopyrightRestrictions();
        $entityArray["duplicatesDerivatives"] = $entity->getDuplicatesDerivatives();
        $entityArray["relatedMaterial"] = $entity->getRelatedMaterial();
        $entityArray["conditionNote"] = $entity->getConditionNote();
        $entityArray["commercial"] = $entity->getCommercial() ? $entity->getCommercial()->getName() : '';
        $entityArray["reelDiameters"] = $entity->getReelDiameters() ? $entity->getReelDiameters()->getName() : '';
        if ($entity->getMediaType()->getId() == 1) {
            $entityArray['diskDiameters'] = ($entity->getAudioRecord()->getDiskDiameters()) ? $entity->getAudioRecord()->getDiskDiameters()->getName() : "";
            $entityArray['bases'] = ($entity->getAudioRecord()->getBases()) ? $entity->getAudioRecord()->getBases()->getName() : "";
            $entityArray['mediaDiameters'] = ($entity->getAudioRecord()->getMediaDiameters()) ? $entity->getAudioRecord()->getMediaDiameters()->getName() : "";
            $entityArray['mediaDuration'] = ($entity->getAudioRecord()->getMediaDuration()) ? $entity->getAudioRecord()->getMediaDuration() : "";
            $entityArray['recordingSpeed'] = ($entity->getAudioRecord()->getRecordingSpeed()) ? $entity->getAudioRecord()->getRecordingSpeed()->getName() : "";
            $entityArray['tapeThickness'] = ($entity->getAudioRecord()->getTapeThickness()) ? $entity->getAudioRecord()->getTapeThickness()->getName() : "";
            $entityArray['slides'] = ($entity->getAudioRecord()->getSlides()) ? $entity->getAudioRecord()->getSlides()->getName() : "";
            $entityArray['trackTypes'] = ($entity->getAudioRecord()->getTrackTypes()) ? $entity->getAudioRecord()->getTrackTypes()->getName() : "";
            $entityArray['monoStereo'] = ($entity->getAudioRecord()->getMonoStereo()) ? $entity->getAudioRecord()->getMonoStereo()->getName() : "";
            $entityArray['noiceReduction'] = ($entity->getAudioRecord()->getNoiceReduction()) ? $entity->getAudioRecord()->getNoiceReduction()->getName() : "";
        } elseif ($entity->getMediaType()->getId() == 2) {
            $entityArray['printType'] = ($entity->getFilmRecord()->getPrintType()) ? $entity->getFilmRecord()->getPrintType()->getName() : "";
            $entityArray['reelCore'] = ($entity->getFilmRecord()->getReelCore()) ? $entity->getFilmRecord()->getReelCore()->getName() : "";
            $entityArray['footage'] = ($entity->getFilmRecord()->getFootage()) ? $entity->getFilmRecord()->getFootage() : "";
            $entityArray['mediaDiameter'] = ($entity->getFilmRecord()->getMediaDiameter()) ? $entity->getFilmRecord()->getMediaDiameter() : "";
            $entityArray['bases'] = ($entity->getFilmRecord()->getBases()) ? $entity->getFilmRecord()->getBases()->getName() : "";
            $entityArray['colors'] = ($entity->getFilmRecord()->getColors()) ? $entity->getFilmRecord()->getColors()->getName() : "";
            $entityArray['sound'] = ($entity->getFilmRecord()->getSound()) ? $entity->getFilmRecord()->getSound()->getName() : "";
            $entityArray['frameRate'] = ($entity->getFilmRecord()->getFrameRate()) ? $entity->getFilmRecord()->getFrameRate()->getName() : "";
            $entityArray['acidDetectionStrip'] = ($entity->getFilmRecord()->getAcidDetectionStrip()) ? $entity->getFilmRecord()->getAcidDetectionStrip()->getName() : "";
            $entityArray['shrinkage'] = ($entity->getFilmRecord()->getShrinkage()) ? $entity->getFilmRecord()->getShrinkage() : "";
        } else {
            $entityArray['cassetteSize'] = ($entity->getVideoRecord()->getCassetteSize()) ? $entity->getVideoRecord()->getCassetteSize()->getName() : "";
            $entityArray['mediaDuration'] = ($entity->getVideoRecord()->getMediaDuration()) ? $entity->getVideoRecord()->getMediaDuration() : "";
            $entityArray['formatVersion'] = ($entity->getVideoRecord()->getFormatVersion()) ? $entity->getVideoRecord()->getFormatVersion()->getName() : "";
            $entityArray['recordingSpeed'] = ($entity->getVideoRecord()->getRecordingSpeed()) ? $entity->getVideoRecord()->getRecordingSpeed()->getName() : "";
            $entityArray['recordingStandard'] = ($entity->getVideoRecord()->getRecordingStandard()) ? $entity->getVideoRecord()->getRecordingStandard()->getName() : "";
        }

//        echo "<pre>";
//        print_r($entityArray);
//        die;
        $fieldsObj = new DefaultFields();
        $userViewSettings = $fieldsObj->getFieldSettings($this->getUser(), $em);

        return $this->render('ApplicationFrontBundle:Records:show.html.php', array(
                    'entity' => $entity,
                    'entityArray' => $entityArray,
                    'fieldSettings' => $userViewSettings
        ));
    }

    /**
     * Insert score values.
     *
     * @param Request $request
     *
     * @Route("/updatescore", name="record_udatescore")
     * @Method("GET")
     * @Template("ApplicationFrontBundle:Records:updateScore.html.php")
     * @return array
     */
    public function uploapScoreAction(Request $request) {
//        return array();
    }

    /**
     * Save score values in db
     *
     * @param Request $request
     *
     * @Route("/savescore", name="record_savescore")
     * @Method("POST")
     * @Template("ApplicationFrontBundle:Records:updateScore.html.php")
     * @return array
     */
    public function saveScoreAction(Request $request) {
        if ($request->files->get('uploadfile')) {
            $originalFileName = $request->files->get('uploadfile')->getClientOriginalName();
            $uploadedFileSize = $request->files->get('uploadfile')->getClientSize();

            $newFileName = null;
            if ($uploadedFileSize > 0) {
                $folderPath = $this->container->getParameter('webUrl') . 'uploads/' . date('Y') . '/' . date('m') . '/';
//                $folderPath = '/Applications/XAMPP/htdocs/avcc/uploads/';
                if (!is_dir($folderPath))
                    mkdir($folderPath, 0777, TRUE);
                $extension = $request->files->get('uploadfile')->getClientOriginalExtension();
                $newFileName = $this->getUser()->getId() . "_score" . time() . "." . $extension;
                $validTypes = array('csv', 'xlsx');
                if (in_array($extension, $validTypes)) {
                    $newfile = $folderPath . $newFileName;
                    $request->files->get('uploadfile')->move($folderPath, $newFileName);
                    if (!$request->files->get('uploadfile')->isValid()) {
                        echo 'file uploaded<br />';
                        $em = $this->getDoctrine()->getManager();
                        $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject($newfile);
                        $isUpdate = false;
                        foreach ($phpExcelObject->getWorksheetIterator() as $worksheet) {
                            $highestRow = $worksheet->getHighestRow();
                            $highestColumn = $worksheet->getHighestColumn();
                            $excelCell = new PHPExcel_Cell(null, null, $worksheet);
                            $highestColumnIndex = $excelCell->columnIndexFromString($highestColumn);
                            if ($highestRow > 0) {
                                for ($row = 3; $row <= 3; ++$row) {
                                    for ($col = 0; $col < $highestColumnIndex; ++$col) {
                                        $cell = $worksheet->getCellByColumnAndRow($col, $row);
                                        $fileColumns[$col] = $cell->getValue();
                                    }
                                }

                                foreach ($fileColumns as $fileColumn) {
                                    if ($fileColumn) {
                                        for ($row = 4; $row <= $highestRow; ++$row) {
                                            $vocabValueCell = $worksheet->getCellByColumnAndRow(1, $row);
                                            $vocabValue = $vocabValueCell->getValue();
                                            if ($vocabValue) {
                                                $entityNameCell = $worksheet->getCellByColumnAndRow(0, $row);
                                                $entityName = str_replace(" ", "", ucfirst($entityNameCell->getValue()));
                                                if ($entityName) {
                                                    $records = $em->getRepository('ApplicationFrontBundle:' . $entityName)->findBy(array('name' => trim($vocabValue)));
                                                    if ($records) {
                                                        $isUpdate = true;
                                                        $scoreValueCell = $worksheet->getCellByColumnAndRow(2, $row);
                                                        $scoreValue = $scoreValueCell->getValue();
                                                        if ($scoreValue != "") {
                                                            foreach ($records as $record) {
                                                                $record->setScore($scoreValue);
                                                                $em->flush();
                                                            }
                                                            $updated[] = $entityName . " : " . $vocabValue . " : " . $scoreValue;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if ($isUpdate)
                        unlink($newfile);
                }
            }

            return array('updated' => isset($updated) ? $updated : null);
        }
    }

}
