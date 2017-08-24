<?php

require __DIR__ . DIRECTORY_SEPARATOR .DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';


$read = new \FiremonPHP\Operations\Read();

$read->find('users/')
    ->

print_r($read);