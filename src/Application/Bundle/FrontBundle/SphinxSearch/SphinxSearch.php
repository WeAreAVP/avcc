<?php

namespace Application\Bundle\FrontBundle\SphinxSearch;

use Foolz\SphinxQL\SphinxQL;
use Foolz\SphinxQL\Helper;
use Foolz\SphinxQL\Connection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAware;

class SphinxSearch extends ContainerAware
{

    private $conn;
    private $indexName = 'records';
    private $recordId;
    private $entityManager = null;
    private $recordTypeId;

    public function __construct(EntityManager $entityManager, $sphinxInfo, $recordId = null, $recordTypeId = null)
    {
        $this->entityManager = $entityManager;
        $this->recordId = $recordId;
        $this->recordTypeId = $recordTypeId;

        $this->conn = new Connection();
        $this->conn->setParams(array('host' => $sphinxInfo['host'], 'port' => $sphinxInfo['port']));
        $this->conn->silenceConnectionWarning(true);
        $this->indexName = $sphinxInfo['indexName'];
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

    public function select($user, $offset = 0, $limit = 100, $sortColumn = 'title', $sortOrder = 'asc', $criteria = null)
    {
        $sq = SphinxQL::create($this->conn);
        $sq->select()
                ->from($this->indexName);
        if ($criteria) {
            $this->whereClause($criteria, $sq);
        }
        if (!in_array("ROLE_SUPER_ADMIN", $user->getRoles())) {
            $sq->where('organization_id', "=", $user->getOrganizations()->getId());
        }
        $result = $sq->orderBy($sortColumn, $sortOrder)
                ->limit($offset, $limit)
                ->enqueue(SphinxQL::create($this->conn)->query('SHOW META'))
                ->executeBatch();
        return $result;        
//        $sql = $sq->getCompiled();
//        echo json_encode(array('result' => $result, 'sql' => $sql));
//        exit;
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

    public function facetSelect($facetColumn, $criteria = null, $parentFacet = null)
    {
        $sq = SphinxQL::create($this->conn)
                ->select($facetColumn, SphinxQL::expr('count(*) AS total'))
                ->from($this->indexName);
        if ($criteria) {
            $this->whereClause($criteria, $sq, $parentFacet);
        }
        $sq->where($facetColumn, '!=', '');
        $sq->groupBy($facetColumn)
                ->orderBy($facetColumn, 'asc');

        return $sq->execute();
    }

    public function whereClause($criteria, $sq, $parentFacet = null)
    {
        foreach ($criteria as $key => $value) {
            if ($key == 'is_review') {
                if ($value == 1) {
                    $sq->where($key, '=', 1);
                } elseif ($value == 2) {
                    $sq->where($key, '=', 0);
                }
            } else {
//                if ($parentFacet && $key != 's_' . $parentFacet) {
                    $_value = (is_array($value)) ? implode('|', $value) : $value;
                    $sq->match($key, $_value, true);
//                }
            }
        }
    }

}
