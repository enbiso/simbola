<?php
namespace application\system\database\setup\table;

/**
 * Description of Revision
 *
 * @author FARFLK
 */
class DbRevision extends \simbola\core\application\dbobj\AppDbTable{
    
    public function init() {
        $this->setModule('system');
        $this->setLu("setup");
        $this->setName("db_revision");
    }
    
    public function setup() { 
        $this->enableRev = false;
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
