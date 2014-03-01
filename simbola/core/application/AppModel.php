<?php

namespace simbola\core\application;

/**
 * AppModel
 * The abstract base class that should be used to define the Application Models
 *
 * @author Faraj Farook
 */
class AppModel{    
    
    /**
     * Used to fetch the term associated with the model term file for 
     * the specified field 
     * 
     * @param string $fieldName
     * @return string
     */
    public static function Term($fieldName) {        
        return \simbola\Simbola::app()->term->getModelTerm(get_called_class(),$fieldName);
    }
    
    /**
     * Used to fetch echo the term associated with the model term file for 
     * the specified field 
     * 
     * @param type $fieldName
     */
    public static function eTerm($fieldName) {        
        echo self::Term($fieldName);
    }
    
    /**
     * Used to fetch the term associated with the model term file for 
     * the specified Enumeration and enumeration field
     * 
     * @param string $enum Enumeration name
     * @param string $name enumeration field
     * @return string
     */
    public static function EnumTerm($enum, $name) {
        return self::Term("e_{$enum}.{$name}");
    }
    
    /**
     * Used to fetch and echo the term associated with the model term file for 
     * the specified Enumeration and enumeration field
     * 
     * @param string $enum Enumeration name
     * @param string $name enumeration field     
     */ 
    public static function eEnumTerm($enum, $name) {
        echo self::EnumTerm($enum, $name);
    }
    
    /**
     * Used to get the class name string using for the specified model
     * 
     * @param string $module Module name
     * @param string $lu Logical Unit name
     * @param string $model Model name
     * @return string The class name
     */
    public static function getClass($module, $lu, $model) {
        $mconf = \simbola\Simbola::app()->getModuleConfig($module);
        return "{$mconf->getNamespace('model')}\\{$lu}\\".ucfirst($model);
    }
}

?>
