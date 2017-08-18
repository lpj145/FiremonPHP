<?php
namespace FiremonPHP\Connector\Driver;

interface DriverInterface
{
    public function getHost():string;

    public function getDatabase():string;

    public function getUsername():string;

    public function getPassword():string;

    public function getReplicas():string;

    public function getOptionalConfig():array;
}
