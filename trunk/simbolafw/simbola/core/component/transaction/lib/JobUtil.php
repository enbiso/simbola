<?php
namespace simbola\core\component\transaction\lib;
/**
 * Description of JobUtil
 * The utility class for handling jobs in transaction
 *
 * @author Faraj
 */
class JobUtil {
    
    /**
     * Get Next Job
     * @param array $queueIds Array of Queues ID
     * @return \simbola\core\component\transaction\lib\job\AbstractJob Job
     */
    function getNextJob($queueIds) {        
        $options['conditions'] = array('_state = ? AND queue_id IN (?)', 'new', array('QUEUE01'));        
        $options['order'] = 'priority desc';        
        $jobObj = \application\system\model\transaction\Job::find('first', $options);
        if(is_null($jobObj)){
            return false;
        }        
        if($jobObj->state('execute')){
            $job = $this->jobFactory($jobObj);
            if($job){
                return $job;
            }else{
                $jobObj->state("error");
                $jobObj->output = "invalid job type";
                $jobObj->save();
                return $this->getNextJob($queueIds);
            }
        }else{
            return $this->getNextJob($queueIds);
        }
    }
    
    /**
     * Job factory
     * @param \application\system\model\transaction\Job $jobObj
     * @return \simbola\core\component\transaction\lib\job\AbstractJob
     */
    function jobFactory($jobObj) {
        switch ($jobObj->type) {
            case "php":
                return new job\PhpScriptJob($jobObj);
            case "service":
                return new job\ServiceJob($jobObj);
            default:
                return false;
        }
    }    
}
