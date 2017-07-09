<?php
    require_once('../config.php');
    global $mysql;
    global $systemtable;
    global $main;
    require_once('../' . $main['system']);

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

    $setup->CreatSystemTabels();

    echo "Setup Completed";
