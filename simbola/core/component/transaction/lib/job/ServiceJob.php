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
     * @param string $content Content
     * @return string Output
     */
    protected function execute($content) {
        $username = $this->dbObj->user->user_name;
        $skey = \simbola\Simbola::app()->auth->login($username);
        $serviceClient = new \simbola\core\component\system\lib\ServiceClient();
        $serviceClient->auth = array(
            'username' => $username,
            'skey' => $skey );
        $content = (object)json_decode($content);
        $serviceClient->module = $content->module;
        $serviceClient->service = $content->service;
        $serviceClient->action = $content->action;
        $serviceClient->params = json_decode($content->params, true);                
        if(is_array($serviceClient->params)){
            return var_export($serviceClient->execute(),true);
        }else{
            throw new \Exception('Parameter should be an array');
        }
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
    public static function createDbInstance($module, $service, $action, $params, $priority, $queueId) {
        $content = json_encode(array(
            'module' => $module,
            'service' => $service,
            'action' => $action,
            'params' => $params,
        ));
        return self::dbInstance("service", $content, $priority, $queueId);
    }

}
