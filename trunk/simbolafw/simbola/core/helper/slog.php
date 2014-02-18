<?php
function slog_info($msg){
    \simbola\Simbola::app()->log->info($msg);
}

function slog_trace($msg){
    \simbola\Simbola::app()->log->trace($msg);
}

function slog_error($msg){
    \simbola\Simbola::app()->log->error($msg);
}

function slog_syserror($method, $msg){
    slog_error($method.":".$msg);
}

function slog_debug($msg){
    \simbola\Simbola::app()->log->debug($msg);
}

function slog_warn($msg){
    \simbola\Simbola::app()->log->warn($msg);
}
