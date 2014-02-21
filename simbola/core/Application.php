<?php

namespace simbola\core;

/**
 * Description of Application
 *
 * @author Faraj
 */
class Application {

    public $components = array();
    public $sysParams;

    public function __construct() {
        $this->import('core/initlib/include');
    }
    
    public function setup($params) {
        $this->sysParams = $params;
        $this->initParam('APPNAME', "Simbola Application");
        $this->initParam('SERVICE_API', "service_api");
        $this->initParam('BASE', "application");
        $this->initParam('ERROR_LEVEL', E_PROD);
        $this->errorSetup();
        $this->includePathSetup();
        $this->importComponents();
        $this->fetchModuleNames();
        $this->import('core/helper');        
    }
    
    public function config($config) {
        foreach ($config as $name => $params) {
            $this->component($name, $params);
        }
    }

    private function initParam($name, $default) {
        if (!isset($this->sysParams[$name])) {
            $this->sysParams[$name] = $default;
        }
    }

    private function includePathSetup() {
        set_include_path(get_include_path() . PATH_SEPARATOR . $this->basepath('fw'));
        set_include_path(get_include_path() . PATH_SEPARATOR . $this->basepath('app'));
    }

    private function errorSetup() {
        if ($this->isProd()) {
            error_reporting(false);
        } elseif ($this->isDev()) {
            error_reporting($this->sysParams['ERROR_LEVEL']);
        }
    }

    public function isDev() {
        return $this->sysParams['ERROR_LEVEL'] != E_PROD;
    }

    public function isProd() {
        return strtoupper($this->sysParams['ERROR_LEVEL']) == E_PROD;
    }

    public function getParams($keys = null) {
        if (!is_null($keys)) {
            $params = array();
            foreach ($keys as $key) {
                $params[$key] = $this->getParam($key);
            }
            return $params;
        } else {
            return $this->sysParams;
        }
    }

    private $moduleNames = array();

    private function fetchModuleNames() {
        $path = $this->basepath("app") . DIRECTORY_SEPARATOR
                . $this->getParam('BASE') . DIRECTORY_SEPARATOR . "*";
        foreach (glob($path, GLOB_ONLYDIR) as $modulePath) {
            $this->moduleNames[] = basename($modulePath);
        }
    }

    public function getModuleNames() {
        return $this->moduleNames;
    }

    public function getParam($name) {
        if (!array_key_exists($name, $this->sysParams)) {
            throw new \Exception("Invalid System Parameter '{$name}' Requested");
        }
        return $this->sysParams[$name];
    }

    //fucntion to get namespaces
    public function getAppNameSpace() {
        return $this->getParam('BASE');
    }
    
    public function getModuleNameSpace($moduleName, $param = null) {
        $mconf = $this->getModuleConfig($moduleName);
        $ns = $this->getAppNameSpace()."\\".$moduleName;
        if($param != null){
            $ns .= "\\" . $mconf->$param;
        }
        return $ns;
    }
    //functions to get the paths
    public function getModuleBase($moduleName, $param = null) {
        $mconf = $this->getModuleConfig($moduleName);
        $path = $this->getAppBase() . DIRECTORY_SEPARATOR . $mconf->name;
        if($param != null){
            $path .= DIRECTORY_SEPARATOR . $mconf->$param;
        }
        return $path;
    }
    
    public function getAppBase() {
        return $this->basepath('app') . DIRECTORY_SEPARATOR . $this->getParam('BASE');
    }

    public function basepath($type) {
        switch (strtolower($type)) {
            case 'fw':
                return dirname(dirname(__FILE__));
            case 'app':
                return $this->sysParams['BASEPATH'];
        }
    }

    public function import($source, $from_fw = true) {
        $source = $this->basepath($from_fw ? 'fw' : 'app') . DIRECTORY_SEPARATOR . $source;
        if (is_dir($source)) {
            foreach (glob($source . "/*.php") as $filename) {
                include_once $filename;
            }
        } else {
            include_once $source . ".php";
        }
    }

    public function importComponents() {
        $compBasePath = $this->basepath('fw') . DIRECTORY_SEPARATOR . "core/component/*";
        foreach (glob($compBasePath) as $compPath) {
            if (is_dir($compPath)) {
                $compName = basename($compPath);
                $className = "\\simbola\\core\\component\\{$compName}\\" . ucfirst($compName);
                $this->components[$compName] = new $className();
                $this->components[$compName]->setupDefault();
            }
        }
    }

    public function component($name, $params = array()) {
        $this->components[$name]->setParams($params);
        $this->components[$name]->setup();
    }

    public function __get($name) {
        return $this->components[$name];
    }

    public function execute() {
        $this->init();
        $this->perform();
        $this->cleanup();
    }

    private function perform() {
        $this->router->route();
    }

    private function init() {
        foreach ($this->components as $component) {
            $component->init();
        }
    }

    private function cleanup() {
        foreach ($this->components as $module) {
            $module->destroy();
        }
    }

    public function fwBasePath() {
        return dirname(__FILE__);
    }

    //module config
    private $moduleConfigCache = array();

    public function getModuleConfig($moduleName) {
        if (empty($moduleName)) {            
            throw new \Exception("Module cannot be empty");
        }
        $config = null;
        if (array_key_exists($moduleName, $this->moduleConfigCache)) {
            $config = $this->moduleConfigCache[$moduleName];
        } else {
            $app = \simbola\Simbola::app();
            $moduleConfig = '\\' . $app->getParam('BASE') . '\\' . $moduleName . '\\Config';            
            try {
                $config = new $moduleConfig;
                $config->setDefault($this->getParam('DEFAULT'));
                $this->moduleConfigCache[$moduleName] = $config;            
            } catch (\Exception $ex){
                $config = null;
                throw new \Exception("Module {$moduleName} not found");
            }
        }
        return $config;
    }

    //names and path 
    public function getDBScriptBase($module_name) {        
        $moduleConfig = $this->getModuleConfig($module_name);
        return $moduleConfig->getPath('database', false);
    }

    public function getPageClass($page, $safeCheck = false) {
        $app = \simbola\Simbola::app();
        $moduleConfig = $this->getModuleConfig($page->module);
        if ($page->type == component\url\lib\Page::$TYPE_CONTROLLER) {
            $class = $moduleConfig->getNamespace('controller') . '\\' . ucfirst($page->logicalUnit) . "Controller";
        } else {
            $class = $moduleConfig->getNamespace('service') . '\\' . ucfirst($page->logicalUnit) . "Service";
        }
        if($safeCheck && (!class_exists($class) || !method_exists($class, $page->getActionFunction()))){
            throw new \Exception('Class/Action not found');
        }
        return $class;
    }

}

?>
