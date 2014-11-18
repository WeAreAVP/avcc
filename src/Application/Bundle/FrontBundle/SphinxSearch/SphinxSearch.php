<?php

namespace Application\Bundle\FrontBundle\SphinxSearch;

use Application\Bundle\FrontBundle\SphinxSearch\SphinxFields;
use Foolz\SphinxQL\SphinxQL;
use Foolz\SphinxQL\Helper;
use Foolz\SphinxQL\Connection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class SphinxSearch
{

    private $conn;
    private $indexName = 'records';
    private $recordId;
    private $entityManager = null;
    private $recordTypeId;

    public function __construct(EntityManager $entityManager, $recordId = null, $recordTypeId = null)
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
        $sq = SphinxQL::create($this->conn)->update($this->indexName);
        $sq->set($data);
        return $sq->execute();
    }

    public function replace()
    {
        $sphinxFields = new SphinxFields();
        $data = $sphinxFields->prepareFields($this->entityManager, $this->recordId, $this->recordTypeId);
        $sq = SphinxQL::create($this->conn)->replace()->into($this->indexName);
        $sq->set($data);
        return $sq->execute();
    }

    public function select($offset = 0, $limit = 100, $sortColumn = 'title', $sortOrder = 'asc', $criteria = null)
    {
        $sq = SphinxQL::create($this->conn)
                ->select()
                ->from($this->indexName);
        if ($criteria) {
            $this->whereClause($criteria, $sq);
        }
        $sq->orderBy($sortColumn, $sortOrder)
                ->limit($offset, $limit)
                ->enqueue(Helper::create($this->conn)->showMeta());
        return $sq->executeBatch();
    }

    public function selectCount($offset = 0, $limit = 100, $sortColumn = 'title', $sortOrder = 'asc')
    {
        $sq = SphinxQL::create($this->conn)
                ->select()
                ->from($this->indexName)
                ->orderBy($sortColumn, $sortOrder)
                ->limit($offset, $limit)
                ->enqueue(Helper::create($this->conn)->showMeta());
        return $sq->executeBatch();
    }
    
    public function facetSelect($facetColumn)
    {
        $sq = SphinxQL::create($this->conn)
                ->select($facetColumn, SphinxQL::expr('count(*) AS total'))
                ->from($this->indexName)
                ->groupBy($facetColumn)
                ->orderBy($facetColumn, 'asc');


        return $sq->execute();
    }

    public function whereClause($criteria, $sq)
    {   
        if (isset($criteria['mediaType'])) {
            $_value = implode('|',$criteria['mediaType']);
            $sq->match('s_media_type', $_value, true);
        }
        if (isset($criteria['commercial'])) {
            $_value = implode('|',$criteria['commercial']);
            $sq->match('s_commercial', $_value, true);
        }
    }

}
