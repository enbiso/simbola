<?php
namespace application\system\database\dbsetup\table;

/**
 * Description of Revision
 *
 * @author FARFLK
 */
class Revision extends \application\system\library\dbsetup\Table{
    
    public function init() {
        $this->setLu("dbsetup");
        $this->setName("revision");
    }
    
    public function setup() { 
        //table created by framework execution. dummy table definition for the security
    }
}
