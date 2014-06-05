<?php

namespace simbola\core\component\transaction;

/**
 * Description of Cron
 *
 * @author farflk
 */
class Transaction extends \simbola\core\component\system\lib\Component {    
    
    /**
     * Function used to call on Cron jobs
     * @param type $cronId Cron Identification
     */
    public function cron($cronId) {
        $cronUtil = new lib\CronUtil();
        $jobUtil = new lib\JobUtil();
        try {            
            if (!$cronUtil->initialize($cronId)) {
                slog_syserror(__METHOD__, "Cron table update failed for Cron ID {$cronId}");
                return false;
            }
            $cronUtil->beginCron();
            $queueIds = $cronUtil->getQueueIds();
            while ($job = $jobUtil->getNextJob($queueIds)) {
                $job->execute();
            }
            $cronUtil->finishCron();
        } catch (\Exception $ex) {
            slog_syserror(__METHOD__, "Cron Error: " . $ex->getMessage());
            $cronUtil->finishCron();
            return false;
        }
    }
}
