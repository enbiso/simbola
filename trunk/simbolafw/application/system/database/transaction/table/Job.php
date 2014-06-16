<?php
namespace application\system\database\transaction\table;
/**
 * Description of job
 *
 * Model 	: job
 * Created	: 05Jun2014
 * Purpose 	: Job Db-table
 *
 * Change Logs
 * -----------------------------------------------------------
 * 05Jun2014 faraj: Created the table job
 *  
 * @author faraj
 */
class Job extends \simbola\core\application\dbobj\AppDbTable{
    
    public function init() {
        $this->setModule('system');
        $this->setLu('transaction');
        $this->setName('job');
    }
    
    public function setup() { 
        //r0
        $this->addTable();
        //r1 - setup columns
        $this->addColumns(array(
            'id BIGINT AUTO_INCREMENT PRIMARY KEY',
            'user_id bigint not null', 
            'priority INT DEFAULT 3',
            'type VARCHAR(20) NOT NULL',
            'queue_id VARCHAR(10)',
            'content TEXT',
            'output TEXT',
        ));
        $this->addForeignKeys(array(
            'fkey_queue' => array('queue_id', 'system', 'transaction', 'queue', 'id'),
            'fkey_user' => array('user_id', 'system', 'auth', 'user', 'user_id')
        ));
    }
}
