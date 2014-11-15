<?php

namespace Application\Bundle\FrontBundle\SphinxSearch;

use Application\Bundle\FrontBundle\SphinxSearch\SphinxFields;

use Foolz\SphinxQL\SphinxQL;
use Foolz\SphinxQL\Connection;

class SphinxSearch
{

	private $conn;
	private $indexName = 'records';
	private $recordId;

	public function __construct($recordId)
	{
		$this->recordId = $recordId;
		

		$this->conn = new Connection();
//		$this->conn->setParams(array('host' => $params['host'], 'port' => $params['port']));
//		$this->conn->silenceConnectionWarning(true);
	}

	public function insert()
	{
		$sphinxFields = new SphinxFields();
		$data = $sphinxFields->prepareFields($this->getDoctrine()->getManager(), $this->recordId);
		echo '<pre>';
		print_r($data);
		exit;
		$sq = SphinxQL::create($this->conn)->insert()->into($this->indexName);
		$sq->set($data);
		return $sq->execute();
	}

}
