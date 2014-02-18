<?php
namespace simbola\core\application;
/**
 * Description of AppModel
 *
 * @author Faraj
 */
class AppModel{    
    
    public static function Term($field_name) {        
        return \simbola\Simbola::app()->term->getModelTerm(get_called_class(),$field_name);
    }
    
    public static function eTerm($field_name) {        
        echo self::Term($field_name);
    }
    
    public static function EnumTerm($enum, $name) {
        return self::Term("e_{$enum}.{$name}");
    }
        
    public static function eEnumTerm($enum, $name) {
        echo self::EnumTerm($enum, $name);
    }
    
    public static function getClass($module, $lu, $model) {
        $mconf = \simbola\Simbola::app()->getModuleConfig($module);
        return "{$mconf->getNamespace('model')}\\{$lu}\\".ucfirst($model);
    }
}

?>
