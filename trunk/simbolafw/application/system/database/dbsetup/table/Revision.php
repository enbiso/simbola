<?php
namespace application\system\database\dbsetup\table;

/**
 * Description of Revision
 *
 * @author FARFLK
 */
class Revision extends \simbola\core\application\dbobj\AppDbTable{
    
    public function init() {
        $this->setModule('system');
        $this->setLu("dbsetup");
        $this->setName("revision");
    }
    
    public function setup() { 
        //r0
        $this->addTable();
        //r1 - setup columns
        $this->addColumns(array(
            'id int primary key auto_increment',
            'rev varchar(200)',
            'content text',
        ));
    }
}
