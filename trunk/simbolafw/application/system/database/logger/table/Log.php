<?php
namespace \application\system\database\logger\table;
/**
 * Description of Log
 *
 * @author FARFLK
 */
class Log extends \application\system\library\dbsetup\Table{
    
    public function init() {
        $this->setModule('system');
        $this->setLu("logger");
        $this->setName("log");
    }
    
    public function setup() { 
        //table created by framework execution. dummy table definition for the security
    }
}
