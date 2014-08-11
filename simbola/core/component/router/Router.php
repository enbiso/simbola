<?php

namespace simbola\core\component\router;

/**
 * Router component definitions
 *
 * @author Faraj Farook
 * 
 * @property-read string $default Default route
 * @property-read string $noaccess No access route
 * @property-read string $nopage No page route
 * @property-read string $login Login page route 
 */
class Router extends \simbola\core\component\system\lib\Component {

    /**
     * Current page
     * @var \simbola\core\component\url\lib\Page 
     */
    private $currentPage;
    
    
    /**
     * Setup defaults
     */
    public function setupDefault() {
        $this->setParamDefault('DEFAULT', 'system/www/index');
        $this->setParamDefault('NOACCESS', 'system/www/noaccess');
        $this->setParamDefault('NOPAGE', 'system/www/pagenotfound');
        $this->setParamDefault('LOGIN', 'system/auth/login');
    }

    /**
     * PHP interpreter function to enable the access of the Router paramaters as 
     * properties
     * 
     * @param string $name Router parameter name
     * @return mixed
     */
    public function __get($name) {        
        if (isset($this->params[strtoupper($name)])) {
            return $this->params[strtoupper($name)];
        } else if($name == 'page'){
            return $this->currentPage;
        } else {
            return "";
        }
    }

    /**
     * Run dispatcher
     */
    public function dispatch() {
        $page = \simbola\Simbola::app()->url->decode($_SERVER["REQUEST_URI"]);
        $this->execute($page);
    }

    /**
     * Implementation function of page execution
     * 
     * @param \simbola\core\component\url\lib\Page $page Page to execute
     */
    private function execute($page) {
        $_GET = $page->params;
        switch ($page->type) {
            case \simbola\core\component\url\lib\Page::TYPE_CONTROLLER:                
                $page = $this->initWithDefaults($page, false);
                $this->executeController($page);
                break;
            case \simbola\core\component\url\lib\Page::TYPE_SERVICE:
                $this->executeService($page);
                break;
        }
    }

    /**
     * Initialize the page with default information of router
     * 
     * @param \simbola\core\component\url\lib\Page $page Page of execution
     * @param boolean $withNoPage initialize the page with NOPAGE data on error
     * @return \simbola\core\component\url\lib\Page
     */
    public function initWithDefaults($page, $withNoPage = true) {
        if (is_null($page->module)){            
            $page->loadFromUrl($this->params['DEFAULT']);            
        }else{
            try{
                $mconf = \simbola\Simbola::app()->getModuleConfig($page->module);
                $mdefRoute = explode("/", $mconf->default_route);
                if(is_null($page->logicalUnit)){
                    $page->setDefault('logicalUnit', $mdefRoute[0]);
                }
                if(is_null($page->action)){
                    $page->setDefault('action', $mdefRoute[1]);
                }
            }  catch (\Exception $ex){
                if($withNoPage){
                    $page->loadFromUrl($this->params['NOPAGE']);            
                }
            }
        }
        return $page;
    }
    
    /**
     * Returns current page
     * 
     * @return \simbola\core\component\url\lib\Page
     */
    public function getCurrentPage() {
        return $this->currentPage;
    }
    
    /**
     * Execution of the controller
     * 
     * @param \simbola\core\component\url\lib\Page $page
     */
    private function executeController($page) {                    
        $this->currentPage = $page;                    
        try {
            $controller_name = \simbola\Simbola::app()->getPageClass($page, true);
        }catch(\Exception $ex){
            $page = new \simbola\core\component\url\lib\Page();
            $page->loadFromUrl($this->params['NOPAGE']);
            $controller_name = \simbola\Simbola::app()->getPageClass($page);
        }
        $controller = new $controller_name();
        $controller->init();
        $controller->run($page);
        $controller->destroy();
    }

    /**
     * Execution of the service
     * 
     * @param \simbola\core\component\url\lib\Page $page
     * @throws \Exception
     */
    private function executeService($page) {
        try{
            $this->currentPage = $page;            
            $service_name = \simbola\Simbola::app()->getPageClass($page, true);                
            $service = new $service_name();
            $service->init();
            $service->run($page);
            $service->destroy();
        }  catch (\Exception $ex) {
            if($ex->getCode() == 202) {//from module
                $page = new \simbola\core\component\url\lib\Page();
                $page->loadFromUrl('system/www/service_api');
                $this->executeController($page);
            }else if(\simbola\Simbola::app()->isProd()){
                header('X-PHP-Response-Code: 501', true, 501);
                echo "ERROR: " . $ex->getMessage();
            }else{
                throw $ex;
            }
        }
    }

}

?>
