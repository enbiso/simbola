<?php
namespace application\system\model\transaction;
/**
 * Description of Model
 *
 * Model 	: scheduleJob
 * Created	: 21Aug2014
 * Purpose 	: Schedule Job Model
 *
 * Change Logs
 * -----------------------------------------------------------
 * 21Aug2014 faraj: Created the model scheduleJob
 *  
 * @author faraj
 *
 * Properties
 * @property Integer $schedule_id Schedule id
 * @property bigint $job_id Job id
 */
class ScheduleJob extends \simbola\core\application\AppModel{
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
        self::setSource('system', 'transaction', 'schedule_job');
        self::primaryKey('schedule_id','job_id');        

        //Relationships - Has Many
        //None

        //Relationships - Belongs To
        //None

        //Validations
        // - schedule_id
        self::validatePresenceOf(array("schedule_id"));
        self::validateNumericalityOf(array("schedule_id", "only_integer" => true));
        // - job_id
        self::validatePresenceOf(array("job_id"));
        self::validateNumericalityOf(array("job_id", "only_integer" => true));
    }
}

?>
