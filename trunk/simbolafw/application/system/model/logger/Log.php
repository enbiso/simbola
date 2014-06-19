<?php
namespace application\system\model\logger;
/**
 * Description of Model
 *
 * Model 	: log
 * Created	: 18Apr2014
 * Purpose 	: Log Model
 *
 * Change Logs
 * -----------------------------------------------------------
 * 18Apr2014 faraj: Created the model log
 *  
 * @author faraj
 *
 * Properties 
 * @property String $type Type
 * @property String $trace Trace
 * @property String $message Message
 */
class Log extends \simbola\core\application\AppModel{
    static  //config params
            $table_name, 
            $primary_key, 
            $class_name, 
            //state machine
            $state_config,
            //relationships
            $has_many = array(), 
            $belongs_to = array(), 
            //alias
            $alias_attribute = array(),
            //validations system
            $validates_uneditable = array(),
            //validations php-ar 
            $validates_presence_of = array(),
            $validates_size_of = array(),
            $validates_length_of = array(),
            $validates_exclusion_of = array(),
            $validates_inclusion_of = array(),
            $validates_format_of = array(),
            $validates_numericality_of = array(),
            $validates_uniqueness_of = array();

    public static function initialize() {
        //Model Setup
        self::setClass(__CLASS__);
        self::setSource('system', 'logger', 'log');
        self::primaryKey('_id');        

        //Relationships - Has Many
        //None

        //Relationships - Belongs To
        //None

        //Validations                
        // - type
        self::validateSizeOf(array("type", "maximum" => 10));
        // - trace
        self::validateSizeOf(array("trace", "maximum" => 1000));
        // - message
        self::validateSizeOf(array("message", "maximum" => 1000));
    }
}

?>
