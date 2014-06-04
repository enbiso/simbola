<?php
namespace application\system\database\transaction\table;
/**
 * Description of cron
 *
 * Model 	: cron
 * Created	: 04Jun2014
 * Purpose 	: Cron Db-table
 *
 * Change Logs
 * -----------------------------------------------------------
 * 04Jun2014 faraj: Created the table cron
 *  
 * @author faraj
 */
class Cron extends \simbola\core\application\dbobj\AppDbTable{
    
    public function init() {
        $this->setModule('system');
        $this->setLu('transaction');
        $this->setName('cron');
    }
    
    public function setup() { 
        //r0
        $this->addTable();
        //r1 - setup columns
        $this->addColumns(array(
            'id varchar(20) PRIMARY KEY',
            'last_executed TIMESTAMP',
            'executed_count BIGINT DEFAULT 0',
        ));
    }
}
