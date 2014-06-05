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
 * @property Long $last_execute Last execute
 * @property bigint $execute_count Execute count
 * @property Integer $interval Interval
 * @property Integer $job_count Job count
 * @property array $cronQueues Cron Queues 
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

        self::stateMachine(array(
            'states' => array('ready', 'execute', 'halt'),
            'rules' => array(
                array('start' => 'ready'),
                array('from' => 'ready', 'to' => 'execute'),
                array('from' => 'execute', 'to' => 'ready'),
                array('from' => 'ready', 'to' => 'halt'),
                array('from' => 'halt', 'to' => 'ready'),
                array('end' => 'ready')
            ),
        ));
        //Relationships - Has Many
        self::hasMany(array("cronQueues", 
            "class_name" => '\application\system\model\transaction\CronQueue', 
            "foreign_key" => "cron_id", 
            "primary_key" => "id"));

        //Relationships - Belongs To
        //None

        //Validations
        // - id
        self::validateSizeOf(array("id", "maximum" => 20));
        // - executed
        self::validatePresenceOf(array("last_execute"));
        // - count
        self::validateNumericalityOf(array("execute_count", "only_integer" => true));
        // - interval
        self::validateNumericalityOf(array("interval", "only_integer" => true));
        // - job_count
        self::validateNumericalityOf(array("job_count", "only_integer" => true));
    }
    
    /**
     * Get queue infomation
     * 
     * @param string $attr Attribute name default FALSE
     * @return array
     */
    public function getQueues($attr = false) {
        $queues = array();
        foreach ($this->cronQueues as $cronQueue) {
            if($attr){
                $queues[] = $cronQueue->queue->$attr;
            }else{
                $queues[] = $cronQueue->queue;
            }
        }
        return $queues;
    }
}

?>
