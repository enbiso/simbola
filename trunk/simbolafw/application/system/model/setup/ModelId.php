<?php
namespace application\system\model\setup;
/**
 * Description of Model
 *
 * Model 	: modelId
 * Created	: 24Jun2014
 * Purpose 	: Model Id Model
 *
 * Change Logs
 * -----------------------------------------------------------
 * 24Jun2014 faraj: Created the model modelId
 *  
 * @author faraj
 *
 * Properties
 * @property String $module Module
 * @property String $lu Lu
 * @property String $name Model
 * @property Integer $user_id User id
 * @property String $allocator Allocator
 * @property bigint $start Start
 * @property bigint $end End
 * @property bigint $current Current
 * @property Integer $id Id
 */
class ModelId extends \simbola\core\application\AppModel{
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
        self::setSource('system', 'setup', 'model_id');
        self::primaryKey('id');        

        self::stateMachine(array(
           'states' => array('active', 'finished'),
           'rules' => array(
               array('start' => 'active'),
               array('end' => 'finished'),
               array('from' => 'active', 'to' => 'finished')
           )
        ));
        //Relationships - Has Many
        //None

        //Relationships - Belongs To
        self::belongsTo(array('user', 
            'class_name' => '\application\system\model\auth\User', 
            'foreign_key' => 'user_id', 'primary_key' => 'user_id'));

        //Validations
        // - module
        self::validateSizeOf(array("module", "maximum" => 100));
        // - lu
        self::validateSizeOf(array("lu", "maximum" => 100));
        // - model
        self::validateSizeOf(array("name", "maximum" => 100));
        // - user_id
        self::validateNumericalityOf(array("user_id", "only_integer" => true));
        // - start
        self::validateNumericalityOf(array("start", "only_integer" => true));
        // - end
        self::validateNumericalityOf(array("end", "only_integer" => true));
        // - current
        self::validateNumericalityOf(array("current", "only_integer" => true));        
    }
}

?>
