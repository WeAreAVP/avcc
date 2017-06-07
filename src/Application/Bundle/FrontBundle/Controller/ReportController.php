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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Components\ExportReport;
use Application\Bundle\FrontBundle\SphinxSearch\SphinxSearch;
use Application\Bundle\FrontBundle\Controller\MyController;
use Symfony\Component\HttpFoundation\Response;

/**
 * ReelDiameters controller.
 *
 * @Route("/report")
 */
class ReportController extends MyController {

    /**
     * Show Reports view.
     *
     * @Route("/", name="report")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function indexAction() {
        $session = $this->getRequest()->getSession();
        if (($session->has('termsStatus') && $session->get('termsStatus') == 0) || ($session->has('limitExceed') && $session->get('limitExceed') == 0)) {
            return $this->redirect($this->generateUrl('dashboard'));
        }
        return array();
    }

    /**
     * Generate report as xlsx or csv
     * @param  string $type
     * @Route("/allformats/{type}", name="all_formats")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function allFormatsAction($type) {
        @set_time_limit(0);
        @ini_set("memory_limit", "2000M"); # 2GB
        @ini_set("max_execution_time", 0); # unlimited
        gc_enable();
        if (!in_array($type, array('csv', 'xlsx'))) {
            throw $this->createNotFoundException('Invalid report type');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->getConnection()->getConfiguration()->setSQLLogger(null);
        if (true === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
            $records = $entityManager->getRepository('ApplicationFrontBundle:Records')->findAll();
        else
            $records = $entityManager->getRepository('ApplicationFrontBundle:Records')->findOrganizationRecords($this->getUser()->getOrganizations()->getId());

        $exportComponent = new ExportReport($this->container);
        $phpExcelObject = $exportComponent->generateReport($records);
        $file_name = 'all_records_report';
        $response = $exportComponent->outputReport($type, $phpExcelObject, $file_name);
        $entityManager->flush();
        $entityManager->clear();
        gc_collect_cycles();
        // create the response
        return $response;
        return array();
    }

    /**
     * Generate quantitative report
     *
     * @Route("/quantitative", name="quantitative")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function quantitativeAction() {
        $em = $this->getDoctrine()->getManager();
        $shpinxInfo = $this->container->getParameter('sphinx_param');
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo);
        $result = $sphinxSearch->removeEmpty($sphinxSearch->facetSelect('format', $this->getUser(), null, false, 'media_type', 'media_type'), 'format');
        $highChart = array();
        foreach ($result as $index => $format) {
            $highChart[] = array($format['format'], (int) $format['total']);
        }

        return array('formats' => json_encode($highChart));
    }

    /**
     * Generate Manifest for shipping to vendor and Quote from Vendor report.
     *
     * @Route("/manifest/{type}", name="manifest")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function manifestAction($type) {
        @set_time_limit(0);
        @ini_set("memory_limit", "2000M"); # 2GB
        @ini_set("max_execution_time", 0); # unlimited
        gc_enable();
        if (!in_array($type, array('csv', 'xlsx'))) {
            throw $this->createNotFoundException('Invalid report type');
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->getConnection()->getConfiguration()->setSQLLogger(null);
        if (true === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
            $records = $entityManager->getRepository('ApplicationFrontBundle:Records')->findAll();
        else
            $records = $entityManager->getRepository('ApplicationFrontBundle:Records')->findOrganizationRecords($this->getUser()->getOrganizations()->getId());
        $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("AVCC - AVPreserve")
                ->setTitle('AVCC - Report')
                ->setSubject('Manifest Report')
                ->setDescription('Manifest for shipping to vendor and Quote from Vendor report');
        $activeSheet = $phpExcelObject->setActiveSheetIndex(0);
        $phpExcelObject->getActiveSheet()->setTitle('Manifest Report');

        $exportComponent = new ExportReport($this->container);
        $exportComponent->prepareManifestReport($activeSheet, $records);
        $response = $exportComponent->outputReport($type, $phpExcelObject, 'manifest_report');
        $entityManager->flush();
        $entityManager->clear();
        gc_collect_cycles();

        return $response;
        return array();
    }

    /**
     * Generate prioritization report as xlsx or csv
     * @param  string $type
     * @Route("/prioritization/{type}", name="prioritization_report")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function prioritizationReportAction($type) {
        @set_time_limit(0);
        @ini_set("memory_limit", -1); # 1GB
        @ini_set("max_execution_time", 0); # unlimited
        if (!in_array($type, array('csv', 'xlsx'))) {
            throw $this->createNotFoundException('Invalid report type');
        }
        gc_enable();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->getConnection()->getConfiguration()->setSQLLogger(null);
        if (true === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
            $records = $entityManager->getRepository('ApplicationFrontBundle:Records')->findAll();
        else
            $records = $entityManager->getRepository('ApplicationFrontBundle:Records')->findOrganizationRecords($this->getUser()->getOrganizations()->getId());

        $exportComponent = new ExportReport($this->container);
        $phpExcelObject = $exportComponent->generatePrioritizationReport($records, $entityManager);
        $response = $exportComponent->outputReport($type, $phpExcelObject, 'prioritization_report');
        $entityManager->flush();
        $entityManager->clear();
        gc_collect_cycles();
        // create the response
        return $response;
        return array();
    }

    /**
     * Generate quantitative report
     *
     * @Route("/commercialunique", name="commercialunique")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function commercialuniqueAction() {
        $em = $this->getDoctrine()->getManager();
        $shpinxInfo = $this->container->getParameter('sphinx_param');
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo);
        $result = $sphinxSearch->removeEmpty($sphinxSearch->facetSelect('commercial', $this->getUser()), 'commercial');
        $highChart = array();
        foreach ($result as $index => $cu) {
            $highChart[] = array($cu['commercial'], (int) $cu['total']);
        }

        return array('commercialUnique' => json_encode($highChart));
    }

    /**
     * Generate quantitative report
     *
     * @Route("/audiobase", name="audiobase")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function audiobaseAction() {
        $em = $this->getDoctrine()->getManager();
        $shpinxInfo = $this->container->getParameter('sphinx_param');
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo);
        $criteria = array('s_media_type' => array('Audio'));
        $result = $sphinxSearch->removeEmpty($sphinxSearch->facetSelect('base', $this->getUser(), $criteria), 'base');
        $highChart = array();
        foreach ($result as $index => $base) {
            $highChart[] = array($base['base'], (int) $base['total']);
        }

        return array('audiobase' => json_encode($highChart));
    }

    /**
     * Generate quantitative report
     *
     * @Route("/filmbase", name="filmbase")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function filmbaseAction() {
        $em = $this->getDoctrine()->getManager();
        $shpinxInfo = $this->container->getParameter('sphinx_param');
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo);
        $criteria = array('s_media_type' => array('Film'));
        $result = $sphinxSearch->removeEmpty($sphinxSearch->facetSelect('base', $this->getUser(), $criteria), 'base');
        $highChart = array();
        foreach ($result as $index => $base) {
            $highChart[] = array($base['base'], (int) $base['total']);
        }

        return array('filmbase' => json_encode($highChart));
    }

    /**
     * Generate quantitative report
     *
     * @Route("/filmreeldiameter", name="filmreeldiameter")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function filmreeldiameterAction() {
        $em = $this->getDoctrine()->getManager();
        $shpinxInfo = $this->container->getParameter('sphinx_param');
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo);
        $criteria = array('s_media_type' => array('Film'));
        $result = $sphinxSearch->removeEmpty($sphinxSearch->facetSelect('reel_diameter', $this->getUser(), $criteria), 'reel_diameter');
        $highChart = array();
        foreach ($result as $index => $reelDiameter) {
            $highChart[] = array($reelDiameter['reel_diameter'], (int) $reelDiameter['total']);
        }

        return array('reelDiameter' => json_encode($highChart));
    }

    /**
     * Generate quantitative report
     *
     * @Route("/openreelaudio", name="openreelaudio")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function openreelaudioAction() {
        $em = $this->getDoctrine()->getManager();
        $shpinxInfo = $this->container->getParameter('sphinx_param');
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo);
        $criteria = array('s_format' => array('1/4 Inch Open Reel Audio', '1/2 Inch Open Reel Audio', '1/2 Inch Open Reel Audio - Digital', '1 Inch Open Reel Audio', '2 Inch Open Reel Audio'));
        $result = $sphinxSearch->removeEmpty($sphinxSearch->facetSelect('reel_diameter', $this->getUser(), $criteria), 'reel_diameter');

        $highChart = array();
        foreach ($result as $index => $reelDiameter) {
            $highChart[] = array($reelDiameter['reel_diameter'], (int) $reelDiameter['total']);
        }

        return array('reelDiameter' => json_encode($highChart));
    }

    /**
     * Generate quantitative report
     *
     * @Route("/reelcorefilm", name="reelcorefilm")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function reelcorefilmAction() {
        $em = $this->getDoctrine()->getManager();
        $shpinxInfo = $this->container->getParameter('sphinx_param');
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo);
        $criteria = array('s_media_type' => array('Film'));
        $result = $sphinxSearch->removeEmpty($sphinxSearch->facetSelect('reel_core', $this->getUser(), $criteria), 'reel_core');
        $highChart = array();
        foreach ($result as $index => $reelCore) {
            $highChart[] = array($reelCore['reel_core'], (int) $reelCore['total']);
        }

        return array('reelCore' => json_encode($highChart));
    }

    /**
     * Generate quantitative report
     *
     * @Route("/printtype", name="printtype")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function printtypeAction() {
        $em = $this->getDoctrine()->getManager();
        $shpinxInfo = $this->container->getParameter('sphinx_param');
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo);
        $criteria = array('s_media_type' => array('Film'));
        $result = $sphinxSearch->removeEmpty($sphinxSearch->facetSelect('print_type', $this->getUser(), $criteria), 'print_type');
        $highChart = array();
        foreach ($result as $index => $printType) {
            $highChart[] = array($printType['print_type'], (int) $printType['total']);
        }

        return array('printType' => json_encode($highChart));
    }

    /**
     * Generate quantitative report
     *
     * @Route("/filmcolor", name="filmcolor")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function filmcolorAction() {
        $em = $this->getDoctrine()->getManager();
        $shpinxInfo = $this->container->getParameter('sphinx_param');
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo);
        $criteria = array('s_media_type' => array('Film'));
        $result = $sphinxSearch->removeEmpty($sphinxSearch->facetSelect('color', $this->getUser(), $criteria), 'color');
        $highChart = array();
        foreach ($result as $index => $color) {
            $highChart[] = array($color['color'], (int) $color['total']);
        }

        return array('color' => json_encode($highChart));
    }

    /**
     * Generate quantitative report
     *
     * @Route("/filmsound", name="filmsound")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function filmsoundAction() {
        $em = $this->getDoctrine()->getManager();
        $shpinxInfo = $this->container->getParameter('sphinx_param');
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo);
        $criteria = array('s_media_type' => array('Film'));
        $result = $sphinxSearch->removeEmpty($sphinxSearch->facetSelect('sound', $this->getUser(), $criteria), 'sound');
        $highChart = array();
        foreach ($result as $index => $sound) {
            $highChart[] = array($sound['sound'], (int) $sound['total']);
        }

        return array('sound' => json_encode($highChart));
    }

    /**
     * Generate quantitative report
     *
     * @Route("/aciddetection", name="aciddetection")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function aciddetectionAction() {
        $em = $this->getDoctrine()->getManager();
        $shpinxInfo = $this->container->getParameter('sphinx_param');
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo);
        $criteria = array('s_media_type' => array('Film'));
        $result = $sphinxSearch->removeEmpty($sphinxSearch->facetSelect('acid_detection', $this->getUser(), $criteria), 'acid_detection');
        $highChart = array();
        foreach ($result as $index => $acid) {
            $highChart[] = array($acid['acid_detection'], (int) $acid['total']);
        }

        return array('acid' => json_encode($highChart));
    }

    /**
     * Generate quantitative report
     *
     * @Route("/diskdiameteraudio", name="diskdiameteraudio")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function diskdiameteraudioAction() {
        $em = $this->getDoctrine()->getManager();
        $shpinxInfo = $this->container->getParameter('sphinx_param');
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo);
        $criteria = array('s_format' => array('LP', '45', '78', 'Lacquer Disc', 'Transcription Disc'));
        $result = $sphinxSearch->removeEmpty($sphinxSearch->facetSelect('disk_diameter', $this->getUser(), $criteria), 'disk_diameter');

        $highChart = array();
        foreach ($result as $index => $diskDiameter) {
            $highChart[] = array($diskDiameter['disk_diameter'], (int) $diskDiameter['total']);
        }

        return array('diskDiameter' => json_encode($highChart));
    }

    /**
     * Generate file size calculator report
     *
     * @param string $type
     *
     * @Route("/filesizecalculator/{type}", name="filesizecalculator_report")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function fileSizeCalculatorAction($type) {
        if (!in_array($type, array('xlsx', 'csv'))) {
            throw $this->createNotFoundException('Invalid report type');
        }

        $formatInfo = $this->getFileSizeInfo();
        if (isset($formatInfo)) {
            $exportComponent = new ExportReport($this->container);
            $phpExcelObject = $exportComponent->generateFileSizeAssetsReport($formatInfo, $type);
            $response = $exportComponent->outputReport($type, $phpExcelObject, 'file_size_calculator', $formatInfo[1]);
            if ($type == "csv") {
                $filename = $response;
                $response = new Response();
                $response->headers->set('Cache-Control', 'private');
                $response->headers->set('Content-type', 'application/zip');
                $response->headers->set('Content-Disposition', 'attachment; filename="' . basename($filename) . '";');
                $response->sendHeaders();

                $response->setContent(file_get_contents($filename));
                return $response;
            } else {
                return $response;
            }
        } else {
            throw $this->createNotFoundException('No record found for report.');
        }
    }

    /**
     * Generate linear foot calculator report
     *
     * @param string $type
     *
     * @Route("/linearfootcalculator/{type}", name="linearfootcalculator_report")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function linearFootCalculatorAction($type) {
        if (!in_array($type, array('xlsx', 'csv'))) {
            throw $this->createNotFoundException('Invalid report type');
        }
        $typeFormats = $this->getLinearFeet();

        $exportComponent = new ExportReport($this->container);
        $phpExcelObject = $exportComponent->generateLinearFootReport($typeFormats);
        $response = $exportComponent->outputReport($type, $phpExcelObject, 'linear_foot_calculator');

        return $response;
    }

    /**
     * Generate formatcount report
     * 
     * @param string $projectid 
     * 
     * @Route("/getFormatCount/{projectid}", name="getFormatCount")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function getFormatCountAction($projectid) {
        $em = $this->getDoctrine()->getManager();
        $shpinxInfo = $this->container->getParameter('sphinx_param');
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo);
        if ($projectid == 'all') {
            $result = $sphinxSearch->removeEmpty($sphinxSearch->facetSelect('format', $this->getUser(), null, false, 'media_type', 'media_type'), 'format');
        } else {
            $projectCriteria = array('project_id' => (int) $projectid);
            $result = $sphinxSearch->removeEmpty($sphinxSearch->facetSelect('format', $this->getUser(), $projectCriteria, false, 'media_type', 'media_type'), 'format');
        }

        $highChart = array();
        foreach ($result as $index => $format) {
            $highChart[] = array(stripslashes($format['format']), (int) $format['total']);
        }

        echo json_encode($highChart);
        exit;
    }

    /**
     * Generate commercial/unique report
     * 
     * @param string $projectid 
     * 
     * @Route("/getCommercialUniqueCount/{projectid}", name="getCommercialUniqueCount")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function getCommercialUniqueCountAction($projectid) {
        $em = $this->getDoctrine()->getManager();
        $shpinxInfo = $this->container->getParameter('sphinx_param');
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo);
        if ($projectid == 'all') {
            $result = $sphinxSearch->removeEmpty($sphinxSearch->facetSelect('commercial', $this->getUser()), 'commercial');
        } else {
            $projectCriteria = array('project_id' => (int) $projectid);
            $result = $sphinxSearch->removeEmpty($sphinxSearch->facetSelect('commercial', $this->getUser(), $projectCriteria), 'commercial');
        }

        $highChart = array();
        foreach ($result as $index => $commercial) {
            $highChart[] = array(stripslashes($commercial['commercial']), (int) $commercial['total']);
        }

        echo json_encode($highChart);
        exit;
    }

    private function getLinearFeet() {
        $em = $this->getDoctrine()->getManager();
        $shpinxInfo = $this->container->getParameter('sphinx_param');
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo);
        $audioCriteria = array('s_media_type' => array('Audio'));
        $audioResult = $sphinxSearch->removeEmpty($sphinxSearch->facetWidthSelect('format', $this->getUser(), $audioCriteria), 'format');

        $videoCriteria = array('s_media_type' => array('Video'));
        $videoResult = $sphinxSearch->removeEmpty($sphinxSearch->facetWidthSelect('format', $this->getUser(), $videoCriteria), 'format');

        $filmCriteria = array('s_media_type' => array('Film'));
        $filmResult = $sphinxSearch->removeEmpty($sphinxSearch->facetWidthSelect('format', $this->getUser(), $filmCriteria), 'format');


        $typeFormats["audio"] = $audioResult;
        $typeFormats["video"] = $videoResult;
        $typeFormats["film"] = $filmResult;

        return $typeFormats;
    }

    /**
     * Generate quantitative report
     * 
     * @param string $projectid 
     * 
     * @Route("/getTotalRecords/{projectid}/{digitized}", name="getTotalRecords")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function getTotalRecordsAction($projectid, $digitized) {
        $em = $this->getDoctrine()->getManager();
        $record['digitized'] = $this->getFileSizeAndLinearFootageInfo($projectid, $digitized);

        $audioTotal = $audiodTotal = 0;
        $filmTotal = $filmdTotal = 0;
        $videoTotal = $videodTotal = 0;
        $total = array();
        $totalLinearAudioCount = $totalLinearVideoCount = $totalLinearFilmCount = 0.00;
        $totalAudioFileSize = $totalVideoFileSize = $totalFilmFileSize = 0.00;
        $sum_content_duration = $vsum_content_duration = $fsum_content_duration = 0;

        foreach ($record as $rkey => $precords) {
            if ($precords) {
                $audioTotal = $record['digitized'][0]["audio"];
                $videoTotal = $record['digitized'][0]["video"];
                $filmTotal = $record['digitized'][0]["film"];
                $audiodTotal = $record['digitized'][0]["ad"];
                $videodTotal = $record['digitized'][0]["vd"];
                $filmdTotal = $record['digitized'][0]["fd"];
                foreach ($precords as $pkey => $records) {
                    $acount = $vcount = $fcount = 0;
                    if ($pkey > 0) {
                        $project = $em->getRepository('ApplicationFrontBundle:Projects')->getFileSizeById($pkey);
                    }
                    if ($records) {
                        if (isset($records['Audio']) && $pkey != 0) {
                            foreach ($records['Audio'] as $key => $audio) {
                                if (($digitized == 1 && $audio['dtotal'] == 1) || ($digitized != 1 && $audio['dtotal'] != 1)) {
                                    $linearAudioCount = $this->calculateLinearFeet($audio['total'], $audio['width']);
                                    $totalLinearAudioCount += $linearAudioCount;
                                    $sum_content_duration += (!empty($audio['sum_content_duration'])) ? $audio['sum_content_duration'] : 0;
                                }
                                if (!empty($project["audioFilesize"]) && $project["audioFilesize"] != NULL && $acount == 0) {
                                    $totalAudioFileSize = $totalAudioFileSize + $this->getAudioFilesize($audio['sum_content_duration'], $digitized, $project["audioFilesize"]);
                                    $acount++;
                                } else if ($acount == 0 && ($digitized == 1 && $audio['dtotal'] == 1) || ($digitized != 1 && $audio['dtotal'] != 1)) {
                                    $totalAudioFileSize = $totalAudioFileSize + $this->getAudioFilesize($audio['sum_content_duration'], $digitized, $project["audioFilesize"]);
                                }
                            }
                        }
                        unset($key);
                        if (isset($records['Video']) && $pkey != 0) {
                            foreach ($records['Video'] as $key => $video) {
                                if (($digitized == 1 && $video['dtotal'] == 1) || ($digitized != 1 && $video['dtotal'] != 1)) {
                                    $linearVideoCount = $this->calculateLinearFeet($video['total'], $video['width']);
                                    $totalLinearVideoCount += $linearVideoCount;
                                    $vsum_content_duration += (!empty($video['sum_content_duration'])) ? $video['sum_content_duration'] : 0;
                                }
                                if (!empty($project["videoFilesize"]) && $project["videoFilesize"] != NULL && $vcount == 0) {
                                    $totalVideoFileSize = $totalVideoFileSize + $this->getVideoFilesize($video['sum_content_duration'], $digitized, $project["videoFilesize"]);
                                    $vcount++;
                                } else if ($vcount == 0 && ($digitized == 1 && $video['dtotal'] == 1) || ($digitized != 1 && $video['dtotal'] != 1)) {
                                    $totalVideoFileSize = $totalVideoFileSize + $this->getVideoFilesize($video['sum_content_duration'], $digitized, $project["videoFilesize"]);
                                }
                            }
                        }
                        unset($key);
                        if (isset($records['Film']) && $pkey != 0) {
                            foreach ($records['Film'] as $key => $film) {
                                if (($digitized == 1 && $film['dtotal'] == 1) || ($digitized != 1 && $film['dtotal'] != 1)) {
                                    $totalLinearFilmCount += $film['footage'];
                                    $fsum_content_duration += (!empty($film['sum_content_duration'])) ? $film['sum_content_duration'] : 0;
                                }
                                if (!empty($project["filmFilesize"]) && $project["filmFilesize"] != NULL && $fcount == 0) {
                                    $totalFilmFileSize = $totalFilmFileSize + $this->getFilmFilesize($film['sum_content_duration'], $digitized, $project["filmFilesize"]);
                                    $fcount++;
                                } else if ($fcount == 0 && ($digitized == 1 && $film['dtotal'] == 1) || ($digitized != 1 && $film['dtotal'] != 1)) {
                                    $totalFilmFileSize = $totalFilmFileSize + $this->getFilmFilesize($film['sum_content_duration'], $digitized, $project["filmFilesize"]);
                                }
                            }
                        }
                    }
                }
                $total[$rkey][] = array("Audio" => array("totalRecords" => $audioTotal, 'dRecords' => $audiodTotal, "linearFeet" => round($totalLinearAudioCount, 1), "fileSize" => round($totalAudioFileSize, 1), 'sum_content_duration' => round($sum_content_duration, 1)));
                $total[$rkey][] = array("Video" => array("totalRecords" => $videoTotal, 'dRecords' => $videodTotal, "linearFeet" => round($totalLinearVideoCount, 1), "fileSize" => round($totalVideoFileSize, 1), 'sum_content_duration' => round($vsum_content_duration, 1)));
                $total[$rkey][] = array("Film" => array("totalRecords" => $filmTotal, 'dRecords' => $filmdTotal, "linearFeet" => $totalLinearFilmCount, "fileSize" => round($totalFilmFileSize, 1), 'sum_content_duration' => round($fsum_content_duration, 1)));
            }
        }

        echo json_encode($total);
        exit;
    }

    /**
     * Formula for linear footage
     * 
     * @param integer $totalCount
     * @param integer $width
     * 
     * @return number
     */
    private function calculateLinearFeet($totalCount, $width) {
        return number_format(($totalCount * $width) / 12, 5);
    }

