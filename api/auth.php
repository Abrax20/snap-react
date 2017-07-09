<?php
    // echo "hallo";
    require_once("config.php");
    global $mysql;
    global $main;
    require_once($main['system']);

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
    $auth = new Auth($pdo);

	if (isset($_POST)) {
        if (isset($_POST)) {
            if (@$_POST['mission'] == "login" && @$_POST['username'] != "" && @$_POST['password'] != "" && @$_POST['username'] != null && @$_POST['password'] != null) {
                $username = $_POST['username'];
                $password = md5($_POST['password']);
                $auth->login($username, $password, $deviceid, $deviceplatform, $devicemodel, $deviceversion);
            }
            if(isset($_POST['auth_token']) && @$_POST['auth_token'] != "" && @$_POST['mission'] == "check_auth_token" && @$_POST['auth_token'] != null) {
                $auth_token = $_POST['auth_token'];
                $auth->check_auth_token($auth_token, $deviceid, $deviceplatform, $devicemodel, $deviceversion);
            }
            if(isset($_POST['auth_token']) && @$_POST['auth_token'] != "" && @$_POST['mission'] == "logout" && @$_POST['auth_token'] != null) {
                $auth_token = $_POST['auth_token'];
                $auth->logout($auth_token, $deviceid, $deviceplatform, $devicemodel, $deviceversion);
            }
            if(@$_POST['mission'] == "register" && @$_POST['username'] != "" && @$_POST['email'] != "" && @$_POST['password'] != "" && @$_POST['username'] != null && @$_POST['email'] != null && @$_POST['password'] != null) {
                $username = $_POST['username'];
                $email = $_POST['email'] ;
                $password = md5($_POST['password']);
                $auth->register($username , $email, $password, $password);
            }
            if(@$_POST['mission'] == "check_register_pin" && @$_POST['pin'] != "" && @$_POST['pin'] != null ) {
                $pin = $_POST['pin'];
                $auth->check_register_pin($pin, $deviceid, $deviceplatform, $devicemodel, $deviceversion);
            }
        }
        $auth->echoanswerObj();
    }
