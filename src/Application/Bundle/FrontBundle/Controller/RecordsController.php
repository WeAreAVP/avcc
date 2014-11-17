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

/**
 * Records controller.
 * 
 * @Route("/")
 *
 */
class RecordsController extends Controller
{

	private $columnOrder = array();
	private $defaultFields;
	private $columns = array();
	private $userFields = array();
	private $session;
	private $offset;
	private $limit;

	public function __construct()
	{
		$this->columns = array(
			'checkbox_Col' => 'checkboxCol',
			'Project_Name' => 'projectName',
			'Unique_ID' => 'uniqueId',
			'Title' => 'title',
			'Collection_Name' => 'collectionName',
			'Location' => 'location'
		);
		;
		$this->defaultFields = new DefaultFields();
		$this->limit = 10;
	}

	/**
	 * Lists all AudioRecords entities.
	 *
	 * @Route("/", name="record_list")
	 * @Method("GET")
	 * @Template("ApplicationFrontBundle:AudioRecords:index.html.twig")
	 * @return array
	 */
	public function indexAction(Request $request)
	{



		$column = $this->columns;



		return array(
			'columns' => $column
		);
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
		$em = $this->getDoctrine()->getManager();
		$sEcho = $request->query->get('sEcho');
		$sortOrder = $request->query->get('sSortDir_0');
		$sortIndex = $request->query->get('iSortCol_0');

		$offset = $request->query->get('iDisplayStart') ? $request->query->get('iDisplayStart') : 0;
		$limit = $request->query->get('iDisplayLength') ? $request->query->get('iDisplayLength') : 10;







		$sphinxSearch = new SphinxSearch($em);
		$result = $sphinxSearch->select($offset, $limit);
		$records = $result[0];
		$currentPageTotal = count($records);
		$totalRecords = $result[1][0]['Value'];


		$tableView = $this->defaultFields->recordDatatableView($records);

		$dataTable = array(
			'sEcho' => intval($sEcho),
			'iTotalRecords' => intval($totalRecords),
			'iTotalDisplayRecords' => intval($currentPageTotal),
			'aaData' => $tableView
		);
		echo json_encode($dataTable);
		exit;
	}

	private function getData($entities)
	{
		$data = array();
		$data['total'] = count($entities);
		$data['records'] = $entities;
		$data['count'] = count($entities);
		if ($data['count'] > 0 && $this->offset === 0)
		{
			$data['start'] = 1;
			$data['end'] = $data['count'];
		}
		else
		{
			$data['start'] = $this->offset;
			$data['end'] = intval($this->offset) + intval($data['count']);
		}

		return $data;
	}

	protected function getSphinxInfo()
	{
		return $this->container->getParameter('sphinx_param');
	}

}
