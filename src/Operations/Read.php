<?php
namespace FiremonPHP\Operations;


use FiremonPHP\Data\DataSet;
use FiremonPHP\Query\FindQuery;

class Read implements OperationsInterface
{
    private $dataSet;

    public function __construct($data = null)
    {
        $this->dataSet = new \FiremonPHP\Data\DataSet($data);
    }

    /**
     * @param String $urlPath
     * @return FindQuery
     */
    public function find(String $urlPath)
    {
        return new FindQuery($this);
    }

    public function getDataSet(): DataSet
    {
        return $this->dataSet;
    }

    public function getOperationType(): string
    {
        return 'Read';
    }
}