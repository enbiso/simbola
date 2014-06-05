<?php
namespace application\system\model\transaction;
/**
 * Description of Model
 *
 * Model 	: queue
 * Created	: 05Jun2014
 * Purpose 	: Queue Model
 *
 * Change Logs
 * -----------------------------------------------------------
 * 05Jun2014 faraj: Created the model queue
 *  
 * @author faraj
 *
 * Properties
 * @property String $id Id
 * @property String $description Description
 * @property array $jobs Jobs
 * @property array $cronQueues Cron Queues
 */
class Queue extends \simbola\core\application\AppModel{
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
        self::setSource('system', 'transaction', 'queue');
        self::primaryKey('id');        

        //Relationships - Has Many
        self::hasMany(array("jobs", 
            "class_name" => '\application\system\model\transaction\Job', 
            "foreign_key" => "queue_id", 
            "primary_key" => "id"));

        self::hasMany(array("cronQueues", 
            "class_name" => '\application\system\model\transaction\CronQueue', 
            "foreign_key" => "queue_id", 
            "primary_key" => "id"));
        
        //Relationships - Belongs To
        //None

        //Validations
        // - id
        self::validateSizeOf(array("id", "maximum" => 20));
        // - description
    }
}

?>
