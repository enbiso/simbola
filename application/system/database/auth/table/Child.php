<?php
namespace application\system\database\auth\table;
/**
 * Description of Child
 *
 * @author FARFLK
 */
class Child extends \simbola\core\application\dbobj\AppDbTable{
    
    public function init() {
        $this->setModule('system');
        $this->setLu("auth");
        $this->setName("child");
    }
    
    public function setup() { 
        //r0
        $this->addTable();
        //r1
        $this->addColumns(array(
            'parent_id BIGINT',
            'child_id BIGINT'
        ));
        $this->addPrimaryKey(array('parent_id', 'child_id'));
        $this->addForeignKeys(array(
            'fkey_authchild_parent' => array('parent_id', 'system', 'auth', 'item', 'item_id'),
            'fkey_authchild_child' => array('child_id', 'system', 'auth', 'item', 'item_id'),
        ));
    }
}
