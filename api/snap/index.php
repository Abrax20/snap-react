<?php
    require_once('../../config.php');
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

    if(isset($_POST['auth_token']) && @$_POST['auth_token'] != "" && @$_POST['mission'] == "upload" && @$_POST['auth_token'] != null) {
        if($system->check_auth_token($_POST['auth_token'])){
            $userID = $system->getUserid($_POST['auth_token']);
     
            $uploaddir = '../uploads/snap/' . $userID;
            if(is_dir(!$uploaddir)) {  
                mkdir($uploaddir);
            }
            $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

                    echo '<pre>';
                    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                        echo "Datei ist valide und wurde erfolgreich hochgeladen.\n";
                    } else {
                        echo "Möglicherweise eine Dateiupload-Attacke!\n";
                    }

                    echo 'Weitere Debugging Informationen:';
                    print_r($_FILES);

                    print "</pre>";

        }
    }


?>