<?php

namespace application\system\model\auth;

/**
 * Description of AuthUser
 *
 * @author Faraj
 */
class User extends \simbola\core\component\db\activerecord\ActiveModel {
    static $table_name,$primary_key,$class_name,$has_many,$alias_attribute;
    public static function initialize() {
        self::setClass(__CLASS__);
        self::setSource("system", "auth", "user");
        self::primaryKey('user_id');
        self::hasMany(array('sessions', 'class_name' => 'application\system\model\auth\Session', 'foreign_key' => 'user_id', 'primary_key' => 'user_id'));        
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
