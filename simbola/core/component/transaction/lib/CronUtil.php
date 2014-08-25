<?php
namespace simbola\core\component\transaction\lib;

/**
 * Description of CronUtil
 *
 * @author Faraj
 */
class CronUtil {
    
    /**
     * The cron Object
     * @var \application\system\model\transaction\Cron 
     */
    private $cron;
    
    /**
     * Finishing the cron job
     */
    function finishCron() {
        $this->cron->job_count--;
        if ($this->cron->job_count <= 0) {
            $this->cron->job_count = 0;
        }
        $this->cron->save();
        if ($this->cron->job_count == 0) {
            $this->cron->state("ready");
        }
    }

    /**
     * Begin the cron job
     */
    function beginCron() {
        switch ($this->cron->state()) {
            case 'execute':
                $this->cron->job_count++;
                $this->cron->save();
                break;
            case 'ready':
                $this->cron->job_count = 1;
                $this->cron->save();
                $this->cron->state("execute");
                break;
        }
    }

    /**
     * Update cron table
     * @param type $cronId Cron Identification id
     * @return boolean
     */
    function initialize($cronId) {
        $this->cron = \application\system\model\transaction\Cron::find('first', array('id' => $cronId));
        if ($this->cron == NULL) { //create a new CRON object of not executed
            $this->cron = new \application\system\model\transaction\Cron(array(
                'id' => $cronId,
                'execute_count' => 1,
                'last_execute' => new \DateTime(time()),
            ));
            return $this->cron->save();
        } elseif($this->cron->state() == 'ready'){
            $this->cron->interval = time() - $this->cron->last_execute->getTimestamp();
            $this->cron->last_execute->setTimestamp(time());
            $this->cron->execute_count++;
            return $this->cron->save();
        }else{
            return false;
        }
    }
    
    public function getQueueIds() {        
        $queueIds = array();
        foreach($this->cron->queues as $queue){
            $queueIds[] = $queue->id;
        }
        return $queueIds;
    }
}