    /**
     * File size and linear footage info calculations
     * 
     * @param integer/string $projectId
     * 
     * @return array
     */
    private function getFileSizeAndLinearFootageInfo($projectid = null, $digitized = 0) {
        $em = $this->getDoctrine()->getManager();
        if ($projectid == 'all') {
            $projectid = 0;
        }
        if ($digitized == 2) {
            $digitized = 0;
        }
        if (in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles())) {
            $org_records = $em->getRepository('ApplicationFrontBundle:Records')->getDataForDashboard($projectid, $digitized);
        } else {
            $org_records = $em->getRepository('ApplicationFrontBundle:Records')->getDataForDashboard($projectid, $digitized, $this->getUser()->getOrganizations()->getId());
        }
        $formatInfo = array();
        $atotal = $vtotal = $ftotal = $adtotal = $vdtotal = $fdtotal = 0;
        foreach ($org_records as $data) {
            $pId = $data['projectId'];
            $mediatype = $data['media'];
            $f = str_replace(" ", "_", $data['format']);
            if ($mediatype == "Audio") {
                $atotal += $data["total"];
                $sum_content_duration = $data["audio_sum"];
            } elseif ($mediatype == "Video") {
                $vtotal += $data["total"];
                $sum_content_duration = $data["video_sum"];
            } else {
                $ftotal += $data["total"];
                $sum_content_duration = $data["film_sum"];
            }
            $_dTotal = 0;
            if ($data["digitized"] == 1) {
                $_dTotal = 1;
            }
            if ($digitized == 1 && $_dTotal == 1) {
                if ($mediatype == "Audio")
                    $adtotal += $data["total"];
                else if ($mediatype == "Video")
                    $vdtotal += $data["total"];
                else if ($mediatype == "Film")
                    $fdtotal += $data["total"];
            } else if ($digitized == 0 && $_dTotal == 0) {
                if ($mediatype == "Audio")
                    $adtotal += $data["total"];
                else if ($mediatype == "Video")
                    $vdtotal += $data["total"];
                else if ($mediatype == "Film")
                    $fdtotal += $data["total"];
            }
            $formatInfo[$pId][$mediatype][$f] = array('format' => $data['format'], 'sum_content_duration' => $sum_content_duration, 'total' => $data['total'], "dtotal" => $_dTotal, 'width' => $data['width'], 'footage' => $data['sum_footage']);
        }
        $formatInfo[0]["audio"] = $atotal;
        $formatInfo[0]["video"] = $vtotal;
        $formatInfo[0]["film"] = $ftotal;
        $formatInfo[0]["ad"] = $adtotal;
        $formatInfo[0]["vd"] = $vdtotal;
        $formatInfo[0]["fd"] = $fdtotal;
        return $formatInfo;
    }

    private function calculateFileSize($totalDuration, $value) {
        return number_format(($totalDuration * $value) / 1024 / 1024, 5);
    }

    private function getAudioFilesize($contentDuration, $type, $val = null) {
        $totalAudioFileSize = 0.00;
        if ($type == 1 && $val != null && !empty($val)) {
            return $val;
        } else if ($type != 1) {
            $uncompress1 = $this->calculateFileSize($contentDuration, 34.56);
            $totalAudioFileSize = $totalAudioFileSize + $uncompress1;

            $kbps = $this->calculateFileSize($contentDuration, 1.92);
            $totalAudioFileSize = $totalAudioFileSize + $kbps;
        }
        return $totalAudioFileSize;
    }

    private function getVideoFilesize($contentDuration, $type, $val = null) {
        $totalVideoFileSize = 0.00;
        if ($type == 1 && $val != null && !empty($val)) {
            return $val;
        } else if ($type != 1) {
            $FFV1 = $this->calculateFileSize($contentDuration, 600);
            $totalVideoFileSize = $totalVideoFileSize + $FFV1;


            $MPEG45 = $this->calculateFileSize($contentDuration, 36);
            $totalVideoFileSize = $totalVideoFileSize + $MPEG45;
        }
        return $totalVideoFileSize;
    }

    private function getFilmFilesize($contentDuration, $type, $val = null) {
        $totalFilmFileSize = 0.00;
        if ($type == 1 && $val != null && !empty($val)) {
            return $val;
        } else if ($type != 1) {
            $k2Uncompressed = $this->calculateFileSize($contentDuration, 17500);
            $totalFilmFileSize = $totalFilmFileSize + $k2Uncompressed;


            $AVCIntra100 = $this->calculateFileSize($contentDuration, 943);
            $totalFilmFileSize = $totalFilmFileSize + $AVCIntra100;

            $MPEG45 = $this->calculateFileSize($contentDuration, 36);
            $totalFilmFileSize = $totalFilmFileSize + $MPEG45;
        }
        return $totalFilmFileSize;
    }

    private function getFileSizeInfo() {
        $formatInfo = null;
        $em = $this->getDoctrine()->getManager();
        $shpinxInfo = $this->container->getParameter('sphinx_param');
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo);
        $total_records = $sphinxSearch->select($this->getUser(), 0, 1000, 'title', 'asc');
        $max_offset = $total_records[1][1]['Value'];
        $projects = $sphinxSearch->removeEmpty($sphinxSearch->facetSelect('project', $this->getUser(), null, false, null, null, "project_id"), 'project');

        $types = $sphinxSearch->removeEmpty($sphinxSearch->facetSelect('media_type', $this->getUser()), 'media_type');

        foreach ($types as $mediatype) {

            $typeCriteria = array('s_media_type' => array($mediatype['media_type']));

            $formatResult = $sphinxSearch->removeEmpty($sphinxSearch->facetDurationSumSelect('format', $this->getUser(), $typeCriteria), 'format');
            if ($formatResult) {
                foreach ($formatResult as $format) {
                    $_records = array();

                    $recordCriteria = array('format' => $format['format']);
                    $count = 0;
                    $offset = 0;

                    while ($count == 0) {
                        $records = $sphinxSearch->select($this->getUser(), $offset, 1000, 'title', 'asc', $recordCriteria);
                        $_records = array_merge($_records, $records[0]);
                        $totalFound = $records[1][1]['Value'];
                        $offset = $offset + 1000;
                        if ($totalFound < 1000 || $offset >= $max_offset) {
                            $count++;
                        }
                    }

                    if ($_records) {
                        $sumDuration = 0;
                        $f = str_replace(" ", "_", $format['format']);
                        foreach ($_records as $rec) {
                            if ($rec['format'] == $format['format']) {
                                if ($rec['content_duration']) {
                                    $sumDuration = $sumDuration + $rec['content_duration'];
                                } elseif ($mediatype['media_type'] != 'Film') {
                                    $sumDuration = $sumDuration + $rec['media_duration'];
                                }
                            }
                        }
                        $formatInfo[$mediatype['media_type']][$f] = array('format' => $format['format'], 'sum_content_duration' => $sumDuration, 'total' => $format['total'], 'width' => $format['width']);
                    }
                }
            }
        }
        $output = array();
        $output[] = $formatInfo;
        $output[] = $projects;
        return $output;
    }

}
