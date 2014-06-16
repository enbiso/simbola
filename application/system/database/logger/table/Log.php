<?php
namespace application\system\database\logger\table;
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
        //r0
        $this->addTable();
        //r1 - setup columns
        $this->addColumns(array(
            'id bigint primary key auto_increment',            
            'type VARCHAR(10)',
            'trace VARCHAR(1000)',
            'message VARCHAR(1000)'
        ));
    }
}
