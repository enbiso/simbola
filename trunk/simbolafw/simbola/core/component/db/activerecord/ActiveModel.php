<?php

namespace simbola\core\component\db\activerecord;

/**
 * Description of AbstractActiveModel
 *
 * @author Faraj
 */
abstract class ActiveModel extends \ActiveRecord\Model {

    public static function initialize() {
        
    }

    public static function __static() {
        static::initialize();
    }

    public static function setSource($module, $lu, $name) {
        static::$table_name = \simbola\Simbola::app()->db->getTableName($module, $lu, $name);
    }

    public static function belongsTo($value) {
        static::$belongs_to[] = $value;
    }

    public static function hasOne($value) {
        static::$has_one[] = $value;
    }

    public static function hasMany($value) {
        static::$has_many[] = $value;
    }

    public static function beforeSave($value) {
        static::$before_save[] = $value;
    }

    public static function aliasAttribute($name, $alias) {
        static::$alias_attribute[$alias] = $name;
    }

    public static function primaryKey($pk) {
        static::$primary_key = $pk;
    }

    //$validates_uneditable,
    static $validates_uneditable = array();
    public static function validateUneditable($attr) {        
        static::$validates_uneditable[] = $attr;
    }
    
    //$validates_presence_of, 
    public static function validatePresenceOf($attr) {        
        static::$validates_presence_of[] = $attr;
    }
    
    //$validates_size_of, 
    public static function validateSizeOf($attr) {        
        static::$validates_size_of[] = $attr;
    }
    
    //$validates_length_of, 
    public static function validateLengthOf($attr) {        
        static::$validates_length_of[] = $attr;
    }

    //$validates_exclusion_of,
    public static function validateExclusionOf($attr) {        
        static::$validates_exclusion_of[] = $attr;
    }

    //$validates_inclusion_of,
    public static function validateInclusionOf($attr) {        
        static::$validates_inclusion_of[] = $attr;
    }   
    
    //$validates_format_of,
    public static function validateFormatOf($attr) {        
        static::$validates_format_of[] = $attr;
    }
    
    //$validates_numericality_of,
    public static function validateNumericalityOf($attr) {        
        static::$validates_numericality_of[] = $attr;
    }
    
    //$validates_uniqueness_of;
    public static function validateUniquenessOf($attr) {        
        static::$validates_uniqueness_of[] = $attr;
    }

    public static function isEditable($attribute) {
        return !(in_array($attribute, static::$validates_uneditable));
    }

    public static function setClass($cname) {
        static::$class_name = $cname;
    }

    public static function Term($field_name) {
        return \simbola\Simbola::app()->term->getModelTerm(static::$class_name, $field_name);
    }

    public static function Columns() {
        return static::connection()->columns(static::$table_name);
    }

    public static function Keys() {
        $keys = array();
        foreach (static::Columns() as $column) {
            if ($column->pk) {
                $keys[] = $column;
            }
        }
        return $keys;
    }

    public static function getSelectData($key, $field, $opts = 'all') {
        $data = static::find($opts);
        $sData = array();
        foreach ($data as $datum) {
            $sData[$datum->$key] = $datum->$field;
        }
        return $sData;
    }

    //this function overrides phpactive record default function set_timestamp
    public function set_timestamp() {
        $now = date('Y-m-d H:i:s');
        if ($this->is_new_record()) {
            if (isset($this->_id)) {
                $this->_id = uniqid('', true);
            }
            if (isset($this->_created)) {
                $this->_created = $now;
            }
        }
        if (isset($this->_version)) {
            $this->_version = $now;
        }
    }

    //uneditable check
    public function set_attributes(array $attributes) {
        if (!$this->is_new_record() && is_array(static::$validates_uneditable)) {
            foreach (static::$validates_uneditable as $attr) {
                unset($attributes[$attr]);
            }
        }
        parent::set_attributes($attributes);
    }

}

?>
