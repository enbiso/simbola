<?php
namespace application\system\database\transaction\table;
/**
 * Description of scheduleJob
 *
 * Model 	: scheduleJob
 * Created	: 21Aug2014
 * Purpose 	: Schedule Job Db-table
 *
 * Change Logs
 * -----------------------------------------------------------
 * 21Aug2014 faraj: Created the table scheduleJob
 *  
 * @author faraj
 */
class ScheduleJob extends \simbola\core\application\dbobj\AppDbTable{
    
    public function init() {
        $this->setModule('system');
        $this->setLu('transaction');
        $this->setName('schedule_job');
    }
    
    public function setup() { 
        //r0
        $this->addTable();
        //r1 - setup columns
        $this->addColumns(array(
            'schedule_id int',
            'job_id bigint',
        ));
        
        $this->addPrimaryKey(array('schedule_id', 'job_id'));
        
        $this->addForeignKeys(array(
            'fkey_schj_s' => array('schedule_id', 'system', 'transaction', 'schedule', 'id'),
            'fkey_schj_j' => array('job_id', 'system', 'transaction', 'job', 'id')
        ));
    }
}
