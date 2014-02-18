<?php
namespace application\web\model\core;
/**
 * Description of Model
 *
 * Model 	: content
 * Created	: 17Feb2014
 * Purpose 	: Content Model
 *
 * Change Logs
 * -----------------------------------------------------------
 * 17Feb2014 faraj: Created the model content
 *  
 * @author faraj
 */
class Content extends \simbola\core\component\db\activerecord\ActiveModel{
    //put your code here
    static $class_name, $table_name, $belongs_to, $has_many;
    public static function initialize() {
        self::setClass(__CLASS__);
        self::setSource("web", 'core', 'content');
    }
    
    public static function getContent($idKey) {
        $content = self::find_by_id($idKey);
        if($content == null){
            $content = self::create(array('id'=>$idKey,'description'=>$idKey, 'content' => "#$idKey#"));
        }
        return $content->content;
    }
}

?>
