<?php
namespace simbola\core\component\transaction\lib\job;
/**
 * Description of PHPScriptJob
 *
 * @author Faraj
 */
class PhpScriptJob extends AbstractJob{
    
    /**
     * Execute the job
     * @param type $content Content
     * @return type Output
     */
    protected function execute($content) {        
        ob_start();
        eval($content);
        return ob_get_clean();        
    }
    
    /**
     * Create DB Instance of PHP Script job
     * @param type $script PHP Script
     * @param int $priority Priority
     * @param type $queueId Queue ID
     * @return \application\system\model\transaction\Job
     */
    public static function createDbInstance($script, $priority, $queueId) {
        return self::dbInstance("php", $script, $priority, $queueId);
    }

}
