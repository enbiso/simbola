<?php
namespace application\system\database\auth\table;
/**
 * Description of Assign
 *
 * @author FARFLK
 */
class User extends \simbola\core\application\dbobj\AppDbTable{
    
    public function init() {
        $this->setModule('system');
        $this->setLu("auth");
        $this->setName("user");
    }
    
    public function setup() { 
        //r0
        $this->addTable();
        //r1
        $this->addColumns(array(
            'user_id BIGINT PRIMARY KEY AUTO_INCREMENT',
            'user_active BOOL DEFAULT TRUE',
            'user_name VARCHAR(100) UNIQUE',
            'user_password TEXT'
        ));
    }
}
