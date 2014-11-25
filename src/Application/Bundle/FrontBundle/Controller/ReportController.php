<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Components\ExportReport;
use Application\Bundle\FrontBundle\SphinxSearch\SphinxSearch;
use Application\Bundle\FrontBundle\Helper\ExportFields;

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
	 * @param string $type
	 * @Route("/allformats/{type}", name="all_formats")
	 * @Method("GET")
	 * @Template()
	 * @return array
	 */
	public function allFormatsAction($type)
	{
		if ( ! in_array($type, array('csv', 'xlsx')))
		{
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
		foreach ($result as $index => $format)
		{
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
		->setTitle("AVCC - Report")
		->setSubject("Report for all formats")
		->setDescription("Report for all formats");
		$activeSheet = $phpExcelObject->setActiveSheetIndex(0);
		$phpExcelObject->getActiveSheet()->setTitle('All Formats');
		$row = 1;
		$columns = new ExportFields();
		$this->columns = $columns->getManifestColumns();
		foreach ($this->columns as $column => $columnName)
		{
			$activeSheet->setCellValueExplicitByColumnAndRow($column, $row, $columnName);
			$activeSheet->getColumnDimensionByColumn($column)->setWidth(20);
			$activeSheet->getStyleByColumnAndRow($column)->getAlignment()->setWrapText(true);
			$activeSheet->getStyleByColumnAndRow($column, $row)->getFont()->setBold(true);
		}
		$activeSheet->getRowDimension($row)->setRowHeight(50);
		$row ++;

		foreach ($records as $record)
		{
			$activeSheet->setCellValueExplicitByColumnAndRow(0, $row, $record->getUniqueId());
			$activeSheet->setCellValueExplicitByColumnAndRow(1, $row, $record->getUser()->getOrganizations()->getName());
			$activeSheet->setCellValueExplicitByColumnAndRow(2, $row, $record->getCollectionName());
			$activeSheet->setCellValueExplicitByColumnAndRow(3, $row, ($record->getFormat()->getName()) ? $record->getFormat()->getName() : '');
			$printType = '';
			if ($record->getFilmRecord())
			{
				$printType = ($record->getFilmRecord()->getPrintType()) ? $record->getFilmRecord()->getPrintType()->getName() : '';
			}
			$activeSheet->setCellValueExplicitByColumnAndRow(4, $row, $printType);

			$mediaType = ($record->getReelDiameters()) ? $record->getReelDiameters()->getName() . "\n" : '';
			if ($record->getAudioRecord())
			{
				$mediaType .=($record->getAudioRecord()->getDiskDiameters()) ? $record->getAudioRecord()->getDiskDiameters()->getName() . "\n" : '';
			}
			if ($record->getVideoRecord())
			{
				$mediaType .=($record->getVideoRecord()->getCassetteSize()) ? $record->getVideoRecord()->getCassetteSize()->getName() . "\n" : '';
			}
			$activeSheet->setCellValueExplicitByColumnAndRow(5, $row, $mediaType);
			$activeSheet->getStyleByColumnAndRow(5, $row)->getAlignment()->setWrapText(true);
			$activeSheet->setCellValueExplicitByColumnAndRow(6, $row, $record->getTitle());
			$duration = $record->getContentDuration();
			if (empty($duration) || $duration < 0)
			{
				if ($record->getAudioRecord())
				{
					$duration = ($record->getAudioRecord()->getMediaDuration()) ? $record->getAudioRecord()->getMediaDuration() : '';
				}
			}
			$activeSheet->setCellValueExplicitByColumnAndRow(7, $row, $duration);
			$row ++;
		}

		$writer = $this->container->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
		$filename = 'manifest_report_' . time() . '.xlsx';
		$response = $this->container->get('phpexcel')->createStreamedResponse($writer);
		$response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
		$response->headers->set('Content-Disposition', "attachment;filename={$filename}");
		$response->headers->set('Pragma', 'public');
		$response->headers->set('Cache-Control', 'maxage=1');

		return $response;
		return array();
	}

}
