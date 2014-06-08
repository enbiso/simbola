<?php
namespace simbola\core\component\transaction\lib\job;
/**
 * Description of AbstractJob
 *
 * @author Faraj
 */
abstract class AbstractJob {
    
    /**
     * Job DB Object
     * @var \application\system\model\transaction\Job 
     */
    protected $dbObj;
    
    /**
     * Construction of Job
     * @param \application\system\model\transaction\Job $jobDbObj
     */
    public function __construct($jobDbObj) {
        $this->dbObj = $jobDbObj;
    }
        
    /**
     * Execute the job
     * @param string $content Content
     * @return string Output content
     */
    abstract protected function execute($content);    
    
    /**
     * DB Instance generator
     * @param type $type Job Type
     * @param type $content Content
     * @param integer $prioriy Priority
     * @param string $queueId Queue ID
     * @return boolean|\application\system\model\transaction\Job
     */
    protected static function dbInstance($type, $content, $prioriy, $queueId) {
        $jobDbObj = new \application\system\model\transaction\Job(array(
            'priority' => $prioriy,
            'queue_id' => $queueId,
            'type' => $type,
            'user_id' => \simbola\Simbola::app()->auth->getId(),
            'content' => $content,
        ));
        if($jobDbObj->save()){
            return $jobDbObj;
        }else{
            return FALSE;
        }
    }
    /**
     * Perform the job
     */
    function perform() {
        try {            
            $output = $this->execute($this->dbObj->content);            
            $this->complete($output);
        } catch (\Exception $ex) {
            $this->error($ex->getMessage());
        }
    }
    
    /**
     * Set job to complete
     * @param type $output Output content
     */
    private function complete($output) {
        $this->dbObj->output = $output;
        $this->dbObj->save();
        $this->dbObj->state("complete");
    }
    
    /**
     * Set job to error
     * @param type $errorMessage Error message
     */
    private function error($errorMessage) {
        $this->dbObj->output = $errorMessage;
        $this->dbObj->save();
        $this->dbObj->state("error");
    }
        
}
