<?php
namespace application\system\model\auth;
/**
 * Description of Person
 *
 * @author Faraj
 */
class Session extends \simbola\core\component\db\activerecord\ActiveModel {
    static $table_name,$primary_key,$class_name,$belongs_to;
    public static function initialize() {
        self::setClass(__CLASS__);
        self::setSource("system", "auth", "session");
        self::belongsTo(array('user', 'class_name' => 'application\system\model\auth\User', 'foreign_key' => 'user_id', 'primary_key' => 'user_id'));
    }
}

?>