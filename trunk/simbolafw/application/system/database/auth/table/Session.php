<?php
namespace application\system\database\auth\table;
/**
 * Description of Session
 *
 * @author FARFLK
 */
class Session extends \simbola\core\application\dbobj\AppDbTable{
    
    public function init() {
        $this->setModule('system');
        $this->setLu("auth");
        $this->setName("session");
    }
    
    public function setup() { 
        //table created by framework execution. dummy table definition for the security
    }
}
