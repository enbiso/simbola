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

Simbola::app()->component('resource', array(
    'MODE' => 'DEV',//DEV or PROD
));

Simbola::app()->component('db', array(
    'VENDOR' => 'MYSQL',
    'SERVER' => 'localhost',
    'DBNAME' => 'simbola_db',
    'USERNAME' => 'root',
    'PASSWORD' => '',    
    'PAGE_LENGTH' => 10,
    'DEBUG' => false,
));

Simbola::app()->component('log', array(
    'TYPES' => array('TRACE','DEBUG','ERROR','INFO'),
));

Simbola::app()->component('social', array(
    'DEBUG' => array('ENABLE' => false, 'FILE' => './logs/social.log'),
    'PROVIDERS' => array(
        "Google" => array(
            "enabled" => false,
            "keys" => array(
                "id" => "[GOOGLE_ID]",
                "secret" => "[GOOGLE_SECRET]"),
        ),
        "Facebook" => array(
            "enabled" => false,
            "keys" => array(
                "id" => "[FB_ID]",
                "secret" => "[FB_SECRET]"),
        ),
        "Twitter" => array(
            "enabled" => false,
            "keys" => array(
                "key" => "[TWITTER_KEY]",
                "secret" => "[TWITTER_SECRET]")
        ),
    )
));

Simbola::app()->component('auth', array(
    'BYPASS' => true,
));

Simbola::app()->component('router', array(
    'DEFAULT' => 'system/site/index',
    'LOGIN' => 'system/user/login',
));

Simbola::app()->execute();