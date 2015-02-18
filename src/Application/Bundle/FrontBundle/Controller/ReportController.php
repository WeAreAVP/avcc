<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Components\ExportReport;
use Application\Bundle\FrontBundle\SphinxSearch\SphinxSearch;

/**
 * ReelDiameters controller.
 *
 * @Route("/report")
 */
class ReportController extends Controller
{

    /**
     * Show Reports view.
     *
     * @Route("/", name="report")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function indexAction()
    {
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
    public function allFormatsAction($type)
    {
        if (!in_array($type, array('csv', 'xlsx'))) {
            throw $this->createNotFoundException('Invalid report type');
        }

        $entityManager = $this->getDoctrine()->getManager();
        if (true === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
            $records = $entityManager->getRepository('ApplicationFrontBundle:Records')->findAll();
        else
            $records = $entityManager->getRepository('ApplicationFrontBundle:Records')->findOrganizationRecords($this->getUser()->getOrganizations()->getId());

        $exportComponent = new ExportReport($this->container);
        $phpExcelObject = $exportComponent->generateReport($records);
        $response = $exportComponent->outputReport($type, $phpExcelObject);

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
    public function quantitativeAction()
    {
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
    public function manifestAction($type)
    {
        if (!in_array($type, array('csv', 'xlsx'))) {
            throw $this->createNotFoundException('Invalid report type');
        }
        $entityManager = $this->getDoctrine()->getManager();

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
    public function prioritizationReportAction($type)
    {
        if (!in_array($type, array('csv', 'xlsx'))) {
            throw $this->createNotFoundException('Invalid report type');
        }

        $entityManager = $this->getDoctrine()->getManager();
        if (true === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
            $records = $entityManager->getRepository('ApplicationFrontBundle:Records')->findAll();
        else
            $records = $entityManager->getRepository('ApplicationFrontBundle:Records')->findOrganizationRecords($this->getUser()->getOrganizations()->getId());

        $exportComponent = new ExportReport($this->container);
        $phpExcelObject = $exportComponent->generatePrioritizationReport($records);
        $response = $exportComponent->outputReport($type, $phpExcelObject, 'prioritization_report');

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
    public function commercialuniqueAction()
    {
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
    public function audiobaseAction()
    {
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
    public function filmbaseAction()
    {
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
    public function filmreeldiameterAction()
    {
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
    public function openreelaudioAction()
    {
        $em = $this->getDoctrine()->getManager();
        $shpinxInfo = $this->container->getParameter('sphinx_param');
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo);
        $criteria = array('s_format' => array('"1/4 Inch Open Reel Audio"', '"1/2 Inch Open Reel Audio"', '"1/2 Inch Open Reel Audio - Digital"', '"1 Inch Open Reel Audio"', '"2 Inch Open Reel Audio"'));
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
    public function reelcorefilmAction()
    {
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
    public function printtypeAction()
    {
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
    public function filmcolorAction()
    {
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
    public function filmsoundAction()
    {
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
    public function aciddetectionAction()
    {
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
    public function diskdiameteraudioAction()
    {
        $em = $this->getDoctrine()->getManager();
        $shpinxInfo = $this->container->getParameter('sphinx_param');
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo);
        $criteria = array('s_format' => array('"LP"', '"45"', '"78"', '"Lacquer Disc"', '"Transcription Disc"'));
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
    public function fileSizeCalculatorAction($type)
    {
        if (!in_array($type, array('xlsx', 'csv'))) {
            throw $this->createNotFoundException('Invalid report type');
        }

        $formatInfo = $this->getFileSizeAndLinearFootageInfo();
        if (isset($formatInfo)) {
            $exportComponent = new ExportReport($this->container);
            $phpExcelObject = $exportComponent->generateFileSizeAssetsReport($formatInfo);
            $response = $exportComponent->outputReport($type, $phpExcelObject, 'file_size_calculator');

            return $response;
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
    public function linearFootCalculatorAction($type)
    {
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
    public function getFormatCountAction($projectid)
    {
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
    public function getCommercialUniqueCountAction($projectid)
    {
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

    private function getLinearFeet()
    {
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
     * @Route("/getTotalRecords/{projectid}", name="getTotalRecords")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function getTotalRecordsAction($projectid)
    {
        $records = $this->getFileSizeAndLinearFootageInfo($projectid);

        $totalLinearAudioCount = 0.00;
        $totalLinearVideoCount = 0.00;
        $total = array();
        if ($records) {
            if (isset($records['Audio'])) {
                $audioTotal = 0;
                $totalAudioFileSize = 0.00;
                foreach ($records['Audio'] as $audio) {
                    $linearAudioCount = $this->calculateLinearFeet($audio['total'], $audio['width']);
                    $totalLinearAudioCount += $linearAudioCount;
                    $audioTotal += $audio['total'];
                    ///// File size calculations
                    $uncompress1 = $this->calculateFileSize($audio['sum_content_duration'], 34.56);
                    $totalAudioFileSize = $totalAudioFileSize + $uncompress1;

//                    $uncompress2 = $this->calculateFileSize($audio['sum_content_duration'], 17.28);
//                    $totalAudioFileSize = $totalAudioFileSize + $uncompress2;
//
//                    $uncompress3 = $this->calculateFileSize($audio['sum_content_duration'], 11.52);
//                    $totalAudioFileSize = $totalAudioFileSize + $uncompress3;
//
//                    $uncompress4 = $this->calculateFileSize($audio['sum_content_duration'], 10.584);
//                    $totalAudioFileSize = $totalAudioFileSize + $uncompress4;
//
//                    $uncompress5 = $this->calculateFileSize($audio['sum_content_duration'], 17.28);
//                    $totalAudioFileSize = $totalAudioFileSize + $uncompress5;
//
//                    $uncompress6 = $this->calculateFileSize($audio['sum_content_duration'], 8.64);
//                    $totalAudioFileSize = $totalAudioFileSize + $uncompress6;
//
//                    $uncompress7 = $this->calculateFileSize($audio['sum_content_duration'], 5.75);
//                    $totalAudioFileSize = $totalAudioFileSize + $uncompress7;
//
//                    $uncompress8 = $this->calculateFileSize($audio['sum_content_duration'], 5.292);
//                    $totalAudioFileSize = $totalAudioFileSize + $uncompress8;

                    $kbps = $this->calculateFileSize($audio['sum_content_duration'], 1.92);
                    $totalAudioFileSize = $totalAudioFileSize + $kbps;
                }
                $total[] = array("Audio" => array("totalRecords" => $audioTotal, "linearFeet" => round($totalLinearAudioCount, 1), "fileSize" => round($totalAudioFileSize, 1)));
            }
            if (isset($records['Video'])) {
                $videoTotal = 0;
                $totalVideoFileSize = 0.00;
                foreach ($records['Video'] as $video) {
                    $linearVideoCount = $this->calculateLinearFeet($video['total'], $video['width']);
                    $totalLinearVideoCount += $linearVideoCount;
                    $videoTotal += $video['total'];
                    ///// File size calculations
//                    $VUncompress1 = $this->calculateFileSize($video['sum_content_duration'], 10240);
//                    $totalVideoFileSize = $totalVideoFileSize + $VUncompress1;
//
//                    $VUncompress2 = $this->calculateFileSize($video['sum_content_duration'], 1800);
//                    $totalVideoFileSize = $totalVideoFileSize + $VUncompress2;
//
//                    $Lossless = $this->calculateFileSize($video['sum_content_duration'], 900);
//                    $totalVideoFileSize = $totalVideoFileSize + $Lossless;

                    $FFV1 = $this->calculateFileSize($video['sum_content_duration'], 600);
                    $totalVideoFileSize = $totalVideoFileSize + $FFV1;

//                    $MPEG2 = $this->calculateFileSize($video['sum_content_duration'], 427);
//                    $totalVideoFileSize = $totalVideoFileSize + $MPEG2;
//
//                    $ProRes = $this->calculateFileSize($video['sum_content_duration'], 306);
//                    $totalVideoFileSize = $totalVideoFileSize + $ProRes;
//
//                    $DV25 = $this->calculateFileSize($video['sum_content_duration'], 240);
//                    $totalVideoFileSize = $totalVideoFileSize + $DV25;

                    $MPEG45 = $this->calculateFileSize($video['sum_content_duration'], 36);
                    $totalVideoFileSize = $totalVideoFileSize + $MPEG45;

//                    $MPEG42 = $this->calculateFileSize($video['sum_content_duration'], 17.1);
//                    $totalVideoFileSize = $totalVideoFileSize + $MPEG42;
                }
                $total[] = array("Video" => array("totalRecords" => $videoTotal, "linearFeet" => round($totalLinearVideoCount, 1), "fileSize" => round($totalVideoFileSize, 1)));
            }
            if (isset($records['Film'])) {
                $filmTotal = 0;
                $totalFilmFileSize = 0.00;
                foreach ($records['Film'] as $film) {
//                    $linearVideoCount = $this->calculateLinearFeet($film['total'], $film['width']);
//                    $totalLinearVideoCount += $linearVideoCount;
                    $filmTotal += $film['total'];

                    ////// File size calculations
//                    $k4Uncompressed = $this->calculateFileSize($film['sum_content_duration'], 69905);
//                    $totalFilmFileSize = $totalFilmFileSize + $k4Uncompressed;
//
//                    $k4Lossless = $this->calculateFileSize($film['sum_content_duration'], 34952.5);
//                    $totalFilmFileSize = $totalFilmFileSize + $k4Lossless;

                    $k2Uncompressed = $this->calculateFileSize($film['sum_content_duration'], 17500);
                    $totalFilmFileSize = $totalFilmFileSize + $k2Uncompressed;

//                    $k2Lossless = $this->calculateFileSize($film['sum_content_duration'], 8750);
//                    $totalFilmFileSize = $totalFilmFileSize + $k2Lossless;

                    $AVCIntra100 = $this->calculateFileSize($film['sum_content_duration'], 943);
                    $totalFilmFileSize = $totalFilmFileSize + $AVCIntra100;

                    $MPEG45 = $this->calculateFileSize($film['sum_content_duration'], 36);
                    $totalFilmFileSize = $totalFilmFileSize + $MPEG45;

//                    $MPEG42 = $this->calculateFileSize($film['sum_content_duration'], 17.1);
//                    $totalFilmFileSize = $totalFilmFileSize + $MPEG42;
                }
                $total[] = array("Film" => array("totalRecords" => $filmTotal, "linearFeet" => "", "fileSize" => round($totalFilmFileSize, 1)));
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
    private function calculateLinearFeet($totalCount, $width)
    {
        return number_format(($totalCount * $width) / 12, 5);
    }

    /**
     * File size and linear footage info calculations
     * 
     * @param integer/string $projectId
     * 
     * @return array
     */
    private function getFileSizeAndLinearFootageInfo($projectid = null)
    {
        $formatInfo = null;
        $em = $this->getDoctrine()->getManager();
        $shpinxInfo = $this->container->getParameter('sphinx_param');
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo);
        $types = $sphinxSearch->removeEmpty($sphinxSearch->facetSelect('media_type', $this->getUser()), 'media_type');
        foreach ($types as $mediatype) {
            $typeCriteria = array('s_media_type' => array($mediatype['media_type']));
            if ($projectid && $projectid != 'all') {
                $typeCriteria['project_id'] = (int) $projectid;
            }
            $formatResult = $sphinxSearch->removeEmpty($sphinxSearch->facetDurationSumSelect('format', $this->getUser(), $typeCriteria), 'format');
            $_records = array();
            if ($formatResult) {
                foreach ($formatResult as $format) {
                    $recordCriteria = array('s_format' => array($format['format']));
                    $count = 0;
                    $offset = 0;
                    while ($count == 0) {
                        $records = $sphinxSearch->select($this->getUser(), $offset, 1000, 'title', 'asc', $recordCriteria);
                        $_records = array_merge($_records, $records[0]);
                        $totalFound = $records[1][1]['Value'];
                        $offset = $offset + 1000;
                        if ($totalFound < 1000) {
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
        return $formatInfo;
    }

    private function calculateFileSize($totalDuration, $value)
    {
        return number_format(($totalDuration * $value) / 1024 / 1024, 5);
    }

}
