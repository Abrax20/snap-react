<?php
  $url = 'http://localhost/ypsart/tsv/';
  $domain = 'localhost';
  $mysql = [
    'adress' => 'localhost',
    'port' => '',
    'user' => 'root',
    'passwort' => '',
    'database' => 'tsv'
  ];
  $main = [
    'system' => 'api/main.php'
  ];
  $systemtable = [
    'variables' => ['tables'],
    'tables' => [
      'variables' => ['users', 'token'],
      'users' => [
        'variables' => ['id', 'name', 'password', 'permissions'],
        'id' => 'id',
        'name' => 'string',
        'password' => 'string',
        'permissions' => 'string',
      ],
      'token' => [
        'variables' => ['id', 'userId', 'auth_token'],
        'id' => 'id',
        'userId' => 'number',
        'auth_token' => 'string',
      ]
    ]
  ];
?>
