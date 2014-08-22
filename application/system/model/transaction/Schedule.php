<?php
namespace application\system\model\transaction;
/**
 * Description of Model
 *
 * Model 	: schedule
 * Created	: 21Aug2014
 * Purpose 	: Schedule Model
 *
 * Change Logs
 * -----------------------------------------------------------
 * 21Aug2014 faraj: Created the model schedule
 *  
 * @author faraj
 *
 * Properties
 * @property Integer $id Id
 * @property bigint $user_id User id
 * @property \ActiveRecord\DateTime $valid_from Valid from
 * @property \ActiveRecord\DateTime $valid_till Valid till
 * @property bigint $interval Interval
 * @property String $description Description
 * @property Integer $priority Priority
 * @property String $type Type
 * @property String $queue_id Queue id
 * @property String $content Content
 * @property \ActiveRecord\DateTime $next_execute Next Execution timestamp
 * @property \ActiveRecord\DateTime $last_execute Next Execution timestamp
 * @property bigint $execute_count Execute Count
 */
class Schedule extends \simbola\core\application\AppModel{
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
        self::setSource('system', 'transaction', 'schedule');
        self::primaryKey('id');        

        self::stateMachine(array(
            'states' => array('idle', 'ready', 'complete', 'error'),
            'rules' => array(
                array('start' => 'idle'),
                array('from' => 'idle', 'to' => 'ready'),
                array('from' => 'ready', 'to' => 'complete'),
                array('from' => 'ready', 'to' => 'error'),                
                array('from' => 'error', 'to' => 'idle'),
                array('from' => 'error', 'to' => 'ready'),
                array('end' => 'complete')
            ),
        ));
        
        //Relationships - Has Many
        self::belongsTo(array("user", 
            "class_name" => "\application\system\model\auth\User", 
            "foreign_key" => "user_id", 
            "primary_key" => "user_id"));
        
        self::belongsTo(array("queue", 
            "class_name" => '\application\system\model\transaction\Queue', 
            "foreign_key" => "queue_id", 
            "primary_key" => "id"));
        //None

        //Relationships - Belongs To
        //None

        //Validations
        // - id
        //self::validateNumericalityOf(array("id", "only_integer" => true));
        // - user_id
        self::validateNumericalityOf(array("user_id", "only_integer" => true));
        // - valid_from
        self::validatePresenceOf(array("valid_from"));
        // - valid_till
        self::validatePresenceOf(array("valid_till"));
        // - interval
        self::validateNumericalityOf(array("interval", "only_integer" => true));
        // - description
        // - priority
        self::validateNumericalityOf(array("priority", "only_integer" => true));
        // - type
        self::validateSizeOf(array("type", "maximum" => 20));
        // - queue_id
        self::validateSizeOf(array("queue_id", "maximum" => 10));
        // - content
    }
    
    function isValidType(){
        return in_array($this->type, array_keys(Job::GetTypes()));
    }
}

?>
