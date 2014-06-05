<?php

namespace simbola\core\component\transaction\lib\job;

/**
 * Description of ServiceJob
 *
 * @author Faraj
 */
class ServiceJob extends AbstractJob {

    /**
     * Execute
     */
    protected function execute() {
        
    }

    /**
     * Create DB Instance of Service job
     * @param type $module Module Name
     * @param type $service Service
     * @param type $action Action
     * @param type $params Parameters
     * @param int $priority Priority
     * @param type $queueId Queue ID
     * @return type
     */
    public static function CreateDbInstance($module, $service, $action, $params, $priority, $queueId) {
        $content = json_encode(array(
            'module' => $module,
            'service' => $service,
            'action' => $action,
            'params' => $params,
        ));
        return self::dbInstance("service", $content, $priority, $queueId);
    }

}
