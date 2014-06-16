<?php
namespace application\system\database\auth\table;
/**
 * Description of Assign
 *
 * @author FARFLK
 */
class Assign extends \simbola\core\application\dbobj\AppDbTable{
    
    public function init() {
        $this->setModule('system');
        $this->setLu("auth");
        $this->setName("assign");
    }
    
    public function setup() { 
        //r0
        $this->addTable();
        //r1        
        $this->addColumns(array(
            'user_id BIGINT',
            'item_id BIGINT'
        ));
        $this->addPrimaryKey(array('user_id', 'item_id'));
        $this->addForeignKeys(array(
            'fkey_authassign_user' => array('user_id', 'system', 'auth', 'user', 'user_id'),
            'fkey_authassign_item' => array('item_id', 'system', 'auth', 'item', 'item_id'),
        ));
    }
}
