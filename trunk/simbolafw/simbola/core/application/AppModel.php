<?php

namespace simbola\core\application;

/**
 * The abstract base class that should be used to define the Application Models
 *
 * @author Faraj Farook
 */
class AppModel extends \ActiveRecord\Model {    
       
    /**     
     * @var array Delegetes
     */
    static $delegate = array();
    
    /**     
     * @var string/boolean Model ID name. By default it's disabled by setting false
     */
    static $model_id = false;
    
    /**     
     * @var int Model ID Range. This is the value interval assigned to the model ID per user.
     */
    static $model_id_range = 100;
    
    /**     
     * @var array Alias Attributes
     */
    static $alias_attribute = array();
    
    /**     
     * @var array State configuration
     */
    static $state_config;

    /**
     * Initialize defaults
     */
    private static function initDefaults() {
        static::$state_config = array(
            'states' => array('idle'),
            'rules' => array(
                array('start' => 'idle'),
                array('end' => 'idle')
            ),
        );
    }

    /**
     * Model ID configuration
     * @param type $columnName Column name to which model ID applys
     * @param type $range The range set to the model ID per user
     */
    public static function modelIdConfig($columnName, $range = 100) {
        static::$model_id = $columnName;
        static::$model_id_range = $range;
    }
    
    /**
     * Init and Get the modelID assigned
     * @param String $allocator Allocator id default 'default'
     * @return \application\system\model\setup\ModelId Model ID Object     
     */
    public static function modelIdInit($allocator = 'default') {        
        if(!static::$model_id){
            throw new \Exception('Model ID not enabled');
        }
        $source = \simbola\Simbola::app()->db->getDriver()->getSourceFromTableName(static::table_name());
        $keys = $source;
        $keys['user_id'] = \simbola\Simbola::app()->auth->getId();
        $keys['_state'] = "active";
        $keys['allocator'] = $allocator;
        $modelId = \application\system\model\setup\ModelId::find($keys);
        if(is_null($modelId)){
            $modelId = new \application\system\model\setup\ModelId($keys);      
            $idStart = 0;            
            foreach (\application\system\model\setup\ModelId::find('all', $source) as $currModelId) {
                if($idStart < $currModelId->end){
                    $idStart = $currModelId->end;
                }
            }
            $modelId->allocator = $allocator;
            $modelId->start = $idStart;
            $modelId->end = $idStart + static::$model_id_range;
            $modelId->current = $modelId->start;
            $modelId->save();
        }  
        return $modelId;
    }
    
    /**
     * Generate th next model ID
     * @param String $allocator Allocator id default 'default'
     * @return integer
     */
    public static function modelIdGenerateNext($allocator = 'default'){
        $modelId = static::modelIdInit($allocator);
        $modelId->current += 1;
        $modelId->save();
        if($modelId->current >= $modelId->end){
            $modelId->state("finished");
        }
        return $modelId->current;
    }
    
    /**
     * Set the current model ID
     * @param String $allocator Allocator id default 'default'
     * @param int $id ID
     * @param boolean $safeSet Safe set flag default true
     */
    public static function modelIdSetCurrent($id, $safeSet = true, $allocator = 'default') {
        $modelId = static::modelIdInit($allocator);
        if($id > $modelId->end || $id < $modelId->start){
            throw new \Exception("Model ID '{$modelId->allocator}' for {$modelId->module}.{$modelId->lu}.{$modelId->name} should range {$modelId->start} - {$modelId->end}");
        }
        if($safeSet){
            $id = $modelId->current < $id? $id: $modelId->current;
        }
        $modelId->current = $id;
        $modelId->save();
        if($modelId->current >= $modelId->end){
            $modelId->state("finished");
        }
    }
    
    /**          
     * @var String Allocator name
     */
    private $_allocator = 'default';        
    
    /**
     * Set the Model ID allocator from 'default'
     * @param type $allocator Allocator Name
     */
    public function setAllocator($allocator){
        $this->_allocator = $allocator;
    }
    
    /**
     * Overriden PHP Acive record model save with modelID
     * @param boolean $validate Validate
     */
    public function save($validate = true) {        
        $attr = static::$model_id;
        if($attr && $this->is_new_record()){            
            if(empty($this->$attr)){
                $this->$attr = static::modelIdGenerateNext($this->_allocator);
            }else{
                static::modelIdSetCurrent($this->$attr, false, $this->_allocator);
            }
        }
        return parent::save($validate);
    }
    
    /**
     * State machine configuration
     * 
     * @param array $stateConfig State machine config
     */
    public static function stateMachine($stateConfig) {
        static::$state_config = $stateConfig;
    }

    /**
     * Return array of available states
     * 
     * @return array of states
     */
    public static function getStates() {
        return static::$state_config['states'];
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
        return "{$mconf->getNamespace('model')}\\{$lu}\\" . ucfirst($model);
    }

    /**
     * Override this function to setup the model initializations.
     */
    public static function initialize() {
        
    }

    /**
     * Framework function used to initialize automatically
     */
    public static function __static() {
        static::initDefaults();
        static::initialize();
    }

