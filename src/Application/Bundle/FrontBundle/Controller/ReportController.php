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
    public function reelcorefilmAction()
    {
        $em = $this->getDoctrine()->getManager();
        $shpinxInfo = $this->container->getParameter('sphinx_param');
        $sphinxSearch = new SphinxSearch($em, $shpinxInfo);
        $criteria = array('s_media_type' => array('Film'));
        $result = $sphinxSearch->removeEmpty($sphinxSearch->facetSelect('reel_core', $this->getUser(), $criteria), 'reel_core');
        $highChart = array();
        foreach ($result as $index => $reelCore) {
            $highChart[] = array($reelCore['reel_core'], (int) $reelDiameter['total']);
        }

        return array('reelCore' => json_encode($highChart));
    }
}
