<?php
namespace simbola\core\component\transaction;
/**
 * Description of Cron
 *
 * @author farflk
 */
class Transaction extends \simbola\core\component\system\lib\Component{       
    
    /**
     * Function used to call on Cron jobs
     * @param type $cronId Cron Identification
     */
    public function cron($cronId) {
        if(!$this->updateCronTable($cronId)){
            slog_syserror(__METHOD__, "Cron table update failed for Cron ID {$cronId}");   
            return false;
        }
    }
    
    /**
     * Update cron table
     * @param type $cronId Cron Identification id
     * @return type
     */
    private function updateCronTable($cronId) {
        $cron = \application\system\model\transaction\Cron::find('first', array('id' => $cronId));
        if($cron == NULL){
            $cron = new \application\system\model\transaction\Cron();
            $cron->id = $cronId;
            $cron->executed_count = 0;
        }
        $cron->executed_count++;
        $cron->last_executed = date("Y-m-d H:i:s");
        return $cron->save();
    }
    
}
