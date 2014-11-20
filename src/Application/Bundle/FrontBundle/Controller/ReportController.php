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
	 * @Route("/allformats", name="all_formats")
	 * @Method("GET")
	 * @Template()
	 * @return array
	 */
	public function allFormatsAction()
	{
		$phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
		$phpExcelObject->getProperties()->setCreator("AVCC - AVPreserve")
		->setTitle("AVCC - Report")
		->setSubject("Report for all formats")
		->setDescription("Report for all formats");
		$activeSheet = $phpExcelObject->setActiveSheetIndex(0);
		foreach ($this->columns as $column => $columnName)
		{
			$activeSheet->setCellValueExplicitByColumnAndRow($column, 1, $columnName);
		}
//		$activeSheet->setCellValue('A1', 'Hello');
//		$activeSheet->setCellValue('B2', 'world!');
//		$activeSheet->setCellValueExplicitByColumnAndRow(0, 1);
		$phpExcelObject->getActiveSheet()->setTitle('All Formats');
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$phpExcelObject->setActiveSheetIndex(0);
//		$this->downloadReport($phpExcelObject);
		$writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
		// create the response
		$response = $this->get('phpexcel')->createStreamedResponse($writer);
		$response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
		$response->headers->set('Content-Disposition', 'attachment;filename=stream-file.xls');
		$response->headers->set('Pragma', 'public');
		$response->headers->set('Cache-Control', 'maxage=1');

		return $response;
		return array();
	}

	private function downloadReport($phpExcelObject)
	{
		// create the writer
	}

}
