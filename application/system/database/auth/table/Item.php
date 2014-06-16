<?php
namespace application\system\database\auth\table;
/**
 * Description of Item
 *
 * @author FARFLK
 */
class Item extends \simbola\core\application\dbobj\AppDbTable{
    
    public function init() {
        $this->setModule('system');
        $this->setLu("auth");
        $this->setName("item");
    }
    
    public function setup() { 
        //r0
        $this->addTable();
        //r1        
        $this->addColumns(array(            
            'item_id BIGINT PRIMARY KEY AUTO_INCREMENT',
            'item_type BIGINT',
            'item_name VARCHAR(500) UNIQUE',
            'item_description TEXT'
        ));        
    }
}
