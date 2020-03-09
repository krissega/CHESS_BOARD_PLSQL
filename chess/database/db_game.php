<?php

require_once("db_database.php");


class db_game extends db_database
{

    public function __construct()
    { }

    public function create_game($params)
    {
        $conn = $this->db_connect();

        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        $sql = 'BEGIN createCompleteGame(:namePlayerOne, :namePlayerTwo); END;';

        $stmt = oci_parse($conn, $sql);

        //  Bind the input parameter
        oci_bind_by_name($stmt, ':namePlayerOne', $params['user1'], 64);

        // Bind the output parameter
        oci_bind_by_name($stmt, ':namePlayerTwo', $params['user2'], 64);

        $status = oci_execute($stmt);

        oci_free_statement($stmt);
        oci_close($conn);

        return $status;
    }

    public function get_recent_created_game()
    {
        $conn = $this->db_connect();

        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        $sql = 'select GETLASTGAMEID as lastgameid from dual';

        $stmt = oci_parse($conn, $sql);
        oci_execute($stmt);
        $row = oci_fetch_array($stmt, OCI_ASSOC);
        oci_free_statement($stmt);
        oci_close($conn);
        return $row;
    }

    public function cargar_tablero($params)
    {
        $conn = $this->db_connect();

        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        $sql = 'select TBL_TABLERO.*, TBL_FICHAS.*
        from TBL_TABLERO
        join TBL_FICHAS
        on TBL_TABLERO.ID_FICHA = TBL_FICHAS.ID
        where TBL_TABLERO.id_juego = :idjuego';

        $stmt = oci_parse($conn, $sql);
        //  Bind the input parameter
        oci_bind_by_name($stmt, ':idjuego', $params['idgame'], 8);

        oci_execute($stmt);

        $data = array();

        while (($row = oci_fetch_array($stmt, OCI_ASSOC))) {
            array_push($data, $row);
        }
        oci_free_statement($stmt);
        oci_close($conn);
        return $data;
    }

    public function cargar_datos_players($params)
    {
        $conn = $this->db_connect();

        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        $sql = 'select TBL_PLAYERS.ID, TBL_PLAYERS.NOMBRE, TBL_JUEGO.PLAYER_WHITE, TBL_JUEGO.PLAYER_BLACK 
        from TBL_JUEGO
        join TBL_PLAYERS
        on TBL_JUEGO.PLAYER_WHITE = TBL_PLAYERS.ID OR TBL_JUEGO.PLAYER_BLACK = TBL_PLAYERS.ID
        where TBL_JUEGO.ID = :idjuego';

        $stmt = oci_parse($conn, $sql);
        //  Bind the input parameter
        oci_bind_by_name($stmt, ':idjuego', $params['idgame'], 8);

        oci_execute($stmt);

        $data = array();

        while (($row = oci_fetch_array($stmt, OCI_ASSOC))) {
            array_push($data, $row);
        }
        oci_free_statement($stmt);
        oci_close($conn);
        return $data;
    }

    public function get_active_player($params)
    {

        $conn = $this->db_connect();

        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        $sql = "SELECT DISTINCT(TBL_JUEGO.ACTIVE_PLAYER),
                CASE TBL_JUEGO.ACTIVE_PLAYER WHEN TBL_JUEGO.PLAYER_WHITE THEN 'white'
                 WHEN TBL_JUEGO.PLAYER_BLACK THEN 'black'
                 END color
        from TBL_JUEGO
        join TBL_PLAYERS
        on TBL_JUEGO.PLAYER_WHITE = TBL_PLAYERS.ID OR TBL_JUEGO.PLAYER_BLACK = TBL_PLAYERS.ID
        where TBL_JUEGO.ID = :idjuego";



        $stmt = oci_parse($conn, $sql);

        //  Bind the input parameter
        oci_bind_by_name($stmt, ':idjuego', $params['idgame'], 8);

        oci_execute($stmt);

        $data = array();

        $row = oci_fetch_array($stmt, OCI_ASSOC);
        oci_free_statement($stmt);
        oci_close($conn);

        return $row;
    }

