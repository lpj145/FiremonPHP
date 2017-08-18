<?php
namespace FiremonPHP\Core;


interface MainLoopInterface
{
    public function setData(\Iterator $data);

    public function setLevelOne(callable $fun);

    public function setLevelTwo(callable $fun);

    public function setBeforeNext(callable $fun);

    public function setEndIterate(callable $fun);

    public function run():void;

}