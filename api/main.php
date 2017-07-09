<?php
    /**
    *
    * namespace Abrax20\System;
    *
    * System class
    *
    * Class Documentation: none
    *
    * @author Christoph-Thomas Abs
    * @since 30.10.2011
    * @copyright Christoph-Thomas Abs - 2017-2030
    * @version 0.01
    * @license none
    *
    * Mysql boolean => 'true' : '1' und 'false' : '0'
    *
    */

    require_once("config.php");
    global $main;

    class System {
        public $pdo;
        public $accountvariables;
        public $networks;

        public function __construct($pdo=false, $accountvariables=false, $networks=false){
            $this->pdo = $pdo;
            $this->accountvariables = $accountvariables;
            $this->networks = $networks;
        }

        public function get_client_ip() {
            $ipaddress = '';
            if (isset($_SERVER['HTTP_CLIENT_IP']))
                $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
            else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            else if(isset($_SERVER['HTTP_X_FORWARDED']))
                $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
            else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
                $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
            else if(isset($_SERVER['HTTP_FORWARDED']))
                $ipaddress = $_SERVER['HTTP_FORWARDED'];
            else if(isset($_SERVER['REMOTE_ADDR']))
                $ipaddress = $_SERVER['REMOTE_ADDR'];
            else
                $ipaddress = 'UNKNOWN';
            return $ipaddress;
        }

        public function checkuser($id, $platform){
            $array = [];
            $query = $this->pdo->query("SELECT * FROM `sn_network_{$platform}` WHERE `{$platform}_id`='$id'");
            $control = 0;
            if($query){
                while($row = $query->fetch()){
                    $owner_id = $row['owner_id'];
                    $control++;
                }
                if($control > 0){
                    $query = $this->pdo->query("SELECT * FROM `sn_user_list` WHERE `id`=$owner_id");
                    if($query){
                        while($row = $query->fetch()){
                            $array['id'] = $row['id'];
                            $array['screen_name'] = $row['username'];
                            // $array['avatar'] = $row['avatar'];
                            // $array['description'] = $row['description'];
                        }
                        return $array[0] = true;
                    }
                }else{
                    return $array[0] = false;
                }
            }else{
                return $array[0] = false;
            }
        }

        public function getUserid($auth_token){
            if($this->pdo != false){
                $result = $this->pdo->query("SELECT * FROM sn_token_list WHERE auth_tocken='" . $auth_token . "'");
                if (!$result) {
                    return false;
                }else{
                    while($row = $result->fetch()){ $userid = $row['owner_id']; }
                    return $userid;
                }
            }else{
                return  false;
            }
        }


        public function check_auth_token($auth_token){

            if($this->pdo != false){
                $result = $this->pdo->query("SELECT * FROM sn_token_list WHERE auth_tocken='" . $auth_token . "'");
                if (!$result) {
                    return false;
                }else{
                    $control = 0;
                    while($row = $result->fetch()){ $control++; }
                    if($control > 0){
                        // $this->answer_obj['answer'] = "true";
                        return true;
                    }
                }
            }else{
                return false;
            }
        }

        public function getlistofaccountvariables($parameter){
            if($accountvariables != false){
                if($parameter == 0){
                    return $this->accountvariables['variables'];
                }
                if($parameter == 1){
                    return $this->accountvariables['account_settings'];
                }
                if($parameter == 2){
                    return $this->accountvariables['account_settings']['variables'];
                }
                if($parameter == 3){
                    return $this->accountvariables['account_variables'];
                }
                if($parameter == 4){
                    return $this->accountvariables['account_variables']['variables'];
                }
            }
        }

        public function convert_to_timeline($data){
            $array_1 = [];
            $array_2 = [];
            $unixTime = time();
		    $unixTime_obj = getdate($unixTime);
            for($i=0;count($data) > $i;$i++){
                $created_at = $data[$i]['created_at'];
                if(in_array($data[$i]['created_at'], $array_1)){
                    $created_at++;
                    $array_1[$created_at][] = $data[$i];
                }else{
                    $array_1[$created_at] = $data[$i];
                }
            }
            foreach ($array_1 as $key => $value) {
                $array_2[] = $array_1[$key];
            }
            return $array_2;
        }
        public function CheckMysqlTable(string $table_name){
            $query = $this->pdo->query("SHOW TABLES LIKE '$table_name'");
            if($query->fetchColumn() > 0){
                return true;
            }else{
                return false;
            }
        }

        public function CreateMysqlTable(string $table_name, $data, $use_template=true){
            print_r($table_name);
            print_r($data);
            print_r("<br>");
            print_r("<br>");
            print_r("<br>");
            print_r("<br>");
            if($this->pdo != false){
                $query_string = 'CREATE TABLE IF NOT EXISTS `' . $table_name . '` (';
                if($use_template == true){
                    // $query_string .= '`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,';
                    // $query_string .= '`owner_id` INT NOT NULL,';
                }
                // if(isset($data{'variables'}) != true){
                    for($x=0; count($data['variables']) > $x; $x++){
                        if(count($data['variables'])-1 == $x){
                            if(@$data[$data['variables'][$x]] == 'id'){
                                $query_string .=  '`' . $data['variables'][$x] . '` INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
                            }
                            if(@$data[$data['variables'][$x]] == 'string'){
                                $query_string .=  '`' . $data['variables'][$x] . '` TEXT';
                            }
                            if(@$data[$data['variables'][$x]] == 'number'){
                                $query_string .=  '`' . $data['variables'][$x] . '` INT';
                            }
                            if(@$data[$data['variables'][$x]] == 'boolean'){
                                $query_string .=  '`' . $data['variables'][$x] . '` BOOL';
                            }
                        }else {
                            if(@$data[$data['variables'][$x]] == 'id'){
                                $query_string .=  '`' . $data['variables'][$x] . '` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, ';
                            }
                            if(@$data[$data['variables'][$x]] == 'string'){
                                $query_string .=  '`' . $data['variables'][$x] . '` TEXT, ';
                            }
                            if(@$data[$data['variables'][$x]] == 'number'){
                                $query_string .=  '`' . $data['variables'][$x] . '` INT, ';
                            }
                            if(@$data[$data['variables'][$x]] == 'boolean'){
                                $query_string .=  '`' . $data['variables'][$x] . '` BOOL, ';
                            }
                        }
                    }
                // }
                $query_string .= ')';
                // echo $query_string;
                // $this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
                $result = $this->pdo->exec($query_string);
                // print_r($res ult->errorInfo());
            }else{
                return false;
            }
        }

    }

    class Auth {
        public $answer_obj = [
            'answer' => false,
        ];
        public $pdo;

        public function __construct($pdo){
            $this->pdo = $pdo;
        }

        public function echoanswerObj(){
            echo json_encode($this->answer_obj);
        }

        public function getanswerObj(){
            return $this->answer_obj;
        }

        public function getanswerString(){
            return json_encode($this->answer_obj);
        }

        public function creatuseraccount(){
        }

        private function new_Tocken_checker($token, $pdo){
            $control = 0;
            $token_query = $pdo->query("SELECT * FROM sn_token_list WHERE auth_tocken='{$token}'");
            while($row = $token_query->fetch()){ $control++; }
            if($control == 0 || $control < 1){
                return true;
            }else{
                return false;
            }
        }

        private function new_register_pin_checker($pin, $pdo){
            $control = 0;
            $token_query = $pdo->query("SELECT * FROM sn_register_pin_list WHERE pin='{$pin}'");
            while($row = $token_query->fetch()){ $control++; }
            if($control == 0 || $control < 1){
                return true;
            }else{
                return false;
            }
        }

        public function login($username, $password) {
                $auth_query = $this->pdo->query("SELECT * FROM sn_user_list WHERE username='{$username}' AND password='{$password}'");
                $control = 0;
                while($row = $auth_query->fetch()){ $control++; }
            	if($control == 1){
                    foreach ($auth_query = $this->pdo->query("SELECT * FROM sn_user_list WHERE username='{$username}' AND password='{$password}'") as $row) { $userid = $row["id"]; }
                    $token = bin2hex(openssl_random_pseudo_bytes(64));
                    while($this->new_Tocken_checker($token, $this->pdo) != true){
                        $token = bin2hex(openssl_random_pseudo_bytes(64));
                    }

                    $token_insert_query = $this->pdo->query("INSERT INTO `sn_token_list` (`owner_id`, `auth_tocken`) VALUES ('{$userid}', '{$token}')");

                    $this->answer_obj['auth_token'] = $token;
                    $this->answer_obj['answer'] = "true";
                }elseif($control > 1){
                    $this->answer_obj['error']['error_type'] = "server";
                    $this->answer_obj['error']['error_number'] = "102";
                    $this->answer_obj['error']['error_message'] = "Internal Backend Error 102";
                }
            // }
        }

        public function register($username, $email, $password, $repeated_password){
            // if($username != null && $email != null && $password != null && $repeated_password != null){
                if($password == $repeated_password){
                    $result = $this->pdo->query("SELECT * FROM sn_user_list WHERE username='{$username}'");
                    if (!$result) {
                        $this->answer_obj['error']['error_type'] = "server";
                        $this->answer_obj['error']['error_number'] = "104";
                        $this->answer_obj['error']['error_message'] = "Internal Backend Error 104";
                    }else{
                        $control = 0;
                        while($row = $result->fetch()){ $control++; }
                        if($control > 0){
                            $this->answer_obj['error']['error_type'] = "server";
                            $this->answer_obj['error']['error_number'] = "105";
                            $this->answer_obj['error']['error_message'] = "Internal Backend Error 105";
                        }else{
                            $result = $this->pdo->query("SELECT * FROM sn_user_list WHERE email='{$email}'");
                            if (!$result) {
                                $this->answer_obj['error']['error_type'] = "server";
                                $this->answer_obj['error']['error_number'] = "106";
                                $this->answer_obj['error']['error_message'] = "Internal Backend Error 106";
                            }else{
                                $control = 0;
                                while($row = $result->fetch()){ $control++; }
                                if($control > 0){
                                    $this->answer_obj['error']['error_type'] = "server";
                                    $this->answer_obj['error']['error_number'] = "107";
                                    $this->answer_obj['error']['error_message'] = "Internal Backend Error 107";
                                }else{
                                $pin = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
                                while($this->new_register_pin_checker($pin, $this->pdo) != true){
                                    $pin = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
                                }

                                    // $to = $email;
                                    // $subject = "Register";

                                    // $message = "<div style=\"width:100%;min-height:100%;background-color:" . $main['color']['background'] . ";text-align:center\">";
                                    // $message .= "<p style=\"color:" . $main['color']['h2'] . "\">Plese Confirming 3 days after your Registered you account with this Pin in the app</p>";
                                    // $message .= "<h2 style=\"color:" . $main['color']['h2'] . "\">" . $pin . "</h2>";
                                    // $message .= "</div>";

                                    // $header = "From:abs.christoh@googlemail.com \r\n";
                                    // // $header .= "Cc:afgh@somedomain.com \r\n";
                                    // $header .= "MIME-Version: 1.0\r\n";
                                    // $header .= "Content-type: text/html\r\n";
                                    // $retval = mail ($to,$subject,$message,$header);
                                    $this->answer_obj['pin'] = "Line 136 is to edit " . $pin;
                                    $retval = true;
                                    if( $retval == true ) {
                                        $this->creatuseraccount();
                                        $time = date("d.m.Y H:i.s");
                                        $user_insert_query = $this->pdo->query("INSERT INTO `sn_user_list` (`email`, `username`, `password`, `created_on`, `Confirmed`) VALUES ('{$email}', '{$username}', '{$password}', '{$time}', 'false')");
                                        $userid = $this->pdo->lastInsertId();
                                        $register_pin_insert_query = $this->pdo->query("INSERT INTO `sn_register_pin_list` (`owner_id`, `pin`) VALUES ('{$userid}', '{$pin}')");
                                        $this->answer_obj['answer'] = "true";

                                    }else {
                                        $this->answer_obj['error']['error_type'] = "server";
                                        $this->answer_obj['error']['error_number'] = "108";
                                        $this->answer_obj['error']['error_message'] = "Internal Backend Error 108";
                                    }
                                }
                            }
                        }
                    }
                }else{
                    $this->answer_obj['error']['error_type'] = "user";
                    $this->answer_obj['error']['error_number'] = "101";
                    $this->answer_obj['error']['error_message'] = "Frontend Error 101";
                }
            // }
        }

        public function check_auth_token($auth_token){
                $result = $this->pdo->query("SELECT * FROM sn_token_list WHERE `auth_tocken`='" . $auth_token . "'");
                if (!$result) {
                    $this->answer_obj['error']['error_type'] = "Server";
                    $this->answer_obj['error']['error_number'] = "103";
                    $this->answer_obj['error']['error_message'] = "Internal Backend Error 103";
                }else{
                    $control = 0;
                    while($row = $result->fetch()){ $control++; }
                    if($control > 0){
                        $this->answer_obj['answer'] = "true";
                    }
                }
            // }
        }

        public function logout($auth_token){
                $result = $this->pdo->query("SELECT * FROM `sn_token_list` WHERE `auth_tocken`='" . $auth_token . "'");
                if (!$result) {
                    $this->answer_obj['error']['error_type'] = "Server";
                    $this->answer_obj['error']['error_number'] = "112";
                    $this->answer_obj['error']['error_message'] = "Internal Backend Error 112";
                }else{
                    $control = 0;
                    while($row = $result->fetch()){ $control++; }
                    if($control > 0){
                        $delete_resulte = $this->pdo->query("DELETE * FROM `sn_token_list` WHERE `auth_tocken`='" . $auth_token . "'");
                        $this->answer_obj['answer'] = "true";
                    }
                }
            // }
        }

        public function check_register_pin($pin){
                $result = $this->pdo->query("SELECT * FROM sn_register_pin_list WHERE pin='{$pin}'");
                if (!$result) {
                    $this->answer_obj['error']['error_type'] = "Server";
                    $this->answer_obj['error']['error_number'] = "109";
                    $this->answer_obj['error']['error_message'] = "Internal Backend Error 109";
                }
                $control = 0;
                while($row = $result->fetch()){ $control++; }
            	if($control > 0){
                    foreach ($register_pin_user_id_query = $this->pdo->query("SELECT * FROM sn_register_pin_list WHERE pin='{$pin}'") as $row) {
                        $userid = $row["owner_id"];
                    }

                    $token = bin2hex(openssl_random_pseudo_bytes(64));
                    while($this->new_Tocken_checker($token, $this->pdo) != true){
                        $token = bin2hex(openssl_random_pseudo_bytes(64));
                    }

                    $token_insert_query = $this->pdo->query("INSERT INTO `sn_token_list` (`owner_id`, `auth_tocken`) VALUES ('{$userid}', '{$token}')");

                    $this->answer_obj['auth_token'] = $token;

                    $register_pin_update_user_list_confrimet_status = $this->pdo->query("UPDATE `sn_user_list` SET `Confirmed`='true' WHERE id='{$userid}'");

                    $register_pin_delete_pin_query = $this->pdo->query("DELETE FROM `sn_register_pin_list` WHERE pin='{$pin}'");

                    $this->answer_obj['answer']= true;
                }else{
                    $this->answer_obj['error']['error_type'] = "Server";
                    $this->answer_obj['error']['error_number'] = "110";
                    $this->answer_obj['error']['error_message'] = "Internal Backend Error 110";
                }
            // }
        }

    }

    class Setup {
        public $pdo;
        public $system_class;
        public $systemtable;
        public $answer_obj = [
            'answer' => false,
        ];

        public function __construct($pdo, $systemtable){
            $this->pdo = $pdo;
            $this->systemtable = $systemtable;
            $this->system_class = new System($this->pdo);
        }

        public function CreatSystemTabels(){
            $tables_titles = $this->systemtable['tables']['variables'];
            $tables_obj = $this->systemtable['tables'];
            for($x=0; count($tables_titles) > $x; $x++){
                $array = [];
                for($y=0; count($tables_obj[$tables_titles[$x]]['variables']) > $y; $y++){
                    $var_name = $tables_obj[$tables_titles[$x]]['variables'][$y];
                    $array['variables'][] = $tables_obj[$tables_titles[$x]]['variables'][$y];
                    $array[$var_name] = $tables_obj[$tables_titles[$x]][$var_name];
                }
                // if($x == 0){
                    // print_r($array);
                // }

                $this->system_class->CreateMysqlTable($tables_titles[$x], $array);
            }
        }

    }
