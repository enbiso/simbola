<?php
namespace application\system\database\transaction\table;
/**
 * Description of cronQueue
 *
 * Model 	: cronQueue
 * Created	: 05Jun2014
 * Purpose 	: Cron Queue Db-table
 *
 * Change Logs
 * -----------------------------------------------------------
 * 05Jun2014 faraj: Created the table cronQueue
 *  
 * @author faraj
 */
class CronQueue extends \simbola\core\application\dbobj\AppDbTable{
    
    public function init() {
        $this->setModule('system');
        $this->setLu('transaction');
        $this->setName('cron_queue');
    }
    
    public function setup() { 
        //r0
        $this->addTable();
        //r1 - setup columns
        $this->addColumns(array(
            'cron_id VARCHAR(20)',
            'queue_id VARCHAR(20)',
        ));
        $this->addForeignKeys(array(
            'fkey_cq_queue' => array('queue_id', 'system', 'transaction', 'queue', 'id'),
            'fkey_cq_cron' => array('cron_id', 'system', 'transaction', 'cron', 'id'),
        ));
        $this->addPrimaryKey(array('cron_id', 'queue_id'));
    }
}