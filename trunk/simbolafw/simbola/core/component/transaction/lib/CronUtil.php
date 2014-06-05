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
        if ($this->cron == NULL) {
            $this->cron = new \application\system\model\transaction\Cron(array(
                'id' => $cronId,
                'execute_count' => 0,
            ));
        } else {
            $this->cron->interval = time() - date_timestamp_get($this->cron->last_execute);
        }
        $this->cron->execute_count++;
        $this->cron->last_execute = date("Y-m-d H:i:s");
        return $this->cron->save();
    }
    
    public function getQueueIds() {
        if($this->cron instanceof \application\system\model\transaction\Cron){
            return $this->cron->getQueues("id");
        }
        return array();
    }
}
