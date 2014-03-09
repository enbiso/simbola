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
        //table created by framework execution. dummy table definition for the security
    }
}
