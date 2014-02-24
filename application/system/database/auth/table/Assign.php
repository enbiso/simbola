<?php
namespace \application\system\database\auth\table;
/**
 * Description of Assign
 *
 * @author FARFLK
 */
class Assign extends \application\system\library\dbsetup\Table{
    
    public function init() {
        $this->setModule('system');
        $this->setLu("auth");
        $this->setName("assign");
    }
    
    public function setup() { 
        //table created by framework execution. dummy table definition for the security
    }
}
