<?php
namespace application\system\model\dbsetup;
/**
 * Description of Model
 *
 * Model 	: db_revision
 * Created	: 18Apr2014
 * Purpose 	: Revision Model
 *
 * Change Logs
 * -----------------------------------------------------------
 * 18Apr2014 faraj: Created the model revision
 *  
 * @author faraj
 *
 * Properties
 * @property String $rev Rev
 * @property String $content Content
 */
class DbRevision extends \simbola\core\application\AppModel{
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
        self::setSource('system', 'setup', 'db_revision');
        self::primaryKey('rev');        

        //Relationships - Has Many
        //None

        //Relationships - Belongs To
        //None

        //Validations
        // - rev
        self::validateSizeOf(array("rev", "maximum" => 100));
        // - content
    }
}

?>
