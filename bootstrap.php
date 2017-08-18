<?php

$CloudDriver = new \FiremonPHP\Connector\Driver\CloudDriver([
    'url' => 'localhost:27017',
    'database' => 'nfce',
    'username' => 'kasoneri',
    'password' => 'y84h65t16',
    'master' => 'Nfce-shard-0',
    'optional' => [
        'authSource' => 'admin',
    ]
]);

\FiremonPHP\Connector\ConnectionManager::config('default', $CloudDriver);