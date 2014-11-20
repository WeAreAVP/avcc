<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * ReelDiameters controller.
 *
 * @Route("/report")
 */
class ReportController extends Controller
{

	private $columns = array(
		'Project_Name',
		'Collection_Name',
		'Media_Type',
		'Unique_ID',
		'Location',
		'Format',
		'Title',
		'Description',
		'Commercial_or_Unique',
		'Content_Duration',
		'Media_Duration',
		'Creation_Date',
		'Content_Date',
		'Base',
		'Print_Type',
		'Disk_Diameter',
		'Reel_Diameter',
		'Media_Diameter',
		'Footage',
		'Recording_Speed',
		'Color',
		'Tape_Thickness',
		'Sides',
		'Track_Type',
		'Mono_or_Stereo',
		'Noise_Reduction',
		'Cassette_Size',
		'Format_Version',
		'Recording_Standard',
		'Reel_or_Core',
		'Sound',
		'Frame_Rate',
		'Acid_Detection_Strip',
		'Shrinkage',
		'Genre_Terms',
		'Contributor',
		'Generation',
		'Part',
		'Copyright_/_Restrictions',
		'Duplicates_/_Derivatives',
		'Related_Material',
		'Condition_Note',
		'Time_Stamp',
		'Timestamp_-_Last_Change',
		'Cataloger'
	);

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
	 * Show Reports view.
	 *
	 * @Route("/allformatscsv", name="all_formats_csv")
	 * @Method("GET")
	 * @Template()
	 * @return array
	 */
	public function allFormatsCsvAction()
	{
		$container = $this->container;
		$response = new StreamedResponse(function() use($container)
		{
			$em = $container->get('doctrine')->getManager();

			// The getExportQuery method returns a query that is used to retrieve
			// all the objects (lines of your csv file) you need. The iterate method
			// is used to limit the memory consumption
//			$results = $em->getRepository('ObtaoAcmeBundle:Jedi')->getExportQuery()->iterate();
			$handle = fopen('php://output', 'r+');
			fputcsv($handle, $this->columns);
//			while (false !== ($row = $results->next()))
//			{
//				// add a line in the csv file. You need to implement a toArray() method
//				// to transform your object into an array
//				fputcsv($handle, $row[0]->toArray());
//				// used to limit the memory consumption
//				$em->detach($row[0]);
//			}

			fclose($handle);
		});

		$response->headers->set('Content-Type', 'application/force-download');
		$response->headers->set('Content-Disposition', 'attachment; filename="export.csv"');

		return $response;
	}

	/**
	 * Show Reports view.
	 *
	 * @Route("/allformatsxlsx", name="all_formats_xlsx")
	 * @Method("GET")
	 * @Template()
	 * @return array
	 */
	public function allFormatsXlsxAction()
	{
		$phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
		$phpExcelObject->getProperties()->setCreator("AVCC - AVPreserve")
		->setTitle("AVCC - Report")
		->setSubject("Report for all formats")
		->setDescription("Report for all formats");
		$activeSheet = $phpExcelObject->setActiveSheetIndex(0);
		$phpExcelObject->getActiveSheet()->setTitle('All Formats');
		$row = 1;
		// Prepare header row for report
		$this->prepareHeader($activeSheet, $row);
		$row ++;
		$this->prepareRecords($activeSheet, $row);



		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$phpExcelObject->setActiveSheetIndex(0);

		$writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
		// create the response
		$filename = 'allFormat_' . time() . '.xlsx';
		$response = $this->get('phpexcel')->createStreamedResponse($writer);
		$response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
		$response->headers->set('Content-Disposition', "attachment;filename={$filename}");
		$response->headers->set('Pragma', 'public');
		$response->headers->set('Cache-Control', 'maxage=1');

		return $response;
		return array();
	}

