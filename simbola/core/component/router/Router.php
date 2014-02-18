<?php

namespace simbola\core\component\router;

/**
 * Description of ROuter
 *
 * @author Faraj
 */
class Router extends \simbola\core\component\system\lib\Component {

    public function setupDefault() {
        $this->setParamDefault('DEFAULT', 'system/www/index');
        $this->setParamDefault('NOACCESS', 'system/www/noaccess');
        $this->setParamDefault('NOPAGE', 'system/www/pagenotfound');
        $this->setParamDefault('LOGIN', 'system/auth/login');
    }

    public function __get($name) {
        if (isset($this->params[$name])) {
            return $this->params[$name];
        } else {
            return "";
        }
    }

    public function route() {
        $page = \simbola\Simbola::app()->url->decode($_SERVER["REQUEST_URI"]);
        $this->execute($page);
    }

    public function execute($page) {
        $_GET = $page->params;
        switch ($page->type) {
            case \simbola\core\component\url\lib\Page::$TYPE_CONTROLLER:
                $page = $this->initWithDefaults($page);
                $this->executeController($page);
                break;
            case \simbola\core\component\url\lib\Page::$TYPE_SERVICE:
                $this->executeService($page);
                break;
        }
    }

    public function initWithDefaults($page) {
        if (is_null($page->module)){            
            $page->loadFromUrl($this->params['DEFAULT']);            
            
        }else{
            $mconf = \simbola\Simbola::app()->getModuleConfig($page->module);
            $mdefRoute = explode("/", $mconf->default_route);
            if(is_null($page->logicalUnit)){
                $page->setDefault('logicalUnit', $mdefRoute[0]);
            }
            if(is_null($page->action)){
                $page->setDefault('action', $mdefRoute[1]);
            }
        }
        return $page;
    }
    
    private function executeController($page) {
        //set default if the route is empty                
        try {
            $controller_name = \simbola\Simbola::app()->getPageClass($page);
            class_exists($controller_name);
        }catch(\Exception $ex){
            $page = new \simbola\core\component\url\lib\Page();
            $page->loadFromUrl($this->params['NOPAGE']);
            $controller_name = \simbola\Simbola::app()->getPageClass($page);
        }
        $this->params['page'] = $page;
        $controller = new $controller_name();
        $controller->init();
        $controller->run($page);
        $controller->destroy();
    }

    private function executeService($page) {
        $service_name = \simbola\Simbola::app()->getPageClass($page);
        if (class_exists($service_name)) {
            $service = new $service_name();
            $service->init();
            $service->run($page);
            $service->destroy();
        } else {
            throw new \Exception("Service not available");
        }
    }

}

?>
