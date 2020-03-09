<?php

class db_database
{
    function __construct()
    {
        
    }

 

    /* Connect */
    function db_connect()
    {
        $c = oci_connect(DB_USER, DB_PASS, DB_HOST);

        if (!$c) {
            //echo "no connection";
            return false;
        } else {            
            //echo "Success";
        }
        
        return $c;

    }


    /* Run a query, with optional error-checking */
    function db_query($query, $err = TRUE)
    {
        $db = $this->db_connect();

        return ($this->db_query_be($query, $db, $err));
    }

    /* run a query and return the results as an array, optionally keyed by the specified database field */
    function db_getarray($query, $key = NULL)
    {
        $res = $this->db_query($query);
        $ret = array();
       
       
    }

}
?>