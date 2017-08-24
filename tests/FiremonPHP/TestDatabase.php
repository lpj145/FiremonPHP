<?php

require __DIR__ . DIRECTORY_SEPARATOR .DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';


$database = new \FiremonPHP\Database();

$arr = [
    'users' => ['nome' => 'Marcos Dantas', 'cidade' => 'Parelhas'],
    'users/Parelhas' => ['cidade' => 'Atualizou!'],

];

$newData = new \FiremonPHP\Operations\Write($arr);

$newData
    ->setIndex('users','cidade')
    ->setMany('users');

$database->execute($newData);
