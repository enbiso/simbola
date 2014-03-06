<?php
namespace simbola\core\component\system\lib;
/**
 * ServiceClient definitions
 * PHP client for the simbola services
 *
 * @author Faraj Farook
 * 
 * @property array $params Parameters
 * @property array $auth Authentication information
 * @property string $module Service module name
 * @property string $service Service name
 * @property string $action Service action name
 */
class ServiceClient {
    /**
     * This contains the request data
     * @var array 
     */
    private $request  = array(
        'params' => null,
        'auth' => array(
            'username' => null,
            'skey' => null),
        'module' => null,
        'service' => null,
        'action' => null);    
    
    /**
     * Contructor overriden
     */
    public function __construct() {
        $auth = \simbola\Simbola::app()->auth;
        $this->request['auth']['username'] = $auth->getUsername();
        $this->request['auth']['skey'] = $auth->getSessionKey();
    }
    
    /**
     * Used by the PHP interpretter to access the request values as 
     * properties
     * 
     * @param string $name Name of the request
     * @param mixed $value Value of the request
     */
    public function __set($name, $value) {
        $this->request[$name] = $value;
    }
    
    /**
     * Used by the PHP interpretter to access the request values as 
     * properties
     * 
     * @param string $name Name of the request
     * @return mixed Value of the request params     
     */
    public function __get($name) {
        return $this->request[$name];
    }
    
    /**
     * Used to execute the client and fettch the data
     * 
     * @return mixed
     * @throws \Exception Error output from the service
     */
    public function execute() {
        $currPost = $_POST;
        $_POST = $this->request;
        
        $servicePage = new \simbola\core\component\url\lib\Page;
        $servicePage->type = \simbola\core\component\url\lib\Page::TYPE_SERVICE;
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
                throw new exception\ServiceUserException($output);
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
