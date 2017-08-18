<?php
namespace FiremonPHP\Connector;


class ConnectionManager
{
    /**
     * @var \FiremonPHP\Connector\Connection[]
     */
    private static $connections = [];

    /**
     * Get configured connections
     * @param String $connectionName
     * @return Connection
     * @throws \Exception
     */
    public static function get(String $connectionName)
    {
        if (isset(self::$connections[$connectionName])) {
            return self::getConnectionOrInitialize($connectionName);
        } else {
            throw new \Exception('The instance that you want is not available, try to set up before!');
        }
    }

    /**
     * @param String $connectionName
     * @param array $config
     */
    public static function config(String $connectionName, \FiremonPHP\Connector\Driver\DriverInterface $driver)
    {
        unset(self::$connections[$connectionName]);
        self::$connections[$connectionName]['driver'] = $driver;
    }

    /**
     * Initialite all mongodb configurations!
     * @param Driver\DriverInterface $driver
     * @return \MongoDB\Driver\Manager
     */
    private static function initializeManager(\FiremonPHP\Connector\Driver\DriverInterface $driver)
    {
        $options = $driver->getOptionalConfig();
        $options['username'] = $driver->getUsername();
        $options['password'] = $driver->getPassword();
        $manager = new \MongoDB\Driver\Manager(
            'mongodb://'.$driver->getHost().$driver->getReplicas(),
            $options
        );

        return $manager;
    }

    /**
     * @param String $connectionName
     * @return Connection
     */
    private static function getConnectionOrInitialize(String $connectionName)
    {
        $driver = self::$connections[$connectionName]['driver'];
        if (self::$connections[$connectionName] instanceof \FiremonPHP\Connector\Connection) {
            return self::$connections[$connectionName];
        }

        self::$connections[$connectionName] = new \FiremonPHP\Connector\Connection(
            self::initializeManager($driver),
            $driver
        );

        return self::$connections[$connectionName];
    }

}