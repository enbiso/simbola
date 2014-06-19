<?php

namespace application\system\model\auth;

/**
 * Description of AuthUser
 *
 * @author Faraj
 * @property string $user_name User Name
 * @property int $user_id User ID
 * @property string $user_password Password
 * @property boolean $user_active User Active
 */
class User extends \simbola\core\application\AppModel {
    static  //config params
            $table_name, 
            $primary_key, 
            $class_name, 
            //relationships
            $has_many, 
            $belongs_to, 
            //alias
            $alias_attribute,
            //validations system
            $validates_uneditable,
            //validations phpAR
            $validates_presence_of, 
            $validates_size_of, 
            $validates_length_of, 
            $validates_exclusion_of,
            $validates_inclusion_of,
            $validates_format_of,
            $validates_numericality_of,
            $validates_uniqueness_of;
    
    public static function initialize() {
        self::setClass(__CLASS__);
        self::setSource("system", "auth", "user");
        self::primaryKey('user_id');
        self::hasMany(array('sessions', 
            'class_name' => '\application\system\model\auth\Session', 
            'foreign_key' => 'user_id', 
            'primary_key' => 'user_id'));        
        self::aliasAttribute('user_name', 'username');               
    }
    
    public function changePassword($current, $new, $repeat) {
        $rbap = \simbola\Simbola::app()->auth->getRBAP();
        $data['status'] = false;
        if (!$rbap->user_authenticate($this->user_name, $current)) {
            $data['message'] = AuthUser::Term("change_password.authentication_failed");
        } elseif ($new != $repeat) {
            $data['message'] = AuthUser::Term("change_password.password_mismatch");
        } else {
            $rbap->user_reset_password($this->user_name, $new);
            $data['status'] = true;
            $data['message'] = AuthUser::Term("change_password.success");
        }
        return $data;
    }
}

?>
