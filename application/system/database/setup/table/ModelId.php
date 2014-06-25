<?php
namespace application\system\database\setup\table;
/**
 * Description of modelId
 *
 * Model 	: modelId
 * Created	: 24Jun2014
 * Purpose 	: Model Id Db-table
 *
 * Change Logs
 * -----------------------------------------------------------
 * 24Jun2014 faraj: Created the table modelId
 *  
 * @author faraj
 */
class ModelId extends \simbola\core\application\dbobj\AppDbTable{
    
    public function init() {
        $this->setModule('system');
        $this->setLu('setup');
        $this->setName('model_id');
    }
    
    public function setup() { 
        //r0
        $this->addTable();
        //r1 - setup columns
        $this->addColumns(array(
            'id int primary key auto_increment',
            'module varchar(100)',
            'lu varchar(100)',
            'name varchar(100)',
            'user_id bigint',
            'start bigint',
            'end bigint',
            'current bigint',
        ));
        $this->addForeignKeys(array(
            'fkey_muser' => array('user_id', 'system', "auth", "user", "user_id"),
        ));
    }
}
