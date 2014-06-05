<?php
namespace application\system\database\transaction\table;
/**
 * Description of queue
 *
 * Model 	: queue
 * Created	: 05Jun2014
 * Purpose 	: Queue Db-table
 *
 * Change Logs
 * -----------------------------------------------------------
 * 05Jun2014 faraj: Created the table queue
 *  
 * @author faraj
 */
class Queue extends \simbola\core\application\dbobj\AppDbTable{
    
    public function init() {
        $this->setModule('system');
        $this->setLu('transaction');
        $this->setName('queue');
    }
    
    public function setup() { 
        //r0
        $this->addTable();
        //r1 - setup columns
        $this->addColumns(array(
            'id VARCHAR(20) PRIMARY KEY',
            'description TEXT',            
        ));
    }
}
