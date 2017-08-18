<?php
namespace FiremonPHP\Query;


class FindQuery
{
    /**
     * @var \FiremonPHP\Operations\Read
     */
    private $baseRead;

    public function __construct(\FiremonPHP\Operations\Read $read)
    {
        $this->baseRead = $read;
    }

    /**
     * @param $dateOne
     * @param $dateTwo
     * @return $this
     */
    public function betweenDates($dateOne, $dateTwo)
    {
        return $this;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit)
    {
        return $this;
    }

    /**
     * @return $this
     */
    public function fields(array $fieldsName)
    {
        return $this;
    }

    /**
     * @return $this
     */
    public function where()
    {
        return $this;
    }

    /**
     * Execute query
     * @return \FiremonPHP\Operations\Read
     */
    public function execute()
    {
        return $this->baseRead;
    }
}