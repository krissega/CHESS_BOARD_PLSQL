<?php
$include_path="../";
require_once ($include_path."config/conn.php");
require_once ($include_path."database/db_game.php");
require_once ("cls_utils.php");

$cls_utils = new cls_utils();
$db_game = new db_game();
$action = $cls_utils->getreq("action");


if(!empty($action)) {

    switch ($action){

        case "registrar-usuarios":
        {
            $errors = array();
            $data = array();
            $required_fields = array("user1", "user2");            
            foreach ($required_fields as $param){
                if(!isset($_POST[$param]) || (isset($_POST[$param]) && in_array($param,$required_fields) &&  $_POST[$param] == "")){
                    $errors[] = 'Campo Requerido ' . $param;
                    //continue;
                }
            }

            if ( ! empty($errors)) {
                $data['success'] = false;
                $data['message']  = $errors;
            }
            else
            {
                $data['success'] = $db_game->create_game($_POST);
                $data['action'] = "play-game";
                $data['redirectto'] = 'play-game.php';
                $data['game'] = $db_game->get_recent_created_game();
            }

            header('Access-Control-Allow-Origin: *');
            header('Content-type: application/json');
            
            echo json_encode($data);
            

        }
        break;

    }
}