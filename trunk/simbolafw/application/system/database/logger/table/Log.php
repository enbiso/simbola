<?php
namespace \application\system\database\logger\table;
/**
 * Description of Log
 *
 * @author FARFLK
 */
class Log extends \simbola\core\application\dbobj\AppDbTable{
    
    public function init() {
        $this->setModule('system');
        $this->setLu("logger");
        $this->setName("log");
    }
    
    public function setup() { 
        //table created by framework execution. dummy table definition for the security
    }
}
