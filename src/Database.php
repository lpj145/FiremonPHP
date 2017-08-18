<?php
namespace FiremonPHP;


use FiremonPHP\Connector\ConnectionManager;

class Database
{
    /**
     * @var \FiremonPHP\Connector\Connection
     */
    private $connection;

    /**
     * @var \MongoDB\Driver\BulkWrite
     */
    private $write;

    /**
     * @var array
     */
    private $read = [];

    /**
     * @var Core\MainLoop
     */
    private $loop;

    /**
     * @var string
     */
    private $operationType;

    private $baseCollection;

    private $indexCollection;

    private $optionsCollection;

    public function __construct(String $configName = null)
    {
        if ($configName === null) {
            $this->connection = ConnectionManager::get('default');
        } else {
            $this->connection = ConnectionManager::get($configName);
        }

        $this->loop = new \FiremonPHP\Core\MainLoop();
    }

    /**
     * Execute block of instruction to validate, callback data, save operations registers and etc..
     * after opertaions registereds, execute main loop to iterate data
     * @param Operations\OperationsInterface $operations
     */
    public function execute(\FiremonPHP\Operations\OperationsInterface $operations)
    {
        $this->operationType = $operations->getOperationType();
        $this->loop->setData($operations->getDataSet());
        $this->registerValidations();
        $this->registerCallbackData();
        $this->registerExecuteOperations();
        $this->loop->run();
    }


    private function registerValidations()
    {
        //TODO implements validations methods!
    }

    private function registerCallbackData()
    {
        //TODO implements callback data!
    }

    /**
     * Register all functions to execute!
     */
    private function registerExecuteOperations()
    {
        $this->loop->setLevelOne(function($key, $item){
           $this->baseCollection = $key;
           $this->setIndexKey($item);
           $this->setOptions($item);
           $this->setWriteBulk();
        });

        $this->loop->setLevelTwo(function($key, $item){
           $this->setOperations($key, $item);
        });

        $this->loop->setBeforeNext(function(){
            $this->executeOperations();
            $this->indexCollection = null;
            $this->optionsCollection = null;
        });
    }

    private function executeOperations()
    {
        if ($this->operationType === 'Write') {
            $namespace = $this->connection->driver->getDatabase().'.'.$this->baseCollection;
            $this->connection->manager->executeBulkWrite($namespace, $this->write);
            echo 'Executado: '.$this->write->count().' operações'.PHP_EOL;
        }
    }

    /**
     * set operation by type of operation inject!
     * @param $key
     * @param $item
     */
    private function setOperations($key, $item)
    {
        if ($key === 'index' || $key === 'options') {
            return;
        }

        if ($this->operationType === 'Write') {
            $this->setWrite($key, $item);
        } elseif ($this->operationType === 'Read') {
            $this->setRead();
        }
    }

    /**
     * Set operation by type key and data
     * if key is filtering key, data is to update or remove
     * else data is to insert!
     * @param $key
     * @param $item
     */
    private function setWrite($key, $item)
    {
        if ($this->isFilterKey($key)) {
            $key = substr($key, 1);
            if ($item === null) {
                $this->removeDocument($key);
            } else {
                $this->updateDocument($key, $item);
            }
        } else {
            $this->write->insert($item);
        }
    }

    private function setRead()
    {

    }

    /**
     * Set updating data document
     * @param $key
     * @param array $data
     */
    private function updateDocument($key, array $data)
    {
        $this->write->update($this->getIndexWithFilterKey($key), ['$set' => $data], $this->optionsCollection);
    }

    /**
     * Set removed documents
     * @param $key
     */
    private function removeDocument($key)
    {
        $this->write->delete($this->getIndexWithFilterKey($key), ['limit' => 1]);
    }

    /**
     * Verify first key char is '/'
     * @param $key
     * @return bool
     */
    private function isFilterKey($key)
    {
        return $key[0] === '/';
    }

    /**
     * When operation type is write, set new bulk to write!
     */
    private function setWriteBulk()
    {
        if ($this->operationType === 'Write') {
            $this->write = new \MongoDB\Driver\BulkWrite();
        }
    }

    /**
     * Set $indexCollection
     * @param $key
     */
    private function setIndexKey($collectionData)
    {
        if (isset($collectionData['index'])) {
            $this->indexCollection = $collectionData['index'];
        }
    }

    private function setOptions($collcetionData)
    {
        if (isset($collcetionData['options'])) {
            $this->optionsCollection = $collcetionData['options'];
        } else {
            $this->optionsCollection = [];
        }
    }

    /**
     * Return index with key definid or default!
     * @param $key
     * @return array
     */
    private function getIndexWithFilterKey($key) {
        if ($this->indexCollection === null) {
            return [
              '_id' => new \MongoDB\BSON\ObjectID($key)
            ];
        } else {
            return [
              $this->indexCollection => $key
            ];
        }
    }
}