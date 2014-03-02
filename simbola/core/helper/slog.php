<?php

/**
 * Logger info
 * 
 * @param string $msg Message
 */
function slog_info($msg){
    \simbola\Simbola::app()->log->info($msg);
}

/**
 * Logger trace
 * 
 * @param string $msg Message
 */
function slog_trace($msg){
    \simbola\Simbola::app()->log->trace($msg);
}

/**
 * Logger error
 * 
 * @param string $msg Message
 */
function slog_error($msg){
    \simbola\Simbola::app()->log->error($msg);
}

/**
 * Logger default
 * 
 * @param string $msg Message
 */
function slog_log($msg){
    \simbola\Simbola::app()->log->log($msg);
}

/**
 * Logger database
 * 
 * @param string $msg Message
 */
function slog_db($msg){
    \simbola\Simbola::app()->log->db($msg);
}

/**
 * Logger custem error
 * 
 * @param string $method Method name
 * @param string $msg Message
 */
function slog_syserror($method, $msg){
    slog_error($method.":".$msg);
}

/**
 * Logger debug
 * 
 * @param string $msg Message
 */
function slog_debug($msg){
    \simbola\Simbola::app()->log->debug($msg);
}

/**
 * Logger warning
 * 
 * @param string $msg Message
 */
function slog_warn($msg){
    \simbola\Simbola::app()->log->warn($msg);
}
