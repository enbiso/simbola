<?php
namespace application\system\database\transaction\table;
/**
 * Description of schedule
 *
 * Model 	: schedule
 * Created	: 21Aug2014
 * Purpose 	: Schedule Db-table
 *
 * Change Logs
 * -----------------------------------------------------------
 * 21Aug2014 faraj: Created the table schedule
 *  
 * @author faraj
 */
class Schedule extends \simbola\core\application\dbobj\AppDbTable{
    
    public function init() {
        $this->setModule('system');
        $this->setLu('transaction');
        $this->setName('schedule');
    }
    
    public function setup() { 
        //r0
        $this->addTable();
        //r1 - setup columns
        $this->addColumns(array(
            'id int AUTO_INCREMENT PRIMARY KEY',
            'user_id bigint not null', 
            'valid_from timestamp not null',
            'valid_till timestamp not null',
            '`interval` bigint not null',
            'description TEXT',
            'priority INT DEFAULT 3',
            '`type` VARCHAR(20) NOT NULL',
            'queue_id VARCHAR(10)',
            'content TEXT',
        ));
        $this->addForeignKeys(array(
            'fkey_sch_q' => array('queue_id', 'system', 'transaction', 'queue', 'id'),
            'fkey_sch_u' => array('user_id', 'system', 'auth', 'user', 'user_id')
        ));
        $this->addColumns(array(           
            'next_execute timestamp',
            'execute_count bigint default 0',
        ));
        $this->addColumns(array(           
            'last_execute timestamp',
        ));
    }
}
