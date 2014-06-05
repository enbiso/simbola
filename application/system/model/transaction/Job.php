<?php
namespace application\system\model\transaction;
/**
 * Description of Model
 *
 * Model 	: job
 * Created	: 05Jun2014
 * Purpose 	: Job Model
 *
 * Change Logs
 * -----------------------------------------------------------
 * 05Jun2014 faraj: Created the model job
 *  
 * @author faraj
 *
 * Properties
 * @property bigint $id Id
 * @property Integer $priority Priority
 * @property String $type Type
 * @property String $queue_id Queue id
 * @property String $content Content
 * @property String $output Output
 * @property Integer $user_id User ID
 * @property Queue $queue Queue
 * @property \application\system\model\auth\User $user User
 */
class Job extends \simbola\core\application\AppModel{
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
        self::setSource('system', 'transaction', 'job');
        self::primaryKey('id');        

        //Relationships - Has Many
        //None

        //Relationships - Belongs To
        self::belongsTo(array("user", 
            "class_name" => "\application\system\model\auth\User", 
            "foreign_key" => "user_id", 
            "primary_key" => "user_id"));
        
        self::belongsTo(array("queue", 
            "class_name" => '\application\system\model\transaction\Queue', 
            "foreign_key" => "queue_id", 
            "primary_key" => "id"));

        //Validations        
        // - priority
        self::validateNumericalityOf(array("priority", "only_integer" => true));
        // - type
        self::validateSizeOf(array("type", "maximum" => 20));
        // - queue_id
        self::validateSizeOf(array("queue_id", "maximum" => 10));
        // - content
        // - output
    }
}

?>
