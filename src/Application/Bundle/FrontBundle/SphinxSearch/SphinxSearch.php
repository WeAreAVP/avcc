<?php

namespace Application\Bundle\FrontBundle\SphinxSearch;

use Application\Bundle\FrontBundle\SphinxSearch\SphinxFields;
use Foolz\SphinxQL\SphinxQL;
use Foolz\SphinxQL\Helper;
use Foolz\SphinxQL\Connection;
use Doctrine\ORM\EntityManager;

class SphinxSearch
{

	private $conn;
	private $indexName = 'records';
	private $recordId;
	private $entityManager = null;
	private $recordTypeId;

	public function __construct(EntityManager $entityManager, $recordId=null, $recordTypeId=null)
	{
		$this->entityManager = $entityManager;
		$this->recordId = $recordId;
		$this->recordTypeId = $recordTypeId;

		$this->conn = new Connection();
		$this->conn->setParams(array('host' => 'localhost', 'port' => '9306'));
		$this->conn->silenceConnectionWarning(true);
	}

	public function insert()
	{
		$sphinxFields = new SphinxFields();
		$data = $sphinxFields->prepareFields($this->entityManager, $this->recordId, $this->recordTypeId);
		$sq = SphinxQL::create($this->conn)->insert()->into($this->indexName);
		$sq->set($data);
		return $sq->execute();
	}

	public function update()
	{
		$sphinxFields = new SphinxFields();
		$data = $sphinxFields->prepareFields($this->entityManager, $this->recordId, $this->recordTypeId);
		$sq = SphinxQL::create($this->conn)->replace()->into($this->indexName);
		$sq->set($data);
		return $sq->execute();
	}

	public function select($offset = 0, $limit = 100)
	{
		$sq = SphinxQL::create($this->conn)
		->select()
		->enqueue(Helper::create($this->conn)->showMeta())
		->from($this->indexName)
		->limit($offset, $limit);
		return $sq->executeBatch();
	}

}
