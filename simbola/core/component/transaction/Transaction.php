<?php

namespace simbola\core\component\transaction;

/**
 * Description of Cron
 *
 * @author farflk
 */
class Transaction extends \simbola\core\component\system\lib\Component {

    private $cron;

    /**
     * Function used to call on Cron jobs
     * @param type $cronId Cron Identification
     */
    public function cron($cronId) {
        try {            
            if (!$this->updateGetCronObject($cronId)) {
                slog_syserror(__METHOD__, "Cron table update failed for Cron ID {$cronId}");
                return false;
            }
            $this->initCron();
            //$this->getNextJob();
            $this->finishCron();
        } catch (\Exception $ex) {
            slog_syserror(__METHOD__, "Cron Error: " . $ex->getMessage());
            $this->finishCron();
            return false;
        }
    }

    /**
     * Finishing the cron job
     */
    private function finishCron() {
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
     * Initializing the cron job
     */
    private function initCron() {
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
    private function updateGetCronObject($cronId) {
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

}
