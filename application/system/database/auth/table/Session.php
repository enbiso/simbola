<?php
namespace \application\system\database\auth\table;
/**
 * Description of Session
 *
 * @author FARFLK
 */
class Session extends \application\system\library\dbsetup\Table{
    
    public function init() {
        $this->setLu("auth");
        $this->setName("assign");
    }
    
    public function setup() { 
        //table created by framework execution. dummy table definition for the security
    }
}
