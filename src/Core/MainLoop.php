<?php
/**
 * Created: 17/08/2017 - 00:47
 * Esta lib prove um meio de execução único, aqui se encontram:
 * validações, callback data, salvar, todas as funcões em uma única iteração,
 * você deve ser capaz de iterar 3 niveis de um array.
 * ex: [
 *   'users' => [
 *      []
 *   ],
 *   'post' => ['description'],
 *   'comments' => null
 * ]
 */

namespace FiremonPHP\Core;


class MainLoop implements MainLoopInterface
{
    /**
     * @var array
     */
    private $_LevelOne = [];
    /**
     * @var array
     */
    private $_LevelTwo = [];
    /**
     * @var array
     */
    private $_BeforeNext = [];
    /**
     * @var array
     */
    private $_EndIterate = [];

    /**
     * @var \Iterator
     */
    private $data;

    /**
     * @var bool
     */
    public $secondLoop = true;


    public function setData(\Iterator $data)
    {
        $this->data = $data;
    }

    /**
     * Execute main loop
     * all function if have, now called
     */
    public function run(): void
    {
        if ($this->data === null) {
            throw new \Exception('Not dataset configured!');
        }

        while(true) {
            $this->executeFunctions('LevelOne', $this->data->key(), $this->data->current());

            if ($this->secondLoop) {
                foreach ($this->data->current() as $key => $item) {
                    $this->executeFunctions('LevelTwo', $key, $item);
                }
            }

            $this->executeFunctions('BeforeNext', $this->data->current(), $this->data->key());
            $this->data->next();

            if (!$this->data->valid()) {
                $this->secondLoop = true;
                $this->executeFunctions('EndIterate', null, null);
                break;
            }
        }
    }

    /**
     * @param callable $fun
     */
    public function setLevelOne(callable $fun)
    {
        $this->_LevelOne[] = $fun;
    }

    /**
     * @param callable $fun
     */
    public function setLevelTwo(callable $fun)
    {
        $this->_LevelTwo[] = $fun;
    }

    /**
     * @param callable $fun
     */
    public function setBeforeNext(callable $fun)
    {
        $this->_BeforeNext[] = $fun;
    }

    /**
     * @param callable $fun
     */
    public function setEndIterate(callable $fun)
    {
        $this->_EndIterate[] = $fun;
    }

    /**
     * Check if exists 0 index on specific function name and init call's functions
     * @param String $funcitonsName
     * @param null $key
     * @param null $value
     */
    private function executeFunctions(String $funcitonsName, $key = null, $value = null)
    {
        if (isset($this->{'_'.$funcitonsName}[0])) {
            $this->execute($this->{'_'.$funcitonsName}, $key, $value);
        }
    }

    /**
     * Execute all function on function name index
     * @param array $fun
     * @param $key
     * @param $value
     * @param int $countRun
     */
    private function execute(array $fun, $key, $value, int $countRun = 0)
    {
        $fun[$countRun]($key, $value);

        if (isset($fun[$countRun+1])) {
            $this->execute($fun, $key, $value, $countRun+1);
        }
    }

}