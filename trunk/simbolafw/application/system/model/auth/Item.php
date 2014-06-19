<?php
namespace application\system\model\auth;
/**
 * Description of Model
 *
 * Model 	: item
 * Created	: 18Apr2014
 * Purpose 	: Item Model
 *
 * Change Logs
 * -----------------------------------------------------------
 * 18Apr2014 faraj: Created the model item
 *  
 * @author faraj
 *
 * Properties
 * @property bigint $item_id Item id
 * @property bigint $item_type Item type
 * @property String $item_name Item name
 * @property String $item_description Item description
 */
class Item extends \simbola\core\application\AppModel{
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
        self::setSource('system', 'auth', 'item');
        self::primaryKey('item_id');        

        //Relationships - Has Many
        //None

        //Relationships - Belongs To
        //None

        //Validations                
        // - item_type
        self::validateNumericalityOf(array("item_type", "only_integer" => true));
        // - item_name
        self::validateUniquenessOf(array("item_name"));
        self::validateSizeOf(array("item_name", "maximum" => 500));
        // - item_description
    }
}

?>
