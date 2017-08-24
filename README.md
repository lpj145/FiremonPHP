# FiremonPHP
Este projeto é uma iniciativa para tornar operações com o mongodb mais fáceis, visando legibilidade, desempenho e escalabilidade.

Firemon deverá se capaz de adicionar, atualizar, excluir e ler dados ou documentos do mongo, esse projeto não é nenhum ODM ou Mapper Data,
porém deve ser de interesse posterior, o Firemon nasce para trazer a mesma facilidade do Firebase porém com mongodb (local ou cluster cloud)


### Instanciando uma conexão
```php
$CloudDriver = new \FiremonPHP\Connector\Driver\CloudDriver([
    'url' => 'localhost:27017',
    'database' => '',
    'username' => '',
    'password' => '',
    'master' => 'Nfce-shard-0',
    'optional' => [
        'authSource' => 'admin',
    ]
]);

\FiremonPHP\Connector\ConnectionManager::config('default', $CloudDriver);
```

### Adicionando dados

```php
$database = new \FiremonPHP\Database();

$arr = [
    'users' => ['nome' => 'Marcos Dantas', 'cidade' => 'Parelhas'], // isso já é capaz de adicionar dados a partir de um namespace
    'users/Parelhas' => ['cidade' => 'Atualizou!'], // Isso já é capaz de atualizar todas ou uma única chave definida abaixo,
    'users/8748494984' => null, // aqui uma delecao usando a chave _id do mongo principal.
    'posts' => [ // Aqui vemos a possibilidade de numa mesma escrita adicionarmos um ou muitos documentos a outras coleções.
      ['title' => 'some title', 'description' => 'Alguns dados adicionais'],
      ['title' => 'Post de teste', 'description' => 'Alguns dados adicionais']
    ],
    'posts/984984' => null // Deletando o post de id 984984
];

$newData = new \FiremonPHP\Operations\Write($arr); // Toda a operação é executada!

$newData
    ->setIndex('users','cidade') // Setamos uma chave principal para filtro de registro no uso da deleção ou atualização
    ->setMany('users'); // Essa operação pode ocorrer em muitos registros!

$database->execute($newData); // Execute tudo!
```
