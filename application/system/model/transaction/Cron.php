<?php
namespace application\system\model\transaction;
/**
 * Description of Model
 *
 * Model 	: cron
 * Created	: 04Jun2014
 * Purpose 	: Cron Model
 *
 * Change Logs
 * -----------------------------------------------------------
 * 04Jun2014 faraj: Created the model cron
 *  
 * @author faraj
 *
 * Properties
 * @property String $id Id
 * @property Long $last_executed Last executed
 * @property bigint $executed_count Executed count
 */
class Cron extends \simbola\core\application\AppModel{
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
        self::setSource('system', 'transaction', 'cron');
        self::primaryKey('id');        

        //Relationships - Has Many
        //None

        //Relationships - Belongs To
        //None

        //Validations
        // - id
        self::validateSizeOf(array("id", "maximum" => 20));
        // - last_executed
        self::validatePresenceOf(array("last_executed"));
        // - executed_count
        self::validateNumericalityOf(array("executed_count", "only_integer" => true));
    }
}

?>