	/**
	 * Create the Header for report.
	 * 
	 * @param PHPExcel_Worksheet $activeSheet
	 * @param Integer $row
	 * @return boolean
	 */
	private function prepareHeader($activeSheet, $row)
	{

		foreach ($this->columns as $column => $columnName)
		{
			$activeSheet->setCellValueExplicitByColumnAndRow($column, $row, str_replace('_', ' ', $columnName));
			$activeSheet->getColumnDimensionByColumn($column)->setWidth(20);
			$activeSheet->getStyleByColumnAndRow($column)->getFont()->setBold(true);
		}
		return TRUE;
	}

	/**
	 * Prepare rows for records.
	 * 
	 * @param PHPExcel_Worksheet $activeSheet
	 * @param Integer $row
	 * @return boolean 
	 */
	private function prepareRecords($activeSheet, $row)
	{
		$entityManager = $this->getDoctrine()->getManager();
		$records = $entityManager->getRepository('ApplicationFrontBundle:Records')->findAll();

		foreach ($records as $record)
		{
			$activeSheet->setCellValueExplicitByColumnAndRow(0, $row, $record->getProject());
			$activeSheet->setCellValueExplicitByColumnAndRow(1, $row, $record->getCollectionName());
			$activeSheet->setCellValueExplicitByColumnAndRow(2, $row, $record->getMediaType());
			$activeSheet->setCellValueExplicitByColumnAndRow(3, $row, $record->getUniqueId());
			$activeSheet->setCellValueExplicitByColumnAndRow(4, $row, $record->getLocation());
			$activeSheet->setCellValueExplicitByColumnAndRow(5, $row, ($record->getFormat()->getName()) ? $record->getFormat()->getName() : '');
			$activeSheet->setCellValueExplicitByColumnAndRow(6, $row, $record->getTitle());
			$activeSheet->setCellValueExplicitByColumnAndRow(7, $row, $record->getDescription());
			$activeSheet->setCellValueExplicitByColumnAndRow(8, $row, ($record->getCommercial()) ? $record->getCommercial()->getName() : '');
			$activeSheet->setCellValueExplicitByColumnAndRow(9, $row, $record->getContentDuration());
			$activeSheet->setCellValueExplicitByColumnAndRow(11, $row, $record->getCreationDate());
			$activeSheet->setCellValueExplicitByColumnAndRow(12, $row, $record->getContentDuration());
			$activeSheet->setCellValueExplicitByColumnAndRow(16, $row, ($record->getReelDiameters()) ? $record->getReelDiameters()->getName() : '');
			$activeSheet->setCellValueExplicitByColumnAndRow(34, $row, ($record->getGenreTerms()));
			$activeSheet->setCellValueExplicitByColumnAndRow(35, $row, ($record->getContributor()));
			$activeSheet->setCellValueExplicitByColumnAndRow(36, $row, $record->getGeneration());
			$activeSheet->setCellValueExplicitByColumnAndRow(37, $row, $record->getPart());
			$activeSheet->setCellValueExplicitByColumnAndRow(38, $row, $record->getCopyrightRestrictions());
			$activeSheet->setCellValueExplicitByColumnAndRow(39, $row, $record->getDuplicatesDerivatives());
			$activeSheet->setCellValueExplicitByColumnAndRow(40, $row, $record->getRelatedMaterial());
			$activeSheet->setCellValueExplicitByColumnAndRow(41, $row, $record->getConditionNote());
			$activeSheet->setCellValueExplicitByColumnAndRow(42, $row, $record->getCreatedOn()->format('Y-m-d H:i:s'));
			$activeSheet->setCellValueExplicitByColumnAndRow(43, $row, ($record->getUpdatedOn()) ? $record->getUpdatedOn()->format('Y-m-d H:i:s') : '');
			$activeSheet->setCellValueExplicitByColumnAndRow(44, $row, $record->getUser()->getName());

			if ($record->getAudioRecord())
			{
				$activeSheet->setCellValueExplicitByColumnAndRow(10, $row, ($record->getAudioRecord()->getMediaDuration()) ? $record->getAudioRecord()->getMediaDuration() : "");
				$activeSheet->setCellValueExplicitByColumnAndRow(13, $row, ($record->getAudioRecord()->getBases()) ? $record->getAudioRecord()->getBases()->getName() : '');
				$activeSheet->setCellValueExplicitByColumnAndRow(15, $row, ($record->getAudioRecord()->getDiskDiameters()) ? $record->getAudioRecord()->getDiskDiameters()->getName() : '');
				$activeSheet->setCellValueExplicitByColumnAndRow(17, $row, ($record->getAudioRecord()->getMediaDiameters()) ? $record->getAudioRecord()->getMediaDiameters()->getName() : '');
				$activeSheet->setCellValueExplicitByColumnAndRow(21, $row, ($record->getAudioRecord()->getTapeThickness()) ? $record->getAudioRecord()->getTapeThickness()->getName() : '');
				$activeSheet->setCellValueExplicitByColumnAndRow(22, $row, ($record->getAudioRecord()->getSlides()) ? $record->getAudioRecord()->getSlides()->getName() : '');
				$activeSheet->setCellValueExplicitByColumnAndRow(23, $row, ($record->getAudioRecord()->getTrackTypes()) ? $record->getAudioRecord()->getTrackTypes()->getName() : '');
				$activeSheet->setCellValueExplicitByColumnAndRow(24, $row, ($record->getAudioRecord()->getMonoStereo()) ? $record->getAudioRecord()->getMonoStereo()->getName() : '');
				$activeSheet->setCellValueExplicitByColumnAndRow(25, $row, ($record->getAudioRecord()->getNoiceReduction()) ? $record->getAudioRecord()->getNoiceReduction()->getName() : '');
			}
			if ($record->getFilmRecord())
			{
				$activeSheet->setCellValueExplicitByColumnAndRow(14, $row, ($record->getFilmRecord()->getPrintType()) ? $record->getFilmRecord()->getPrintType()->getName() : '');
				$activeSheet->setCellValueExplicitByColumnAndRow(18, $row, ($record->getFilmRecord()->getFootage()) ? $record->getFilmRecord()->getFootage() : '');
				$activeSheet->setCellValueExplicitByColumnAndRow(20, $row, ($record->getFilmRecord()->getColors()) ? $record->getFilmRecord()->getColors()->getName() : '');
				$activeSheet->setCellValueExplicitByColumnAndRow(29, $row, ($record->getFilmRecord()->getReelCore()) ? $record->getFilmRecord()->getReelCore()->getName() : '');
				$activeSheet->setCellValueExplicitByColumnAndRow(30, $row, ($record->getFilmRecord()->getSound()) ? $record->getFilmRecord()->getSound()->getName() : '');
				$activeSheet->setCellValueExplicitByColumnAndRow(31, $row, ($record->getFilmRecord()->getFrameRate()) ? $record->getFilmRecord()->getFrameRate()->getName() : '');
				$activeSheet->setCellValueExplicitByColumnAndRow(32, $row, ($record->getFilmRecord()->getAcidDetectionStrip()) ? $record->getFilmRecord()->getAcidDetectionStrip()->getName() : "");
				$activeSheet->setCellValueExplicitByColumnAndRow(33, $row, ($record->getFilmRecord()->getShrinkage()) ? $record->getFilmRecord()->getShrinkage() : '');
			}
			if ($record->getVideoRecord())
			{

				$activeSheet->setCellValueExplicitByColumnAndRow(19, $row, ($record->getVideoRecord()->getRecordingSpeed()) ? $record->getVideoRecord()->getRecordingSpeed()->getName() : '');
				$activeSheet->setCellValueExplicitByColumnAndRow(26, $row, ($record->getVideoRecord()->getCassetteSize()) ? $record->getVideoRecord()->getCassetteSize()->getName() : '');
				$activeSheet->setCellValueExplicitByColumnAndRow(27, $row, ($record->getVideoRecord()->getFormatVersion()) ? $record->getVideoRecord()->getFormatVersion()->getName() : '');
				$activeSheet->setCellValueExplicitByColumnAndRow(28, $row, ($record->getVideoRecord()->getRecordingStandard()) ? $record->getVideoRecord()->getRecordingStandard()->getName() : '');
			}$row ++;
		}
		return true;
	}

}
