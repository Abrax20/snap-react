<?php
  require_once('../../config.php');
  global $mysql;
  global $systemtable;
  require_once('../main.php');

  $pdo = new PDO('mysql:host=' . $mysql["adress"] . $mysql["port"] .';dbname=' . $mysql["database"], $mysql["user"], $mysql["passwort"]);

  if(!$pdo){
      $anwer_obj = [
          'answer' => false,
          'error' => [
              'error_type' => 'server',
              'error_number' => '101',
              'error_message' => 'Internal Backend Error 101'
          ]
      ];
      $anwer_string = json_encode($anwer_obj);
      die($anwer_string);
  }

  $system = new System($pdo);
  $setup = new Setup($pdo, $systemtable);
  $setup->CreateMysqlTable();

  $name = 'Gaby';
  $password = md5('Tanz17');
  $permissions = '{"read":["tanz"]}';
  $string = "INSERT INTO `users` (`id`, `name`, `password`, `permissions`) VALUES (NULL, '$name', '$password', '$permissions')";
  $pdo->query($string);

  $name = 'Ingo';
  $password = md5('Aktive17');
  $permissions = '{"read":["teamleiter"]}';
  $string = "INSERT INTO `users` (`id`, `name`, `password`, `permissions`) VALUES (NULL, '$name', '$password', '$permissions')";
  $pdo->query($string);
  echo 'true';
