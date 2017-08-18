<?php
namespace FiremonPHP\Operations;


use FiremonPHP\Data\DataSet;

interface OperationsInterface
{
    public function getDataSet():DataSet;

    public function getOperationType():string;
}