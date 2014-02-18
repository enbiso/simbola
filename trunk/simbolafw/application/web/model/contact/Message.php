<?php
namespace application\web\model\contact;
/**
 * Description of Model
 *
 * Model 	: message
 * Created	: 17Feb2014
 * Purpose 	: Message Model
 *
 * Change Logs
 * -----------------------------------------------------------
 * 17Feb2014 faraj: Created the model message
 *  
 * @author faraj
 */
class Message extends \simbola\core\component\db\activerecord\ActiveModel{
    //put your code here
    static $class_name, $table_name, $belongs_to, $has_many;
    //variable for capcha
    public $capcha;
    
    public static function initialize() {
        self::setClass(__CLASS__);
        self::setSource("web", 'contact', 'message');
    }
    
    public function getCapchaImage(){
        $capcha = new \application\system\library\capcha\SCapcha();
        \simbola\Simbola::app()->session->set('web_contact_message_capcha_code',$capcha->getCode());
        return $capcha->getImageB64();
    }
    
    public function validateCapcha() {
        return \simbola\Simbola::app()->session->get('web_contact_message_capcha_code') == $this->capcha;
    }
}

?>
