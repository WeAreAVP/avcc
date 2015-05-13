<?php
/**
 * @author Darius Matulionis <darius@matulionis.lt>
 * @since : 2012-02-23, 14.39.08
 */

//FOR DEBUGING
//error_reporting(E_ALL^E_NOTICE);
ini_set("display_startup_errors", 0); // set 1 to show errors
ini_set("display_errors", 0); // set 1 to show errors

/**
 * AUTH SHOULD BE DONE HERE !!!! 
 */
//!!!! CHANGE THIS !!!!!! AND THEN ajax.php

$session_id = session_id() ? session_id() : $_COOKIE["PHPSESSID"];
if(session_start()){
    $_SESSION['session_id'] = $session_id;
    $_SESSION['user_auth'] = true;
}

$auth = false;
if($_SESSION['session_id'] && $_SESSION['user_auth']){
    $auth = true;
    require 'EncriptDecript.php';
    $ed = new EncriptDecript();
    $encripted_session_id =$ed->encript($session_id);
}

if(!$auth){
    die("You are not allowed to be here !!!");
}
//------------------------------------------------------------


//PLUGIN PATH
defined('PLUGIN_PATH')
        || define('PLUGIN_PATH', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR.'..' ));

//LIBS PATH
defined('LIBS_PATH')
        || define('LIBS_PATH', realpath(dirname(__FILE__)));

//ENCRIPTED SESSION ID
defined('ENCRIPTED_SESSION_ID')
        || define('ENCRIPTED_SESSION_ID', $encripted_session_id);


//!!!! CHANGE THIS !!!!!!
// WEB BASE DIRECTORY
defined('WEB_BASE') || define('WEB_BASE', 'TinyMCE_ImageManager/upload' );


//!!!! CHANGE THIS !!!!!!
//UPLOAD PATH
defined('UPLOADS_PATH')
        || define('UPLOADS_PATH', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.WEB_BASE ));



defined('UPLOADS_URL') || define('UPLOADS_URL', 'http://'.$_SERVER['HTTP_HOST'].'/'.WEB_BASE );
