<?php

class cls_utils
{
    public function __construct()
    {   
        ini_set('session.gc_maxlifetime', '315360000'); 
        session_start();        
    }


   function getreq($name)
    {
        if (isset($_POST[$name]))
            $toret = $_POST[$name];
        elseif (isset($_GET[$name]))
            $toret = $_GET[$name];
        else
            $toret = NULL;

        return ($toret);
    }

}