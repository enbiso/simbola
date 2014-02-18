<?php
namespace simbola\core\component\system\lib;
/**
 * Description of ServiceClient
 *
 * @author Faraj
 */
class ServiceClient {
    private $request  = array(
        'params' => null,
        'auth' => array(
            'username' => null,
            'skey' => null),
        'module' => null,
        'service' => null,
        'action' => null);    
    
    public function __construct() {
        $auth = \simbola\Simbola::app()->auth;
        $this->request['auth']['username'] = $auth->getUsername();
        $this->request['auth']['skey'] = $auth->getSessionKey();
    }
    
    public function __set($name, $value) {
        $this->request[$name] = $value;
    }
    
    public function __get($name) {
        return $this->request[$name];
    }
    
    public function execute() {
        $currPost = $_POST;
        $_POST = $this->request;
        
        $servicePage = new \simbola\core\component\url\lib\Page;
        $servicePage->type = \simbola\core\component\url\lib\Page::$TYPE_SERVICE;
        $servicePage->module = $this->module;
        $servicePage->logicalUnit = $this->service;
        $servicePage->action = $this->action;
        $servicePage->params = $this->params;
        
        $serviceName = \simbola\Simbola::app()->getPageClass($servicePage);
        $service = new $serviceName();
        $service->setReturn(true);
        $service->init();
        $output = $service->run($servicePage);
        $service->destroy();
        $_POST = $currPost;
        if($output['header']['status'] != \simbola\core\application\AppService::STATUS_OK){
            if($output['header']['status'] == \simbola\core\application\AppService::STATUS_USER_ERROR){
                throw new \Exception($output['body']['message']);
            }else{
                slog_syserror(__METHOD__,var_export($output,true));
                throw new \Exception($output['header']['status']);
            }
        }else{
            return $output;
        }        
    }
}

?>
