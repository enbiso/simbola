<?php
namespace application\system\database\auth\table;
/**
 * Description of Child
 *
 * @author FARFLK
 */
class Child extends \simbola\core\application\dbobj\AppDbTable{
    
    public function init() {
        $this->setModule('system');
        $this->setLu("auth");
        $this->setName("child");
    }
    
    public function setup() { 
        //table created by framework execution. dummy table definition for the security
    }
}
