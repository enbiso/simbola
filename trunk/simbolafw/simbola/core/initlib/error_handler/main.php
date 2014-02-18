<?php
//exception hanlder
function simbola_exception_handler($exception) {
    include_once('view/_header.php');
    include_once("view/exception/_header.php");    
    include_once("view/exception/_body.php");
    include_once("view/_footer.php");
    return true;
}
set_exception_handler('simbola_exception_handler');

//error handler
function simbola_error_handler($errno, $errstr, $errfile, $errline) {
    include_once('view/_header.php');
    switch ($errno) {
        case E_USER_ERROR:
        case E_ERROR:
        case E_CORE_ERROR:    
            $type = "error";
            break;
        case E_USER_WARNING:
        case E_WARNING:
        case E_CORE_WARNING:    
            $type = "warning";
            break;
        case E_USER_NOTICE:
        case E_NOTICE:        
            $type = "notice";
            break;
        default:
            $type = "other";
            break;
    }
    include_once("view/error/_{$type}_header.php");    
    include_once("view/error/_body.php");    
    include_once("view/_footer.php");
    return true;
}

set_error_handler("simbola_error_handler");

//shutdown handler
function simbola_shutdown_handler() {
    $error = error_get_last();    
    if ($error !== NULL) {
        $errno = $error["type"];
        $errfile = $error["file"];
        $errline = $error["line"];
        $errstr = $error["message"];
        simbola_error_handler($errno, $errstr, $errfile, $errline);
    }else{
        //do somthing on shutdown if required
    }
}
register_shutdown_function('simbola_shutdown_handler');