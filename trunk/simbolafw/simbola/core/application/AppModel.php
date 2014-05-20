<?php

namespace simbola\core\application;

/** 
 * The abstract base class that should be used to define the Application Models
 *
 * @author Faraj Farook
 */
class AppModel extends \ActiveRecord\Model{    
    static $delegate = array();
    static $alias_attribute = array();
    static $state_config = array(
        'states' => array('idle'),
        'rules'  => array(
            array('start' => 'idle'),
            array('end' => 'idle')
        ),
    );
    
    /**
     * State machine configuration
     * 
     * @param array $stateConfig State machine config
     */
    public static function stateMachine($stateConfig) {
        self::$state_config = $stateConfig;
    }
    
    /**
     * Return array of available states
     * 
     * @return array of states
     */
    public static function getStates() {
        return self::$state_config['states'];
    }
    
    /**
     * Used to fetch the term associated with the model term file for 
     * the specified field 
     * 
     * @param string $fieldName
     * @return string
     */
    public static function term($fieldName) {        
        return \simbola\Simbola::app()->term->getModelTerm(static::$class_name, $fieldName);
    }
    
    /**
     * Used to fetch echo the term associated with the model term file for 
     * the specified field 
     * 
     * @param type $fieldName
     */
    public static function eTerm($fieldName) {        
        echo self::Term($fieldName);
    }
    
    /**
     * Used to fetch the term associated with the model term file for 
     * the specified Enumeration and enumeration field
     * 
     * @param string $enum Enumeration name
     * @param string $name enumeration field
     * @return string
     */
    public static function EnumTerm($enum, $name) {
        return self::Term("e_{$enum}.{$name}");
    }
    
    /**
     * Used to fetch and echo the term associated with the model term file for 
     * the specified Enumeration and enumeration field
     * 
     * @param string $enum Enumeration name
     * @param string $name enumeration field     
     */ 
    public static function eEnumTerm($enum, $name) {
        echo self::EnumTerm($enum, $name);
    }
    
    /**
     * Used to get the class name string using for the specified model
     * 
     * @param string $module Module name
     * @param string $lu Logical Unit name
     * @param string $model Model name
     * @return string The class name
     */
    public static function getClass($module, $lu, $model) {
        $mconf = \simbola\Simbola::app()->getModuleConfig($module);
        return "{$mconf->getNamespace('model')}\\{$lu}\\".ucfirst($model);
    }

    /**
     * Override this function to setup the model initializations.
     */
    public static function initialize(){}
    
    /**
     * Framework function used to initialize automatically
     */
    public static function __static() {
        static::initialize();
    }

    /**
     * Set the model source 
     * 
     * @param string $module Module name
     * @param string $lu Logical Unit name
     * @param string $name Table name
     */
    public static function setSource($module, $lu, $name) {
        static::$table_name = \simbola\Simbola::app()->db->getDriver()->getTableName($module, $lu, $name);
    }

    /**
     * Set belongs to relationship
     * 
     * @param array $value
     */
    public static function belongsTo($value) {
        static::$belongs_to[] = $value;
    }

    /**
     * Set has one relationship
     * 
     * @param array $value
     */
    public static function hasOne($value) {
        static::$has_one[] = $value;
    }

    /**
     * Set has many relationship
     * 
     * @param array $value
     */
    public static function hasMany($value) {
        static::$has_many[] = $value;
    }

    /**
     * before_create: called before a NEW model is to be inserted into the database
     * @param string $value funtion name
     */
    public static function beforeCreate($value) {
        static::$before_create[] = $value;
    }
    
    /**
     * before_update: called before an existing model has been saved
     * @param string $value funtion name
     */
    public static function beforeUpdate($value) {
        static::$before_update[] = $value;
    }
    
    /**
     * before_validation: called before running validators
     * @param string $value funtion name
     */
    public static function beforeValidation($value) {
        static::$before_validation[] = $value;
    }
    
    /**
     * before_validation_on_create: called before validation on a NEW model being inserted
     * @param string $value funtion name
     */
    public static function beforeValidationOnCreate($value) {
        static::$before_validation_on_create[] = $value;
    }
    
    /**
     * before_validation_on_update: same as above except for an existing model being saved
     * @param string $value funtion name
     */
    public static function beforeValidationOnUpdate($value) {
        static::$before_validation_on_update[] = $value;
    }
    
