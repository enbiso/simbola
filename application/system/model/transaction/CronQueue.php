<?php
namespace application\system\model\transaction;
/**
 * Description of Model
 *
 * Model 	: cronQueue
 * Created	: 05Jun2014
 * Purpose 	: Cron Queue Model
 *
 * Change Logs
 * -----------------------------------------------------------
 * 05Jun2014 faraj: Created the model cronQueue
 *  
 * @author faraj
 *
 * Properties
 * @property String $cron_id Cron id
 * @property String $queue_id Queue id
 * @property Queue $queue Queue
 * @property Cron $cron Cron 
 */
class CronQueue extends \simbola\core\application\AppModel{
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
        self::setSource('system', 'transaction', 'cron_queue');
        self::primaryKey(array('cron_id','queue_id'));        

        //Relationships - Has Many
        //None

        //Relationships - Belongs To
        self::belongsTo(array("queue", 
            "class_name" => '\application\system\model\transaction\Queue', 
            "foreign_key" => "queue_id", 
            "primary_key" => "id"));
        self::belongsTo(array("cron", 
            "class_name" => '\application\system\model\transaction\Cron', 
            "foreign_key" => "cron_id", 
            "primary_key" => "id"));

        //Validations
        // - cron_id
        self::validatePresenceOf(array("cron_id"));
        self::validateSizeOf(array("cron_id", "maximum" => 20));
        // - queue_id
        self::validatePresenceOf(array("queue_id"));
        self::validateSizeOf(array("queue_id", "maximum" => 20));
    }
}

?>
