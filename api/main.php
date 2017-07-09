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
    * @since 30.03.2017
    * @copyright Christoph-Thomas Abs - 2017-2030
    * @version 0.01
    * @license none
    *
    * Mysql boolean => 'true' : '1' und 'false' : '0'
    *
    */

    // require_once("../config.php");
    // global $main;

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
                $query_string .= ')';
                echo 'Result: ' . $result = $this->pdo->exec($query_string);
                echo 'Error: '; print_r($this->pdo->errorInfo());

            }else{
                return false;
            }
        }

        public function ServerRequest($url, $request_type, $data){
          if (!function_exists('curl_init')){
              die('Sorry cURL is not installed!');
          }
          if($request_type == 'post'){
            $data_string = json_encode($data);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $output = curl_exec($ch);
            curl_close($ch);
            return $output;
          }
          if($request_type == 'get'){
            $data_string = '?';
            for($i=0;count($data['variables'])>$i;$i++){
              if($i != 0){
                $data_string = $data_string . '&' . urlencode($data['variables'][$i]) . urlencode($data[$data['variables'][$i]]);
              }else{
                $data_string = $data_string . urlencode($data['variables'][$i]) . urlencode($data[$data['variables'][$i]]);
              }
            }
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url . $data_string);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $output = curl_exec($ch);
            curl_close($ch);
            return $output;
          }
          return false;
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

        public function CreateMysqlTable(){
            $tables_titles = $this->systemtable['tables']['variables'];
            $tables_obj = $this->systemtable['tables'];
            for($x=0; count($tables_titles) > $x; $x++){
                $array = [];
                for($y=0; count($tables_obj[$tables_titles[$x]]['variables']) > $y; $y++){
                    $var_name = $tables_obj[$tables_titles[$x]]['variables'][$y];
                    $array['variables'][] = $tables_obj[$tables_titles[$x]]['variables'][$y];
                    $array[$var_name] = $tables_obj[$tables_titles[$x]][$var_name];
                }
                $this->system_class->CreateMysqlTable($tables_titles[$x], $array);
            }
        }

    }

    class Request_Controller{

        public $table_name;
        public $pdo;
        public $HandlerID;
        public function __construct($pdo, $HandlerID, $table_name=null){
            $this->pdo = $pdo;
            $this->table_name = $table_name;
            $this->HandlerID = $HandlerID;
        }

        public function CheckRequest($owner_id, $max_min_request, $table_name=null){
            if($table_name == null){
                if($this->table_name == null){
                    return false;
                }else{
                    $table_name = $this->table_name;
                }
            }
            $query = $this->pdo->query("SELECT * FROM `$table_name` WHERE `owner_id`=`$owner_id`");
            $control = 0;
            while($row = $query->fetch()){
                $last_request_counter = $row['last_' . $this->HandlerID . '_counter'];
                $last_request_timestamp = $row['last_' . $this->HandlerID . '_timestamp'];
                $gloable_request_counter = $row['gloable_' . $this->HandlerID .'_counter'];
                $control++;
            }
            $last_request_counter = intval($last_request_counter);
            $gloable_request_counter = intval($gloable_request_counter);
            if($control > 0){
                $unixTime = time();
				$unixTime_obj = getdate($unixTime);
				$last_request_timestamp_obj = json_decode($last_request_timestamp, true);
				if($last_request_timestamp_obj['yday'] == $unixTime_obj['yday'] && $last_request_timestamp_obj['year'] == $unixTime_obj['year'] && ($last_request_timestamp_obj['minutes'] - $unixTime_obj['minutes']) < 1){
                    if($last_request_counter > $max_min_request){
                        return false;
                    }else{
                        return true;
                    }
                }else{
                    return true;
                }
            }else{
                return true;
            }
        }

        public function loggerRequest($owner_id, $max_min_request, $table_name=null){
            if($table_name == null){
                if($this->table_name == null){
                    return false;
                }else{
                    $table_name = $this->table_name;
                }
            }
            $query = $this->pdo->query("SELECT * FROM `$table_name` WHERE `owner_id`=`$owner_id`");
            $control = 0;
            while($row = $query->fetch()){
                $last_request_counter = $row['last_' . $this->HandlerID . '_counter'];
                $last_request_timestamp = $row['last_' . $this->HandlerID . '_timestamp'];
                $gloable_request_counter = $row['gloable_' . $this->HandlerID .'_counter'];
                $control++;
            }
            $last_request_counter = intval($last_request_counter);
            $gloable_request_counter = intval($gloable_request_counter);
            if($control > 0){
              $unixTime = time();
			  $unixTime_obj = getdate($unixTime);
              $unixTime_string = json_encode($unixTime_obj);
              $gloable_request_counter++;
              $last_request_counter++;
              $token_insert_query = $this->pdo->query("UPDATE `twitter_counter` SET `last_timeline_counter`=$last_request_counter, `last_timeline_timestamp`=`$unixTime_string`, `gloable_timeline_counter`=$gloable_request_counter WHERE `owner_id`=$owner_id");
              return true;
            }else{
                $unixTime = time();
			    $unixTime_obj = getdate($unixTime);
                $unixTime_string = json_encode($unixTime_obj);
                $token_insert_query = $this->pdo->query("INSERT INTO `$table_name` (`owner_id`, `last_{$this->HandlerID}_counter`, `last_{$this->HandlerID}_timestamp`, `gloable_{$this->HandlerID}_counter`) VALUES ('{$owner_id}', '1', '{$unixTime_string}', '1')");
                return true;
            }
        }

        public function resetRequest($owner_id, $max_min_request, $table_name=null){
            if($table_name == null){
                if($this->table_name == null){
                    return false;
                }else{
                    $table_name = $this->table_name;
                }
            }
            $query = $this->pdo->query("SELECT * FROM `$table_name` WHERE `owner_id`=`$owner_id`");
            $control = 0;
            while($row = $query->fetch()){
                $last_request_counter = $row['last_' . $this->HandlerID . '_counter'];
                $last_request_timestamp = $row['last_' . $this->HandlerID . '_timestamp'];
                $gloable_request_counter = $row['gloable_' . $this->HandlerID .'_counter'];
                $control++;
            }
            $last_request_counter = intval($last_request_counter);
            $gloable_request_counter = intval($gloable_request_counter);
            if($control > 0){
              $unixTime = time();
			  $unixTime_obj = getdate($unixTime);
              $unixTime_string = json_encode($unixTime_obj);
              $gloable_request_counter++;
              $last_request_counter = 0;
              $token_insert_query = $this->pdo->query("UPDATE `twitter_counter` SET `last_timeline_counter`=$last_request_counter, `last_timeline_timestamp`=`$unixTime_string`, `gloable_timeline_counter`=$gloable_request_counter WHERE `owner_id`=$owner_id");
              return true;
            }else{
                $unixTime = time();
			    $unixTime_obj = getdate($unixTime);
                $unixTime_string = json_encode($unixTime_obj);
                $token_insert_query = $this->pdo->query("INSERT INTO `$table_name` (`owner_id`, `last_{$this->HandlerID}_counter`, `last_{$this->HandlerID}_timestamp`, `gloable_{$this->HandlerID}_counter`) VALUES ('{$owner_id}', '1', '{$unixTime_string}', '1')");
                return true;
            }
        }

        public function HandelRequest($owner_id, $max_min_request, $table_name=null){
            if($table_name == null){
                if($this->table_name == null){
                    return false;
                }else{
                    $table_name = $this->table_name;
                }
            }
            $query = $this->pdo->query("SELECT * FROM `$table_name` WHERE `owner_id`=$owner_id");
            $control = 0;
            while($row = $query->fetch()){
                $control++;
                $last_request_counter = intval($row['last_' . $this->HandlerID . '_counter']);
                $last_request_timestamp = $row['last_' . $this->HandlerID . '_timestamp'];
                $gloable_request_counter = intval($row['gloable_' . $this->HandlerID .'_counter']);
            }
            if($control > 0){
                $unixTime = time();
				$unixTime_obj = getdate($unixTime);
				$last_request_timestamp_obj = json_decode($last_request_timestamp, true);
				if($last_request_timestamp_obj['yday'] == $unixTime_obj['yday'] && $last_request_timestamp_obj['year'] == $unixTime_obj['year']){
                    if($unixTime_obj['minutes'] == $last_request_timestamp_obj['minutes']){
                        if($last_request_counter > $max_min_request){
                            return false;
                        }else{
                            $unixTime = time();
			                $unixTime_obj = getdate($unixTime);
                            $unixTime_string = json_encode($unixTime_obj);
                            $gloable_request_counter++;
                            $last_request_counter++;
                            $token_insert_query = $this->pdo->query("UPDATE `twitter_counter` SET `last_timeline_counter`='{$last_request_counter}', `last_timeline_timestamp`='{$unixTime_string}', `gloable_timeline_counter`='{$gloable_request_counter}' WHERE `owner_id`=$owner_id");
                            return true;
                        }
                    }else{
                        if($unixTime_obj['minutes'] == ($last_request_timestamp_obj['minutes'] + 1)){
                            if(($unixTime_obj['seconds'] - $last_request_timestamp_obj['seconds']) < 60){
                                if($last_request_counter > $max_min_request){
                                    return false;
                                }else{
                                    $unixTime_string = $last_request_timestamp;
                                    $gloable_request_counter++;
                                    $last_request_counter++;
                                    $token_insert_query = $this->pdo->query("UPDATE `twitter_counter` SET `last_timeline_counter`='{$last_request_counter}', `last_timeline_timestamp`='{$unixTime_string}', `gloable_timeline_counter`='{$gloable_request_counter}' WHERE `owner_id`=$owner_id");
                                    return true;
                                }
                            }else{
                                $unixTime = time();
                                $unixTime_obj = getdate($unixTime);
                                $unixTime_string = json_encode($unixTime_obj);
                                $gloable_request_counter++;
                                $last_request_counter = 1;
                                $token_insert_query = $this->pdo->query("UPDATE `twitter_counter` SET `last_timeline_counter`='{$last_request_counter}', `last_timeline_timestamp`='{$unixTime_string}', `gloable_timeline_counter`='{$gloable_request_counter}' WHERE `owner_id`=$owner_id");
                                return true;
                            }
                        }else{
                            $unixTime = time();
                            $unixTime_obj = getdate($unixTime);
                            $unixTime_string = json_encode($unixTime_obj);
                            $gloable_request_counter++;
                            $last_request_counter = 1;
                            $token_insert_query = $this->pdo->query("UPDATE `twitter_counter` SET `last_timeline_counter`='{$last_request_counter}', `last_timeline_timestamp`='{$unixTime_string}', `gloable_timeline_counter`='{$gloable_request_counter}' WHERE `owner_id`=$owner_id");
                            return true;
                        }
                    }
                }else{
                    $unixTime = time();
			        $unixTime_obj = getdate($unixTime);
                    $unixTime_string = json_encode($unixTime_obj);
                    $gloable_request_counter++;
                    $last_request_counter = 1;
                    $token_insert_query = $this->pdo->query("UPDATE `twitter_counter` SET `last_timeline_counter`='{$last_request_counter}', `last_timeline_timestamp`='{$unixTime_string}', `gloable_timeline_counter`='{$gloable_request_counter}' WHERE `owner_id`=$owner_id");
                    return true;
                }
            }else{
                $unixTime = time();
			    $unixTime_obj = getdate($unixTime);
                $unixTime_string = json_encode($unixTime_obj);
                $token_insert_query = $this->pdo->query("INSERT INTO `$table_name` (`owner_id`, `last_{$this->HandlerID}_counter`, `last_{$this->HandlerID}_timestamp`, `gloable_{$this->HandlerID}_counter`) VALUES ('{$owner_id}', '1', '{$unixTime_string}', '1')");
                return true;
            }
        }
    }
