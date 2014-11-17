<?php

namespace Application\Bundle\FrontBundle\SphinxSearch;

use Application\Bundle\FrontBundle\SphinxSearch\SphinxFields;
use Foolz\SphinxQL\SphinxQL;
use Foolz\SphinxQL\Connection;
use Doctrine\ORM\EntityManager;

class SphinxSearch
{

	private $conn;
	private $indexName = 'records';
	private $recordId;
	private $entityManager = null;

	public function __construct(EntityManager $entityManager, $recordId)
	{
		$this->entityManager = $entityManager;
		$this->recordId = $recordId;


		$this->conn = new Connection();
		$this->conn->setParams(array('host' => 'localhost', 'port' => '9306'));
		$this->conn->silenceConnectionWarning(true);
	}

	public function insert()
	{
		$sphinxFields = new SphinxFields();
		$data = $sphinxFields->prepareFields($this->entityManager, $this->recordId, true);
		echo '<pre>';
		print_r($data);
		exit;
		$sq = SphinxQL::create($this->conn)->insert()->into($this->indexName);
		$sq->set($data);
		return $sq->execute();
	}

	public function update()
	{
		$sphinxFields = new SphinxFields();
		$data = $sphinxFields->prepareFields($this->entityManager, $this->recordId);
//		echo '<pre>';
//		print_r($data);
//		exit;
//		$sq = SphinxQL::create($this->conn)->update($this->indexName);
                $sq = SphinxQL::create($this->conn)->replace()->into($this->indexName);
		$sq->set($data);
//                $sq->where('id', '=' , $this->recordId);
		return $sq->execute();
	}

}
