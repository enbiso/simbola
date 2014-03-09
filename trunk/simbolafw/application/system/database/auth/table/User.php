<?php
namespace \application\system\database\auth\table;
/**
 * Description of Assign
 *
 * @author FARFLK
 */
class Assign extends \simbola\core\application\dbobj\AppDbTable{
    
    public function init() {
        $this->setModule('system');
        $this->setLu("auth");
        $this->setName("user");
    }
    
    public function setup() { 
        //table created by framework execution. dummy table definition for the security
    }
}
