<?php
namespace application\system\model\auth;
/**
 * Description of Model
 *
 * Model 	: child
 * Created	: 18Apr2014
 * Purpose 	: Child Model
 *
 * Change Logs
 * -----------------------------------------------------------
 * 18Apr2014 faraj: Created the model child
 *  
 * @author faraj
 *
 * Properties
 * @property bigint $parent_id Parent id
 * @property bigint $child_id Child id
 */
class Child extends \simbola\core\application\AppModel{
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
        self::setSource('system', 'auth', 'child');
        self::primaryKey('parent_id','child_id');        

        //Relationships - Has Many
        //None

        //Relationships - Belongs To
        //None

        //Validations
        // - parent_id
        self::validatePresenceOf(array("parent_id"));
        self::validateNumericalityOf(array("parent_id", "only_integer" => true));
        // - child_id
        self::validatePresenceOf(array("child_id"));
        self::validateNumericalityOf(array("child_id", "only_integer" => true));
    }
}

?>
