<?php

/*
 * File SphinxSearch
 *
 *
 */

namespace Application\Bundle\FrontBundle\SphinxSearch;

use Foolz\SphinxQL\SphinxQL;
use Foolz\SphinxQL\Connection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * SphinxSearch is used to manage all operations for sphinxsearch service.
 * It uses SphinxQL.
 *
 */
class SphinxSearch extends ContainerAware
{

    /**
     * Connection for sphinxQL.
     * @var Connection
     */
    private $conn;

    /**
     * Name of the index.
     * @var string
     */
    private $indexName = 'records';

    /**
     * Record ID.
     * @var integer
     */
    private $recordId;

    /**
     * Entity manager.
     *
     * @var EntityManager
     */
    private $entityManager = null;

    /**
     * Record type
     *
     * @var type
     */
    private $recordTypeId;

    /**
     * Constructor of SphinxSearch
     *
     * @param EntityManager $entityManager
     * @param array         $sphinxInfo
     * @param integer       $recordId
     * @param integer       $recordTypeId
     */
    public function __construct(EntityManager $entityManager, array $sphinxInfo, $recordId = null, $recordTypeId = null)
    {
        $this->entityManager = $entityManager;
        $this->recordId = $recordId;
        $this->recordTypeId = $recordTypeId;

        $this->conn = new Connection();
        $this->conn->setParams(array('host' => $sphinxInfo['host'], 'port' => $sphinxInfo['port']));
        $this->conn->silenceConnectionWarning(true);
        $this->indexName = $sphinxInfo['indexName'];
    }

    /**
     * Insert new record to sphinx index.
     *
     * @return array on count of records.
     */
    public function insert()
    {
        $sphinxFields = new SphinxFields();
        $data = $sphinxFields->prepareFields($this->entityManager, $this->recordId, $this->recordTypeId);
        $sq = SphinxQL::create($this->conn)->insert()->into($this->indexName);
        $sq->set($data);

        return $sq->execute();
    }

    /**
     * Replace the values for existing record.
     *
     * @return array on count of records.
     */
    public function replace()
    {
        $sphinxFields = new SphinxFields();
        $data = $sphinxFields->prepareFields($this->entityManager, $this->recordId, $this->recordTypeId);
        $sq = SphinxQL::create($this->conn)->replace()->into($this->indexName);
        $sq->set($data);

        return $sq->execute();
    }

    /**
     * Select record for listing from sphinx.
     *
     * @param User    $user
     * @param integer $offset
     * @param integer $limit
     * @param string  $sortColumn
     * @param string  $sortOrder
     * @param string  $criteria
     *
     * @return array
     */
    public function select($user, $offset = 0, $limit = 100, $sortColumn = 'title', $sortOrder = 'asc', $criteria = null)
    {
        $sq = SphinxQL::create($this->conn);
        $sq->select()
                ->from($this->indexName);
        if ($criteria) {
            $this->whereClause($criteria, $sq);
        }
        $this->roleCriteria($user, $sq);

        $result = $sq->orderBy($sortColumn, $sortOrder)
                ->limit($offset, $limit)
                ->enqueue(SphinxQL::create($this->conn)->query('SHOW META'))
                ->executeBatch();

        return $result;
    }

    /**
     * Prepare facet for sphinx attribute.
     *
     * @param string $facetColumn
     * @param array  $criteria
     * @param string $parentFacet
     *
     * @return array
     */
    public function facetSelect($facetColumn, $user, $criteria = null, $parentFacet = false, $orderByColumnName = null, $groupByColumnName = null)
    {
        $sq = SphinxQL::create($this->conn)
                ->select($facetColumn, SphinxQL::expr('count(*) AS total'))
                ->from($this->indexName);
        if ($criteria && $facetColumn != $parentFacet) {
            $this->whereClause($criteria, $sq);
        }
        $this->roleCriteria($user, $sq);
        $sq->where($facetColumn, '!=', '');

        if ($groupByColumnName) {
            $sq->groupBy($groupByColumnName);
        }
        $sq->groupBy($facetColumn);
        if ($orderByColumnName) {
            $sq->orderBy($orderByColumnName, 'asc');
        }
        $sq->orderBy($facetColumn, 'asc');

        $sq->limit(0, 100);
        return $sq->execute();
//        $q = array('result'=>$sq->execute(),'query'=>$sq->getCompiled());
//        echo '<pre>';
//        print_r($q);
//        exit;
    }