    /**
     * before_destroy: called after a model has been deleted
     * @param string $value funtion name
     */
    public static function beforeDestroy($value) {
        static::$before_destroy[] = $value;
    }
    
    /**
     * before_save: called before a model is saved     
     * @param string $value funtion name
     */
    public static function beforeSave($value) {
        static::$before_save[] = $value;
    }
    
    /**
     * after_save: called after a model is saved
     * @param string $value funtion name
     */
    public static function afterSave($value) {
        static::$after_save[] = $value;
    }
    
    /**
     * after_create: called after a NEW model has been inserted into the database
     * @param string $value funtion name
     */
    public static function afterCreate($value) {
        static::$after_create[] = $value;
    }
    
    /**
     * after_update: called after an existing model has been saved
     * @param string $value funtion name
     */
    public static function afterUpdate($value) {
        static::$after_update[] = $value;
    }

    /**
     * after_validation: called after running validators
     * @param string $value funtion name
     */
    public static function afterValidation($value) {
        static::$after_validation[] = $value;
    }
    
    /**
     * after_validation_on_create: called after validation on a NEW model being inserted
     * @param string $value funtion name
     */
    public static function afterValidationOnCreate($value) {
        static::$after_validation_on_create[] = $value;
    }
    
    /**
     * after_validation_on_update: same as above except for an existing model being saved
     * @param string $value funtion name
     */
    public static function afterValidationOnUpdate($value) {
        static::$after_validation_on_update[] = $value;
    }
    
    /**
     * after_destroy: called after a model has been deleted
     * @param string $value funtion name
     */
    public static function afterDestroy($value) {
        static::$after_destroy[] = $value;
    }

    /**
     * Set alias name for the field     
     * @param string $name Name of the field
     * @param string $alias Alias name of the field
     */
    public static function aliasAttribute($name, $alias) {
        static::$alias_attribute[$alias] = $name;
    }

    /**
     * Add PHP active record delegate
     * 
     * array('name', 'to' => 'host', 'prefix' => 'woot')     
     * $person->woot_name is same as $person->host->name
     * 
     * @param array $delegate PHP AR Delegate entry          
     */
    public static function delegate($delegate) {
        static::$delegate[] = $delegate;
    }
    
    /**
     * Set the primary key of the table
     * 
     * @param string $pk Primary key
     */
    public static function primaryKey($pk) {
        static::$primary_key = $pk;
    }

    /**
     * validates uneditable variable
     */
    static $validates_uneditable = array();
    /**
     * App model validation
     * 
     * @param string $attr attribute name
     */
    public static function validateUneditable($attr) {        
        static::$validates_uneditable[] = $attr;
    }
    
    /**
     * PHP activerecord validation
     * 
     * @param array $attr attribute name
     */
    public static function validatePresenceOf($attr) {        
        static::$validates_presence_of[] = $attr;
    }
    
    /**
     * PHP activerecord validation
     * 
     * @param array $attr attribute name
     */
    public static function validateSizeOf($attr) {        
        static::$validates_size_of[] = $attr;
    }
    
    /**
     * PHP activerecord validation
     * 
     * @param array $attr attribute name
     */
    public static function validateLengthOf($attr) {        
        static::$validates_length_of[] = $attr;
    }

    /**
     * PHP activerecord validation
     * 
     * @param array $attr attribute name
     */
    public static function validateExclusionOf($attr) {        
        static::$validates_exclusion_of[] = $attr;
    }

    /**
     * PHP activerecord validation
     * 
     * @param array $attr attribute name
     */
    public static function validateInclusionOf($attr) {        
        static::$validates_inclusion_of[] = $attr;
    }   
    
    /**
     * PHP activerecord validation
     * 
     * @param array $attr attribute name
     */
    public static function validateFormatOf($attr) {        
        static::$validates_format_of[] = $attr;
    }
    
    /**
     * PHP activerecord validation
     * 
     * @param array $attr attribute name
     */
    public static function validateNumericalityOf($attr) {        
        static::$validates_numericality_of[] = $attr;
    }
    
    /**
     * PHP activerecord validation
     * 
     * @param array $attr attribute name
     */
    public static function validateUniquenessOf($attr) {        
        static::$validates_uniqueness_of[] = $attr;
    }

