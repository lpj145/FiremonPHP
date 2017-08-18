<?php
namespace FiremonPHP\Operations;


use FiremonPHP\Data\DataSet;

class Write implements OperationsInterface
{
    /**
     * @var \FiremonPHP\Data\DataSet
     */
    private $dataSet;


    public function __construct($data = null)
    {
        $this->dataSet = new \FiremonPHP\Data\DataSet($data);
    }

    public function add(array $value)
    {
        $this->dataSet->add($value);
        return $this;
    }

    public function setIndex(String $ns, $value)
    {
        $this->dataSet->setIndexKey($ns, $value);
        return $this;
    }

    public function getDataSet(): DataSet
    {
        return $this->dataSet;
    }

    public function getOperationType(): string
    {
        return 'Write';
    }

    /**
     * @param String $ns
     * @return $this
     */
    public function setMany(String $ns)
    {
        $this->dataSet->setOptions($ns, 'multi', true);
        return $this;
    }
}