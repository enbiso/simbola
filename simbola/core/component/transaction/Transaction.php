<?php

namespace simbola\core\component\transaction;

/**
 * Description of Cron
 *
 * @author farflk
 */
class Transaction extends \simbola\core\component\system\lib\Component {    
    
    /**
     * initialization
     */
    public function init() {
        parent::init();
        if($this->isNewInstallation()){
            $this->setupTable("system", "transaction", "cron");
            $this->setupTable("system", "transaction", "queue");
            $this->setupTable("system", "transaction", "job");
            $this->setupTable("system", "transaction", "cronQueue");
        }
    }
    
    /**
     * Setup database table
     * @param type $module Module
     * @param type $lu LU Name
     * @param type $name Table name
     */
    private function setupTable($module, $lu, $name) {
        $objName = \simbola\core\application\dbobj\AbstractDbObject::getClass($module, $lu, "table", $name);
        $obj = new $objName(\simbola\Simbola::app()->db->getDriver());
        $obj->execute(true);
    }
    
    /**
     * Function used to call on Cron jobs
     * @param type $cronId Cron Identification
     */
    public function cron($cronId) {
        $cronUtil = new lib\CronUtil();
        $jobUtil = new lib\JobUtil();
        try {               
            if ($cronUtil->initialize($cronId)) {                                
                $cronUtil->beginCron();                
                $queueIds = $cronUtil->getQueueIds();                                                        
                while ($job = $jobUtil->getNextJob($queueIds)) {
                    $job->perform();                  
                }
                $cronUtil->finishCron();
            }else{                
                return false;
            }
        } catch (\Exception $ex) {
            slog_syserror(__METHOD__, "Cron Error: " . $ex->getMessage());
            $cronUtil->finishCron();
            return false;
        }
    }
    
    
    /**
     * Check if the component database object exist
     * 
     * @return boolean
     */
    public function isNewInstallation() {
        $dbDriver = \simbola\Simbola::app()->db->getDriver();
        return !$dbDriver->tableExist("system", "transaction", "cron");
    }

}
