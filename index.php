<?php

include_once 'simbola/Simbola.php';

use \simbola\Simbola;

Simbola::app()->setup(array(
    'BASEPATH' => dirname(__FILE__),
    'ERROR_LEVEL' => E_ALL,
    'APPNAME' => 'Simbola Application',        
    'DEFAULT' => array(
        'LAYOUT' => '/system/layout/main'
    )
));

Simbola::app()->component('url', array(
    'URL_BASE' => 'sampleapp',
    'HIDE_INDEX' => false,
));


Simbola::app()->component('db', array(
    'VENDOR' => 'MYSQL',
    'SERVER' => 'localhost',
    'DBNAME' => 'sampleapp_db',
    'USERNAME' => 'root',
    'PASSWORD' => '',    
    'PAGE_LENGTH' => 10,
    'DEBUG' => false,
));



Simbola::app()->component('log', array(
    'TYPES' => array('TRACE','DEBUG','ERROR','INFO'),
));

Simbola::app()->component('auth', array(
    'BYPASS' => false,
));

Simbola::app()->component('router', array(
    'DEFAULT' => 'system/www/index',
    'LOGIN' => 'system/auth/login',
));

Simbola::app()->execute();