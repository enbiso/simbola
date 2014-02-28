<?php 
namespace simbola;

include_once 'core/Application.php';

/**
 * Simbola Framework  
 * 
 * @author Faraj Farook
 * @copyright (c) 2014, enbiso
 * @version 1.5 
 */
class Simbola extends core\Application{
    /**
     * @access private
     * @var Simbola 
     */
    private static $app;
    
    /**           
     * Get the application instance which is under execution.      
     * @access public
     * @return Simbola     
     */
    public static function app() {
        if(!isset(Simbola::$app)){
            Simbola::$app = new Simbola();
        }
        return Simbola::$app;                        
    }
}