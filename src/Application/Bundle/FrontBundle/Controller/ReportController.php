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
class ReportController extends Controller {

    /**
     * Show Reports view.
     *
     * @Route("/", name="report")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function indexAction() {
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
    public function quantitativeAction() {
        $em = $this->getDoctrine()->getManager();
        $shpinxInfo = $this->container->getParameter('sphinx_param');
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo);
        $result = $sphinxSearch->removeEmpty($sphinxSearch->facetSelect('format'), 'format');
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
    public function manifestAction() {
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
    public function prioritizationReportAction($type) {
        if (!in_array($type, array('csv', 'xlsx'))) {
            throw $this->createNotFoundException('Invalid report type');
        }

        $entityManager = $this->getDoctrine()->getManager();
        if (true === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
            $records = $entityManager->getRepository('ApplicationFrontBundle:Records')->findAll();
        else
            $records = $entityManager->getRepository('ApplicationFrontBundle:Records')->findOrganizationRecords($this->getUser()->getOrganizations()->getId());
        ///////////////////
        $score = 0;
        foreach ($records as $record) {
            $score = $score + $record->getMediaType()->getScore();
            $score = $score + ($record->getFormat()->getScore()) ? $record->getFormat()->getScore() : 0;
            $score = $score + ($record->getCommercial()) ? $record->getCommercial()->getScore() : 0;
            $score = $score + ($record->getReelDiameters()) ? $record->getReelDiameters()->getScore() : 0;

            if ($record->getAudioRecord()) {
            //    $score = $score + ($record->getAudioRecord()->getMediaDuration()) ? $record->getAudioRecord()->getMediaDuration()->getscore() : 0;
                $score = $score + ($record->getAudioRecord()->getBases()) ? $record->getAudioRecord()->getBases()->getscore() : 0;
                $score = $score + ($record->getAudioRecord()->getDiskDiameters()) ? $record->getAudioRecord()->getDiskDiameters()->getscore() : 0;
                $score = $score + ($record->getAudioRecord()->getMediaDiameters()) ? $record->getAudioRecord()->getMediaDiameters()->getscore() : 0;
                $score = $score + ($record->getAudioRecord()->getTapeThickness()) ? $record->getAudioRecord()->getTapeThickness()->getscore() : 0;
                $score = $score + ($record->getAudioRecord()->getSlides()) ? $record->getAudioRecord()->getSlides()->getscore() : 0;
                $score = $score + ($record->getAudioRecord()->getTrackTypes()) ? $record->getAudioRecord()->getTrackTypes()->getscore() : 0;
                $score = $score + ($record->getAudioRecord()->getMonoStereo()) ? $record->getAudioRecord()->getMonoStereo()->getscore() : 0;
                $score = $score + ($record->getAudioRecord()->getNoiceReduction()) ? $record->getAudioRecord()->getNoiceReduction()->getscore() : 0;
            }
            if ($record->getFilmRecord()) {
                $score = $score + ($record->getFilmRecord()->getPrintType()) ? $record->getFilmRecord()->getPrintType()->getscore() : 0;
            //    $score = $score + ($record->getFilmRecord()->getFootage()) ? $record->getFilmRecord()->getscore() : 0;
                $score = $score + ($record->getFilmRecord()->getColors()) ? $record->getFilmRecord()->getColors()->getscore() : 0;
                $score = $score + ($record->getFilmRecord()->getReelCore()) ? $record->getFilmRecord()->getReelCore()->getscore() : 0;
                $score = $score + ($record->getFilmRecord()->getSound()) ? $record->getFilmRecord()->getSound()->getscore() : 0;
                $score = $score + ($record->getFilmRecord()->getFrameRate()) ? $record->getFilmRecord()->getFrameRate()->getscore() : 0;
                $score = $score + ($record->getFilmRecord()->getAcidDetectionStrip()) ? $record->getFilmRecord()->getAcidDetectionStrip()->getscore() : 0;
            //    $score = $score + ($record->getFilmRecord()->getShrinkage()) ? $record->getFilmRecord()->getscore() : 0;
            }
            if ($record->getVideoRecord()) {

                $score = $score + ($record->getVideoRecord()->getRecordingSpeed()) ? $record->getVideoRecord()->getRecordingSpeed()->getscore() : 0;
                $score = $score + ($record->getVideoRecord()->getCassetteSize()) ? $record->getVideoRecord()->getCassetteSize()->getscore() : 0;
                $score = $score + ($record->getVideoRecord()->getFormatVersion()) ? $record->getVideoRecord()->getFormatVersion()->getscore() : 0;
                $score = $score + ($record->getVideoRecord()->getRecordingStandard()) ? $record->getVideoRecord()->getRecordingStandard()->getscore() : 0;
            }
            echo $score;
       exit;
        }
       
        //////////////////
        $exportComponent = new ExportReport($this->container);
        $phpExcelObject = $exportComponent->generatePrioritizationReport($records);
        $response = $exportComponent->outputReport($type, $phpExcelObject);

        // create the response
        return $response;
        return array();
    }

}
