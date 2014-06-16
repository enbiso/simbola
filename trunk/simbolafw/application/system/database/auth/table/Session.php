<?php
namespace application\system\database\auth\table;
/**
 * Description of Session
 *
 * @author FARFLK
 */
class Session extends \simbola\core\application\dbobj\AppDbTable{
    
    public function init() {
        $this->setModule('system');
        $this->setLu("auth");
        $this->setName("session");
    }
    
    public function setup() { 
        //r0
        $this->addTable();
        //r1
        $this->addColumns(array(
            'id BIGINT PRIMARY KEY AUTO_INCREMENT',            
            'client_addr VARCHAR(50)',
            'user_id BIGINT',
            'skey TEXT',
            'description TEXT'
        ));
        $this->addForeignKeys(array(
            'fkey_session_user' => array('user_id', 'system', 'auth', 'user', 'user_id'),
        ));
    }
}
