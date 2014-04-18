<?php
namespace application\system\model\auth;
/**
 * Description of Model
 *
 * Model 	: assign
 * Created	: 18Apr2014
 * Purpose 	: Assign Model
 *
 * Change Logs
 * -----------------------------------------------------------
 * 18Apr2014 faraj: Created the model assign
 *  
 * @author faraj
 *
 * Properties
 * @property bigint $user_id User id
 * @property bigint $item_id Item id
 */
class Assign extends \simbola\core\application\AppModel{
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
        self::setSource('system', 'auth', 'assign');
        self::primaryKey('user_id','item_id');        

        //Relationships - Has Many
        //None

        //Relationships - Belongs To
        //None

        //Validations
        // - user_id
        self::validatePresenceOf(array("user_id"));
        self::validateNumericalityOf(array("user_id", "only_integer" => true));
        // - item_id
        self::validatePresenceOf(array("item_id"));
        self::validateNumericalityOf(array("item_id", "only_integer" => true));
    }
}

?>
