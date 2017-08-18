<?php
namespace FiremonPHP\Connector\Driver;


class CloudDriver implements DriverInterface
{
    /**
     * @var string
     */
    private $host;
    /**
     * @var string
     */
    private $database;
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $password;

    /**
     * @var array
     */
    private $replicas = [];
    /**
     * @var bool string
     */
    private $ssl = true;

    /**
     * @var mixed|string
     */
    private $master = '';

    /**
     * @var int
     */
    private $invalidCredentials = 5;

    /**
     * @var array
     */
    private $optionalConfigs = [];

    /**
     * Initialize Cloud Driver with credentials array or url string by cloud connector
     * @param array $config
     */
    public function __construct(array $config)
    {
        if (count($config) === 1 && !empty($config['url'])) {
            $this->byCompleteUrl($config['url']);
        } else {
            $this->invalidCredentials--;
            $this->host = $config['url'];
        }

        if (!empty($config['database'])) {
            $this->invalidCredentials--;
            $this->database = $config['database'];
        }

        if (!empty($config['username'])) {
            $this->invalidCredentials--;
            $this->username = $config['username'];
        }

        if (!empty($config['password'])) {
            $this->invalidCredentials--;
            $this->password = $config['password'];
        }

        if (!empty($config['replicas'])) {
            $this->replicas = $config['replicas'];
        }

        if(!empty($config['ssl'])) {
            $this->ssl = (bool)$config['ssl'];
        }

        if (!empty($config['master'])) {
            $this->master = $config['master'];
        }

        if (!empty($config['optional'])) {
            $this->optionalConfigs = $config['optional'];
        }
    }


    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getDatabase(): string
    {
        return $this->database;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getReplicas(): string
    {
        $replicasUrl = implode(',', $this->replicas);
        if (!empty($replicasUrl)) {
            return ','.$replicasUrl;
        }
        return '';
    }

    public function getOptionalConfig(): array
    {
        return $this->optionalConfigs;
    }


    private function byCompleteUrl(String $connectionString)
    {
        $urlParsed = parse_url($connectionString);
        $queries = [];
        if (count($urlParsed) < 5) {
            throw new \Exception('The URL cannot represent all the parameters');
        }
        $hosts = explode(',', $urlParsed['host']);
        $this->host = array_shift($hosts);
        $this->replicas = $hosts;
        $this->username = $urlParsed['user'];
        $this->password = $urlParsed['pass'];
        parse_str($urlParsed['query'], $queries);
        $this->optionalConfigs = $queries;
    }

}