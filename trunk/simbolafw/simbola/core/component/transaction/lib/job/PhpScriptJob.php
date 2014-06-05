<?php
namespace simbola\core\component\transaction\lib\job;
/**
 * Description of PHPScriptJob
 *
 * @author Faraj
 */
class PhpScriptJob extends AbstractJob{
    
    /**
     * Execute
     */
    protected function execute() {
        
    }
    
    /**
     * Create DB Instance of PHP Script job
     * @param type $script PHP Script
     * @param int $priority Priority
     * @param type $queueId Queue ID
     * @return \application\system\model\transaction\Job
     */
    public static function CreateDbInstance($script, $priority, $queueId) {
        return self::dbInstance("php", $script, $priority, $queueId);
    }

}
