<?php
namespace FiremonPHP\Data;


use function PHPSTORM_META\type;

class DataSet implements \Iterator
{
    private $data;

    public function __construct($data)
    {
        $this->add($data);
    }

    public function add($value)
    {
        if ($value !== null) {
            $this->buildAssociative($value);
        }
    }

    private function buildAssociative(array $values)
    {
        $key = key($values);

        if (!$this->isRemovedData($key, $values)) {
            $subKey = key($values[$key]);
            if (is_string($values[$key][$subKey])) {
                if (!$this->isUpdatedData($key, $values[$key])) {
                    $this->data[$key][] = $values[$key];
                }
            } else {
                $this->data[$key] = $values[$key];
            }
        }

        unset($values[$key]);

        if (count($values) > 0) {
            $this->buildAssociative($values);
        }
    }

    private function isUpdatedData($key, $value) {
        $posBar = strpos($key, '/');
        if ($posBar) {
            $ns = substr($key, 0, $posBar);
            $subKey = substr($key,$posBar );
            $this->data[$ns][$subKey] = $value;
            return true;
        }
        return false;
    }

    private function isRemovedData($key, $value)
    {
        if ($value[$key] === null) {
            $this->isUpdatedData($key, $value[$key]);
            return true;
        }
        return false;
    }

    public function rewind() {
        reset($this->data);
    }

    public function current() {
        return current($this->data);
    }

    public function key() {
        return key($this->data);
    }

    public function next() {
        next($this->data);
    }

    public function valid() {
        return key($this->data) !== null;
    }

    public function setIndexKey($index, $keyName)
    {
        if (isset($this->data[$index])) {
            $this->data[$index]['index'] = $keyName;
        }
    }

    public function setOptions($index, $optionName, $value)
    {
        if (isset($this->data[$index])) {
            $this->data[$index]['options'][$optionName] = $value;
        }
    }
}