    /**
     * Make where clause for searching.
     *
     * @param array    $criteria
     * @param SphinxQL $sq
     *
     * @return void
     */
    public function whereClause($criteria, $sq)
    {
        foreach ($criteria as $key => $value) {
            if ($key == 'is_review') {
                if ($value == 1) {
                    $sq->where($key, '=', 1);
                } elseif ($value == 2) {
                    $sq->where($key, '=', 0);
                }
            } elseif ($key == 'project_id') {
                $sq->where('project_id', "=", $value);
            } else {

                $_value = (is_array($value)) ? '"' . implode('" | "', $value) . '"' : $value;

                $sq->match($key, $_value, true);
            }
        }
    }

    public function removeEmpty($facet, $index)
    {
        $result = array();
        foreach ($facet as $key => $value) {
            foreach ($value as $column => $row) {
                if ($column == $index && !empty($row))
                    $result[] = $value;
            }
        }

        return $result;
    }

    /**
     * Get meta data from sphinx.
     *
     * @param User   $user
     * @param string $criteria
     *
     * @return array
     */
    public function getMeta($user, $criteria = null)
    {
        $sq = SphinxQL::create($this->conn);
        $sq->select('*')
                ->from($this->indexName);
        if ($criteria) {
            $this->whereClause($criteria, $sq);
        }
        $this->roleCriteria($user, $sq);
        $result = $sq
                ->limit(0)
                ->enqueue(SphinxQL::create($this->conn)->query('SHOW META'))
                ->executeBatch();

        return $result;
    }

    protected function roleCriteria($user, $sq)
    {
        if (!in_array("ROLE_SUPER_ADMIN", $user->getRoles())) {
            if (!in_array("ROLE_MANAGER", $user->getRoles()) && $user->getUserProjects()) {
                $projectIdArr = null;
                foreach ($user->getUserProjects() as $project) {
                    $projectIdArr[] = $project->getId();
                }
                if ($projectIdArr)
                    $sq->where('project_id', 'IN', $projectIdArr);
                $sq->where('organization_id', "=", $user->getOrganizations()->getId());
            }
            if (in_array("ROLE_MANAGER", $user->getRoles()) && $user->getOrganizations()) {
                $sq->where('organization_id', "=", $user->getOrganizations()->getId());
            }
        }
    }

    /**
     * get count and media/content duration sum for report.
     *
     * @param string $facetColumn
     * @param array  $criteria
     * @param string $parentFacet
     *
     * @return array
     */
    public function facetDurationSumSelect($facetColumn, $user, $criteria = null, $parentFacet = false)
    {
        $sq = SphinxQL::create($this->conn)
                ->select($facetColumn, SphinxQL::expr('count(*) AS total'), SphinxQL::expr('sum(content_duration) AS sum_content_duration'), SphinxQL::expr('width'))
                ->from($this->indexName);
        if ($criteria && $facetColumn != $parentFacet) {
            $this->whereClause($criteria, $sq);
        }
        $this->roleCriteria($user, $sq);
        $sq->where($facetColumn, '!=', '');
        $sq->groupBy($facetColumn)
                ->orderBy($facetColumn, 'asc');

        return $sq->execute();
    }

    /**
     * get width for report.
     *
     * @param string $facetColumn
     * @param array  $criteria
     * @param string $parentFacet
     *
     * @return array
     */
    public function facetWidthSelect($facetColumn, $user, $criteria = null, $parentFacet = false)
    {
        $sq = SphinxQL::create($this->conn)
                ->select($facetColumn, SphinxQL::expr('count(*) AS total'), SphinxQL::expr('width'))
                ->from($this->indexName);
        if ($criteria && $facetColumn != $parentFacet) {
            $this->whereClause($criteria, $sq);
        }
        $this->roleCriteria($user, $sq);
        $sq->where($facetColumn, '!=', '');
        $sq->groupBy($facetColumn)
                ->orderBy($facetColumn, 'asc');

        return $sq->execute();
    }

}
