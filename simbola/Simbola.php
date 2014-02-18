<?php 
namespace simbola;

include_once 'core/Application.php';

//Simbola framework
class Simbola extends core\Application{
    private static $app;
    public static function app() {
        if(!isset(Simbola::$app)){
            Simbola::$app = new Simbola();
        }
        return Simbola::$app;
    }
}

if(isset($argc)&&isset($argv)){
    //SHELL EXECUTE
    if($argc > 1 && $argv[1] = 'shell'){
        Simbola::app()->setup(array("BASEPATH"=>'.'));
        Simbola::app()->shell();
    }
}