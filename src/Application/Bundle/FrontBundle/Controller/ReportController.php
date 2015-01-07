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
        $result = $sphinxSearch->removeEmpty($sphinxSearch->facetSelect('format', $this->getUser()), 'format');
        $highChart = array();
        foreach ($result as $index => $format) {
            $highChart[] = array($format['format'], (int) $format['total']);
        }

        return array('formats' => json_encode($highChart));
    }

    /**
     * Generate Manifest for shipping to vendor and Quote from Vendor report.
     *
     * @Route("/manifest", name="manifest")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function manifestAction()
    {
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
        $response = $exportComponent->outputReport('xlsx', $phpExcelObject, 'manifest_report');

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
     * @Route("/filesizeaudio", name="filesizeaudio")
     * @Method("GET")
     * @Template("ApplicationFrontBundle:Report:fileSizeAudio.html.php")
     * @return array
     */
    public function fileSizeAudioAction()
    {
        $em = $this->getDoctrine()->getManager();
        $shpinxInfo = $this->container->getParameter('sphinx_param');
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo);
        $types = array('Audio', 'Video', 'Film');
        foreach ($types as $type) {
            $typeCriteria = array('s_media_type' => array($type));
            $formatResult = $sphinxSearch->removeEmpty($sphinxSearch->facetDurationSumSelect('format', $this->getUser(), $typeCriteria), 'format');
            $_records = array();
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
                            } else {
                                $sumDuration = $sumDuration + $rec['media_duration'];
                            }
                        }
                    }
                    $formatInfo[$type][$f] = array('sum_duration' => $sumDuration, 'total' => $format['total']);
                }
            }
        }
        echo '<pre>';
        print_r($formatInfo);
        die;
        return array('audioResult' => $audioResult);
    }

    /**
     * Generate file size calculator report
     *
     * @Route("/filesizevideo", name="filesizevideo")
     * @Method("GET")
     * @Template("ApplicationFrontBundle:Report:fileSizeVideo.html.php")
     * @return array
     */
    public function fileSizeVideoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $shpinxInfo = $this->container->getParameter('sphinx_param');
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo);

        $videoCriteria = array('s_media_type' => array('Video'));
        $videoResult = $sphinxSearch->removeEmpty($sphinxSearch->facetDurationSumSelect('format', $this->getUser(), $videoCriteria), 'format');

        return array('videoResult' => $videoResult);
    }

    /**
     * Generate file size calculator report
     * 
     * @param  string $type
     * 
     * @Route("/filesizecalculator/{type}", name="filesizecalculator_report")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function fileSizeCalculatorAction($type)
    {
        if (!in_array($type, array('xlsx'))) {
            throw $this->createNotFoundException('Invalid report type');
        }

        $em = $this->getDoctrine()->getManager();
        $shpinxInfo = $this->container->getParameter('sphinx_param');
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo);

        $audioCriteria = array('s_media_type' => array('Audio'));
        $audioResult = $sphinxSearch->removeEmpty($sphinxSearch->facetDurationSumSelect('format', $this->getUser(), $audioCriteria), 'format');

        $videoCriteria = array('s_media_type' => array('Video'));
        $videoResult = $sphinxSearch->removeEmpty($sphinxSearch->facetDurationSumSelect('format', $this->getUser(), $videoCriteria), 'format');

        $filmCriteria = array('s_media_type' => array('Film'));
        $filmResult = $sphinxSearch->removeEmpty($sphinxSearch->facetDurationSumSelect('format', $this->getUser(), $filmCriteria), 'format');

        $typeFormats["audio"] = $audioResult;
        $typeFormats["video"] = $videoResult;
        $typeFormats["film"] = $filmResult;

        $exportComponent = new ExportReport($this->container);
        $phpExcelObject = $exportComponent->generateFileSizeAssetsReport($typeFormats);
        $response = $exportComponent->outputReport($type, $phpExcelObject, 'file_size_calculator');

        return $response;
    }

    /**
     * Generate linear foot calculator report
     * 
     * @param  string $type
     * 
     * @Route("/linearfootcalculator/{type}", name="linearfootcalculator_report")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function linearFootCalculatorAction($type)
    {
        if (!in_array($type, array('xlsx'))) {
            throw $this->createNotFoundException('Invalid report type');
        }
        $em = $this->getDoctrine()->getManager();
        $shpinxInfo = $this->container->getParameter('sphinx_param');
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo);

        $audioCriteria = array('s_media_type' => array('Audio'));
        $audioResult = $sphinxSearch->removeEmpty($sphinxSearch->facetDurationSumSelect('format', $this->getUser(), $audioCriteria), 'format');

        $videoCriteria = array('s_media_type' => array('Video'));
        $videoResult = $sphinxSearch->removeEmpty($sphinxSearch->facetDurationSumSelect('format', $this->getUser(), $videoCriteria), 'format');

        $typeFormats["audio"] = $audioResult;
        $typeFormats["video"] = $videoResult;

        $exportComponent = new ExportReport($this->container);
        $phpExcelObject = $exportComponent->generateLinearFootReport($typeFormats);
        $response = $exportComponent->outputReport($type, $phpExcelObject, 'linear_foot_calculator');

        return $response;
    }

}