    /**
     * Check if the attribute is editable
     * 
     * @param string $attribute attribute name
     * @return boolean
     */
    public static function isEditable($attribute) {
        return !(in_array($attribute, static::$validates_uneditable));
    }

    /**
     * Set the model class name on initialization
     * 
     * @param string $cname Class name
     */
    public static function setClass($cname) {
        static::$class_name = $cname;
    }

    /**
     * Returns the table columns associated with the model
     * 
     * @return array
     */
    public static function Columns() {
        return static::connection()->columns(static::$table_name);
    }

    /**
     * Returns the table keys associated with the model
     * 
     * @return array
     */
    public static function Keys() {
        $keys = array();
        foreach (static::Columns() as $column) {
            if ($column->pk) {
                $keys[] = $column;
            }
        }
        return $keys;
    }

    /**
     * Returns the HTML SELECT population data
     * 
     * @param string $key Key field name
     * @param string $field Diaplay field name
     * @param mixed $opts Options on find
     * @return array HTML Select data
     */
    public static function getSelectData($key, $field, $opts = 'all') {
        $data = static::find($opts);
        $sData = array();
        foreach ($data as $datum) {
            $sData[$datum->$key] = $datum->$field;
        }
        return $sData;
    }

    /**
     * Overrides phpactive record default function set_timestamp
     */
    public function set_timestamps() {
        parent::set_timestamps();
        $now = date('Y-m-d H:i:s');
        $colNameArr = array_keys($this->Columns());
        if ($this->is_new_record()) {
            if (in_array('_id', $colNameArr)) {
                $this->_id = uniqid('', true);
            }
            if (in_array('_created', $colNameArr)) {
                $this->_created = $now;
            }
            if (in_array('_state', $colNameArr)) {
                $rules = self::$state_config['rules'];                
                foreach ($rules as $rule) {
                    if(isset($rule['start'])){
                        $this->_state = $rule['start'];
                    }
                }
            }
        }
        if (in_array('_version', $colNameArr)) {
            $this->_version = $now;
        }
    }

    /**
     * Uneditable check
     */
    public function set_attributes(array $attributes) {
        if (!$this->is_new_record() && is_array(static::$validates_uneditable)) {
            foreach (static::$validates_uneditable as $attr) {
                unset($attributes[$attr]);
            }
        }
        parent::set_attributes($attributes);
    }
    
    /**
     * Set/Get state from state_machine
     * 
     * @param string $state State
     */
    public function state($state = false) {
        if($state){
            if($this->runStateLogic($this->_state, $state)){
                $this->_state = $state;
            }
        }else{
            return $this->_state;
        }
    }
    
    /**
     * Returns created date
     * 
     * @return \ActiveRecord\DateTime
     */
    public function created($format = false) {
        if($format){
            return $this->_created->format($format);        
        }else{
            return $this->_created;
        }
    }
    
    /**
     * Returns modified date
     * 
     * @return \ActiveRecord\DateTime
     */
    public function modified($format = false) {
        if($format){
            return $this->_version->format($format);        
        }else{
            return $this->_version;  
        }
    }
    
    /**
     * Check state logic
     * 
     * @param string $oldState Old State
     * @param string $newState New State
     * @return boolean
     */
    private function runStateLogic($oldState, $newState) {
        if(in_array($oldState, self::$state_config['states'])&&
           in_array($newState, self::$state_config['states'])){
            $rules = self::$state_config['rules'];
            foreach ($rules as $rule) {
                if(isset($rule['start'])){
                    $rule['from'] = null;
                    $rule['to'] = $rule['start'];        
                }
                if($rule['from'] == $oldState && $rule['to'] == $newState){
                    if(isset($rule['action'])){
                        $action = $rule['action'];
                        return $this->$action();
                    }else{
                        return true;
                    }
                }
            }
        }
        return false;
    }
    
    /**
     * Get dirty attributes. If param set to all = true then 
     * _version, _created, _state and _id were also includeed.
     * @param type $all default true
     * @return array Attributes
     */
    public function dirty_attributes($all = true) {
        $attr = parent::dirty_attributes();
        if(!$all){
            foreach (array('_version', '_created', '_state', '_id') as $key) {
                if(array_key_exists($key, $attr)){
                    unset($attr[$key]);
                }
            }
        }
        return $attr;
    }
}

?>
