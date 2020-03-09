<?php
$include_path = "../";
require_once($include_path . "config/conn.php");
require_once($include_path . "database/db_game.php");
require_once("cls_utils.php");

$cls_utils = new cls_utils();
$db_game = new db_game();
$action = $cls_utils->getreq("action");


if (!empty($action)) {

    switch ($action) {

        case "cargar-tablero": {
                $errors = array();
                $data = array();
                $required_fields = array("idgame");
                foreach ($required_fields as $param) {
                    if (!isset($_POST[$param]) || (isset($_POST[$param]) && in_array($param, $required_fields) &&  $_POST[$param] == "")) {
                        $errors[] = 'Campo Requerido ' . $param;
                        //continue;
                    }
                }

                if (!empty($errors)) {
                    $data['success'] = false;
                    $data['message']  = $errors;
                } else {
                    $data['success'] = true;
                    $data['tokens'] = $db_game->cargar_tablero($_POST);
                    $data['action'] = "cargar-tablero";
                }

                header('Access-Control-Allow-Origin: *');
                header('Content-type: application/json');
                echo json_encode($data);
            }
            break;

        case "cargar-datos-players": {
                $errors = array();
                $data = array();
                $required_fields = array("idgame");
                foreach ($required_fields as $param) {
                    if (!isset($_POST[$param]) || (isset($_POST[$param]) && in_array($param, $required_fields) &&  $_POST[$param] == "")) {
                        $errors[] = 'Campo Requerido ' . $param;
                        //continue;
                    }
                }

                if (!empty($errors)) {
                    $data['success'] = false;
                    $data['message']  = $errors;
                } else {
                    $data['success'] = true;
                    $data['players'] = $db_game->cargar_datos_players($_POST);
                    $data['action'] = "cargar-datos-players";
                }

                header('Access-Control-Allow-Origin: *');
                header('Content-type: application/json');
                echo json_encode($data);
            }
            break;

        case "obtener-jugador-activo": {
                $errors = array();
                $data = array();
                $required_fields = array("idgame");
                foreach ($required_fields as $param) {
                    if (!isset($_POST[$param]) || (isset($_POST[$param]) && in_array($param, $required_fields) &&  $_POST[$param] == "")) {
                        $errors[] = 'Campo Requerido ' . $param;
                        //continue;
                    }
                }

                if (!empty($errors)) {
                    $data['success'] = false;
                    $data['message']  = $errors;
                } else {
                    $data['success'] = true;
                    $data['activeplayer'] = $db_game->get_active_player($_POST);
                    $data['action'] = "obtener-jugador-activo";
                }

                header('Access-Control-Allow-Origin: *');
                header('Content-type: application/json');
                echo json_encode($data);
            }
            break;

        case "send-play": {
                $errors = array();
                $data = array();

                $required_fields = array("idgame", "coordInit", "coordFin", "colCordIni", "filCordIni", "colCordFin", "colCordFin");
                foreach ($required_fields as $param) {
                    if (!isset($_POST[$param]) || (isset($_POST[$param]) && in_array($param, $required_fields) &&  $_POST[$param] == "")) {
                        $errors[] = 'Campo Requerido ' . $param;
                        //continue;
                    }
                }

                if (!empty($errors)) {
                    $data['success'] = false;
                    $data['message']  = $errors;
                } else {
                    $data['success'] = true;
                    $data['actionpermitted'] = $db_game->realizarJugada($_POST);
                    $data['action'] = "send-play";
                }

                header('Access-Control-Allow-Origin: *');
                header('Content-type: application/json');
                echo json_encode($data);
            }
            break;

        case "list-games": {
                $errors = array();
                $data = array();
                $required_fields = array();
                foreach ($required_fields as $param) {
                    if (!isset($_POST[$param]) || (isset($_POST[$param]) && in_array($param, $required_fields) &&  $_POST[$param] == "")) {
                        $errors[] = 'Campo Requerido ' . $param;
                        //continue;
                    }
                }

                if (!empty($errors)) {
                    $data['success'] = false;
                    $data['message']  = $errors;
                } else {
                    $data['success'] = true;
                    $data['games'] = $db_game->listar_juegos();
                    $data['action'] = "listar-juegos";
                }

                header('Access-Control-Allow-Origin: *');
                header('Content-type: application/json');
                echo json_encode($data);
            }
            break;

        case "listar-estadisticas": {
                $errors = array();
                $data = array();
                $required_fields = array();
                foreach ($required_fields as $param) {
                    if (!isset($_POST[$param]) || (isset($_POST[$param]) && in_array($param, $required_fields) &&  $_POST[$param] == "")) {
                        $errors[] = 'Campo Requerido ' . $param;
                        //continue;
                    }
                }

                if (!empty($errors)) {
                    $data['success'] = false;
                    $data['message']  = $errors;
                } else {
                    $data['success'] = true;
                    $data['estadisticas'] = $db_game->listar_estadistica_juegos();
                    $data['action'] = "listar-juegos";
                }

                header('Access-Control-Allow-Origin: *');
                header('Content-type: application/json');
                echo json_encode($data);
            }
            break;

        case "confirmar-empate": {
                $errors = array();
                $data = array();
                $required_fields = array('idgame');
                foreach ($required_fields as $param) {
                    if (!isset($_POST[$param]) || (isset($_POST[$param]) && in_array($param, $required_fields) &&  $_POST[$param] == "")) {
                        $errors[] = 'Campo Requerido ' . $param;
                        //continue;
                    }
                }

                if (!empty($errors)) {
                    $data['success'] = false;
                    $data['message']  = $errors;
                } else {
                    $data['success'] = true;
                    $data['empate'] = $db_game->insertar_empate($_POST);
                    $data['action'] = "empate";
                }

                header('Access-Control-Allow-Origin: *');
                header('Content-type: application/json');
                echo json_encode($data);
            }
            break;
    }
}