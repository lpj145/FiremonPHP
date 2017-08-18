<?php

require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';


$loop = new FiremonPHP\Core\MainLoop();

$arr = new ArrayIterator([
    'users' => [
        ['nome' => 'Marcos Dantas', 'cidade' => 'Parelhas'],
    ],
    'posts' => [
        'title' => 'Titulo de teste',
        'description' => 'Some description data'
    ]
]);

$loop->setData($arr);

$loop->setLevelTwo(function($key, $item){
   print_r($item);
});

$loop->setLevelTwo(function($key, $item){
   print_r($item);
});

$loop->setEndIterate(function(){
   echo 'Finalizaou!'.PHP_EOL;
});

$loop->setEndIterate(function(){
    echo 'Finalizaou!'.PHP_EOL;
});
$loop->setEndIterate(function(){
    echo 'Finalizaou!'.PHP_EOL;
});
$loop->setEndIterate(function(){
    echo 'Finalizaou!'.PHP_EOL;
});
$loop->setEndIterate(function(){
    echo 'Finalizaou!'.PHP_EOL;
});

$loop->run();