    public function realizarJugada($params)
    {
        $conn = $this->db_connect();

        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        $sql = 'BEGIN TOKENS_MOV_RULES_PROC(:idgame, :filCordIni, :colCordIni, :filCordFin, :colCordFin, :result); END;';
        //$sql = "select TOKENS_MOV_RULES(:idgame, :filCordIni, :colCordIni, :filCordFin, :colCordFin) res from dual";
        //$sql = "select TOKENS_MOV_RULES(5, 2, 'F', 3, 'F') res from dual";

        $stmt = oci_parse($conn, $sql);


        //  Bind the input parameter
        oci_bind_by_name($stmt, ':idgame', $params['idgame']);
        oci_bind_by_name($stmt, ':filCordIni', $params['filCordIni']);
        oci_bind_by_name($stmt, ':colCordIni', $params['colCordIni']);
        oci_bind_by_name($stmt, ':filCordFin', $params['filCordFin']);
        oci_bind_by_name($stmt, ':colCordFin', $params['colCordFin']);
        oci_bind_by_name($stmt, ':result', $result);


        oci_execute($stmt);
        //$row = oci_fetch_array($stmt, OCI_ASSOC);
        // $row = oci_fetch_array($stmt, OCI_BOTH);

        oci_free_statement($stmt);
        oci_close($conn);

        return $result;
    }

    function listar_juegos()
    {
        $conn = $this->db_connect();

        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        $sql = 'select TBL_JUEGO.*, player1.NOMBRE nombrewhite, player2.NOMBRE nombreblack, TBL_GAME_STATUS.ESTADO, TBL_GAME_STATUS.WINNER
from TBL_JUEGO
join TBL_PLAYERS player1
on TBL_JUEGO.PLAYER_WHITE = player1.ID
join TBL_PLAYERS player2
on TBL_JUEGO.PLAYER_BLACK = player2.ID
left join TBL_GAME_STATUS
on TBL_JUEGO.ID = TBL_GAME_STATUS.GAME_ID
order by FECHA_INICIO DESC';

        $stmt = oci_parse($conn, $sql);

        oci_execute($stmt);

        $data = array();

        while (($row = oci_fetch_array($stmt, OCI_ASSOC))) {
            array_push($data, $row);
        }
        oci_free_statement($stmt);
        oci_close($conn);
        return $data;
    }

    function listar_estadistica_juegos()
    {
        $conn = $this->db_connect();

        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        $sql = "SELECT (select COUNT (1) ganados
    from TBL_GAME_STATUS
    WHERE ESTADO = 'GANADO') ganados, (select COUNT (1) empatados
    from TBL_GAME_STATUS
    WHERE ESTADO = 'EMPATADO') empatados, (select COUNT (1) en_juego
    from TBL_JUEGO
    left join TBL_GAME_STATUS
    on TBL_GAME_STATUS . GAME_ID = TBL_JUEGO . ID
    WHERE TBL_GAME_STATUS . GAME_ID IS NULL) en_juego

    from dual";

        $stmt = oci_parse($conn, $sql);

        oci_execute($stmt);

        $data = array();

        while (($row = oci_fetch_array($stmt, OCI_ASSOC))) {
            array_push($data, $row);
        }
        oci_free_statement($stmt);
        oci_close($conn);
        return $data;
    }

    function insertar_empate($params)
    {
        $conn = $this->db_connect();

        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        $sql = 'insert into TBL_GAME_STATUS(GAME_ID, ESTADO) VALUES (:idgame, \'EMPATADO\')';


        $stmt = oci_parse($conn, $sql);


        //  Bind the input parameter
        oci_bind_by_name($stmt, ':idgame', $params['idgame']);

        $result = oci_execute($stmt);

        oci_free_statement($stmt);
        oci_close($conn);

        return $result;
    }
}