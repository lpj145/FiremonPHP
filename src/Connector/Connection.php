<?php
namespace FiremonPHP\Connector;


class Connection
{
    /**
     * @var \MongoDB\Driver\Manager
     */
    public $manager;
    /**
     * @var Driver\DriverInterface
     */
    public $driver;

    public function __construct(\MongoDB\Driver\Manager $manager, \FiremonPHP\Connector\Driver\DriverInterface $driver)
    {
        $this->manager = $manager;
        $this->driver = $driver;
    }
}