    /**
     * Set the model source and setup table if not exist
     * 
     * @param string $module Module name
     * @param string $lu Logical Unit name
     * @param string $name Table name
     */
    public static function setSource($module, $lu, $name) {
        $dbDriver = \simbola\Simbola::app()->db->getDriver();
        if (!$dbDriver->tableExist($module, $lu, $name)) {
            $dbObjClassName = dbobj\AbstractDbObject::getClass($module, $lu, "table", $name);
            $dbObj = new $dbObjClassName($dbDriver);
            $dbObj->setup();            
        }
        static::$table_name = $dbDriver->getTableName($module, $lu, $name);
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
     * @param bool $onlyPublic Fetch only the public columns. Default FALSE
     * @return array
     */
    public static function Columns($onlyPublic = false) {
        $columns = array();
        if($onlyPublic){
            foreach (static::Columns() as $column) {
                if(!sstring_starts_with($column->name,"_")){
                    $columns[] = $column;
                }
            } 
        }else{
            $columns = static::connection()->columns(static::$table_name);
        }
        return $columns;
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
     * Returns the possible states of object
     * @param bool $securityCheck Seccurity Check
     * @return array States
     */
    public function getNextStates($securityCheck = false) {
        $nextStates = array();
        foreach (static::$state_config['states'] as $state) {
            if($this->getStateRule($this->_state, $state)){
                if($securityCheck){
                    $model = (object)\simbola\Simbola::app()->db->getDriver()->getSourceFromTableName(static::$table_name);
                    $permObj = new \simbola\core\component\auth\lib\PermObject(
                            $model->module, $model->lu, $model->name, "entity.state.{$state}");   
                    if(\simbola\Simbola::app()->auth->checkPermission($permObj)){
                        $nextStates[] = $state;
                    }
                }else{
                    $nextStates[] = $state;
                }
            }
        }
        return $nextStates;
    }
    
    /**
     * This function returns the state change info which can be used with the
     * system service system.state.change
     * @param bool $securityCheck Seccurity Check
     * @param array $filter Filter out states
     * @return stateChangeInfo State Change Info
     */
    public function getStateChangeInfo($securityCheck = false, $filter = array()) {
        $source = \simbola\Simbola::app()->db->getDriver()->getSourceFromTableName(static::$table_name);
        $keys = array();
        foreach (static::Keys() as $key) {
            $key = $key->name;
            $keys[$key] = $this->$key;
        }
        $stateChangeInfo = array();
        
        foreach ($this->getNextStates($securityCheck) as $state) {
            if(!in_array($state, $filter)){
                $label = \simbola\Simbola::app()->term->getModelTermName(static::$class_name, "state.{$state}");            
                $stateChangeInfo[$label] = array(
                    'model' => $source,
                    'keys' => $keys,
                    'state' => $state
                );
            }
        }
        return $stateChangeInfo;
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
                $rules = static::$state_config['rules'];
                foreach ($rules as $rule) {
                    if (isset($rule['start'])) {
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
        if ($state) {
            $rule = $this->getStateRule($this->_state, $state);
            $result = $rule !== false;
            if ($result && $rule['pre_action']) {
                foreach ($rule['pre_action'] as $action) {
                    $result &= $this->$action();
                }
            }
            if ($result) {
                $this->_state = $state;
                $result = $this->save();
            }
            if ($result && $rule['post_action']) {
                foreach ($rule['post_action'] as $action) {
                    $this->$action();
                }
            }
            return $result;
        } else {
            return $this->_state;
        }
    }

    /**
     * Get state rule
     * 
     * @param string $oldState Old State
     * @param string $newState New State
     * @return object of rule
     */
    private function getStateRule($oldState, $newState) {
        if (in_array($oldState, static::$state_config['states']) &&
                in_array($newState, static::$state_config['states'])) {
            $rules = static::$state_config['rules'];
            foreach ($rules as $rule) {
                if (key_exists("start", $rule)) {
                    $rule['from'] = null;
                    $rule['to'] = $rule['start'];                    
                }
                if (key_exists("from", $rule) && key_exists("to", $rule)) {
                    if ($rule['from'] == $oldState && $rule['to'] == $newState) {
                        //pre init
                        if (!key_exists('pre_action', $rule)) {
                            $rule['pre_action'] = false;
                        }elseif(!is_array($rule['pre_action'])){
                            $rule['pre_action'] = array($rule['pre_action']);
                        }
                        //post init
                        if (!key_exists('post_action', $rule)) {
                            $rule['post_action'] = false;
                        }elseif(!is_array($rule['post_action'])){
                            $rule['post_action'] = array($rule['post_action']);
                        }
                        return $rule;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Returns created date     
     * @return \ActiveRecord\DateTime
     */
    public function created($format = false) {
        if ($format) {
            return $this->_created->format($format);
        } else {
            return $this->_created;
        }
    }

    /**
     * Returns modified date     
     * @return \ActiveRecord\DateTime
     */
    public function modified($format = false) {
        if ($format) {
            return $this->_version->format($format);
        } else {
            return $this->_version;
        }
    }

    /**
     * Get dirty attributes. If param set to all = true then 
     * _version, _created, _state and _id were also includeed.
     * @param type $all default true
     * @return array Attributes
     */
    public function dirty_attributes($all = true) {
        $attr = parent::dirty_attributes();
        if (!$all) {
            foreach (array('_version', '_created', '_state', '_id') as $key) {
                if (array_key_exists($key, $attr)) {
                    unset($attr[$key]);
                }
            }
        }
        return $attr;
    }

}

?>
