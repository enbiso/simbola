<?php
namespace \application\system\database\auth\table;
/**
 * Description of Item
 *
 * @author FARFLK
 */
class Item extends \application\system\library\dbsetup\Table{
    
    public function init() {
        $this->setModule('system');
        $this->setLu("auth");
        $this->setName("item");
    }
    
    public function setup() { 
        //table created by framework execution. dummy table definition for the security
    }
}
