<?php

namespace simbola\core;

/**
 * Application Framework
 *
 * @author Faraj Farook
 * 
 * @property component\session\Session $session Session component
 * @property component\auth\Auth $auth Auth component
 * @property component\db\Db $db DB Component
 * @property component\email\Email $email Email component
 * @property component\log\Log $log Log component
 * @property component\request\Request $request Request component
 * @property component\router\Router $router Router component 
 * @property component\social\Social $cosial Social component
 * @property component\system\System $system System component
 * @property component\term\Term $term Term component
 * @property component\url\Url $url URL component
 */
class Application {

    /**
     * List of components can be accessed Simbola::app()->[component_name]
     * @var array
     */
    public $components = array();
    
    /**
     * The array of system configuration parameters
     * @var array() 
     */
    public $sysParams;

    /**
     * Do not call this. Contructor is called as a singleton in the Simbola class
     */
    public function __construct() {        
        $this->import('core/initlib/include');        
    }
    
    /**
     * Setup the simbola application with basic inputs as associate array as below
     * 
     * 'BASEPATH'    => dirname(__FILE__) !IMPORTANT  
     * 'APPNAME'     => 'Simbola Application'
     * 'ERROR_LEVEL' => E_PROD, E_ALL, E_*
     * 'DEFAULT'     => array('LAYOUT' => '/system/layout/main')
     *
     * @param array $params
     * @access public
     */
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
    
    /**
     * Configure the components in one whole array. Usefulll when you store the 
     * config as a PHP array ina seperate file
     * 
     * @param array $config Contains the configuration params of the components in one array
     * @access public
     */
    public function config($config) {
        foreach ($config as $name => $params) {
            $this->component($name, $params);
        }
    }

    /**
     * Used to set the default params to the system 
     * 
     * @access private
     * @param string $name name of the param
     * @param Mixed $default default alue of the param
     */
    private function initParam($name, $default) {
        if (!isset($this->sysParams[$name])) {
            $this->sysParams[$name] = $default;
        }
    }

    /**
     * Includes the application and framwork paths to the comon includer
     * 
     * @access private
     */
    private function includePathSetup() {
        set_include_path(get_include_path() . PATH_SEPARATOR . $this->basepath('fw'));
        set_include_path(get_include_path() . PATH_SEPARATOR . $this->basepath('app'));
    }

    /**
     * Setup the error handling according to the ERROR_LEVEL system config
     * 
     * @access private
     */
    private function errorSetup() {
        if ($this->isProd()) {
            error_reporting(false);
        } elseif ($this->isDev()) {
            error_reporting($this->sysParams['ERROR_LEVEL']);
        }
    }

    /**
     * Check if the system is running in DEVELOPMENT mode
     * Change it using the ERROR_LEVEL system config
     * 
     * @access public
     * @return boolean
     */
    public function isDev() {
        return $this->sysParams['ERROR_LEVEL'] != E_PROD;
    }

    /**
     * Check if the system is running in PRODUCTION mode
     * Change it using the ERROR_LEVEL system config
     * 
     * @access public
     * @return boolean
     */
    public function isProd() {
        return strtoupper($this->sysParams['ERROR_LEVEL']) == E_PROD;
    }

    /**
     * Fetch the system params used to configure the system
     * 
     * @param array() $keys the names of the system config keys
     * @return array(key => value)
     */
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

    /**
     * Module names in the application
     * 
     * @var array(string) module names 
     * @access private
     */
    private $moduleNames = array();

    /**
     * Used by the framework to fetch the module names
     * 
     * @access private
     */
    private function fetchModuleNames() {
        $path = $this->basepath("app") . DIRECTORY_SEPARATOR
                . $this->getParam('BASE') . DIRECTORY_SEPARATOR . "*";
        foreach (glob($path, GLOB_ONLYDIR) as $modulePath) {
            $this->moduleNames[] = basename($modulePath);
        }
    }

    /**
     * Returns the module names fetched
     * 
     * @access public
     * @return array(string) module names
     */
    public function getModuleNames() {
        return $this->moduleNames;
    }

    /**
     * fetch the system config param by name
     * 
     * @access public
     * @param string $name
     * @return Mixed
     * @throws \Exception Invalid parameter
     */
    public function getParam($name) {
        if (!array_key_exists($name, $this->sysParams)) {
            throw new \Exception("Invalid System Parameter '{$name}' Requested");
        }
        return $this->sysParams[$name];
    }

    /**
     * Returns the PHP namespace base for the application
     * 
     * @access public
     * @return string
     */
    public function getAppNameSpace() {
        return $this->getParam('BASE');
    }
    
    /**
     * Get the Module namespace or the namespace for the specific module component,
     * given the module component name in the second parameter
     * 
     * @access public
     * @param string $moduleName Name of the module
     * @param string $moduleComponent This can be any component in the application module 'controller','database' etc..
     * @return string
     */
    public function getModuleNameSpace($moduleName, $moduleComponent = null) {
        $mconf = $this->getModuleConfig($moduleName);
        $ns = $this->getAppNameSpace()."\\".$moduleName;
        if($moduleComponent != null){
            $ns .= "\\" . $mconf->$moduleComponent;
        }
        return $ns;
    }
    
    /**
     * Get the Module file base or the file base for the specific module component,
     * given the module component name in the second parameter
     * 
     * @access public
     * @param string $moduleName Name of the module
     * @param string $moduleComponent This can be any component in the application module 'controller','database' etc..
     * @return string
     */
    public function getModuleBase($moduleName, $moduleComponent = null) {
        $mconf = $this->getModuleConfig($moduleName);
        $path = $this->getAppBase() . DIRECTORY_SEPARATOR . $mconf->name;
        if($moduleComponent != null){
            $path .= DIRECTORY_SEPARATOR . $mconf->$moduleComponent;
        }
        return $path;
    }
    
    /**
     * Used to get the application file base of the running application
     * 
     * @access public
     * @return string
     */
    public function getAppBase() {
        return $this->basepath('app') . DIRECTORY_SEPARATOR . $this->getParam('BASE');
    }

    /**
     * Used to fetch the base file path of the framework(fw) or application(app)
     * according to the parameter passed
     * 
     * @access public
     * @param string $type Can be 'fw' or 'app'
     * @return type
     */
    public function basepath($type) {
        switch (strtolower($type)) {
            case 'fw':
                return dirname(dirname(__FILE__));
            case 'app':
                return $this->sysParams['BASEPATH'];
        }
    }

    /**
     * Import the php files in the application or from the framework.
     * 
     * @access public
     * @param string $source The source file path relative for the base path of application or framework
     * @param boolean $fromFramework Specify to look in the framework (by default) or in the application (if FALSE)
     */
    public function import($source, $fromFramework = true) {
        $source = $this->basepath($fromFramework ? 'fw' : 'app') . DIRECTORY_SEPARATOR . $source;
        if (is_dir($source)) {
            foreach (glob($source . "/*.php") as $filename) {
                include_once $filename;
            }
        } else {
            include_once $source . ".php";
        }
    }

    /**
     * Implementation function used to import the components
     * 
     * @access private
     */
    private function importComponents() {
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

    /**
     * Initialize component with custom params
     * 
     * @param string $name component name
     * @param array $params component init params
     */
    public function component($name, $params = array()) {
        $this->components[$name]->setParams($params);
        $this->components[$name]->setup();        
    }
    
    /**
     * Used to fetch the components in the property style Used by the PHP interpreter
     * 
     * @access public
     * @param string $name component name
     * @return component\system\lib\Component
     */
    public function __get($name) {
        return $this->components[$name];
    }

    /**     
     * Execute application. Should be called at the index.php after the configurations
     * 
     * @access public
     */
    public function execute() {
        // initialize components
        foreach ($this->components as $component) {
            $component->init();
        }
        // run the dispatcher
        $this->router->dispatch();
        // cleanup components
        foreach ($this->components as $module) {
            $module->destroy();
        }
    }

    /**
     * Get Framework Base path
     * @return string
     */
    public function fwBasePath() {
        return dirname(__FILE__);
    }

    /**
     * The module configuration object cache
     * 
     * @access private
     * @var array(application\AppModuleConfig)
     */
    private $moduleConfigCache = array();

    /**
     * Get Module Configuration instance
     * 
     * @access public
     * @param string $moduleName Module name
     * @return application\AppModuleConfig
     * @throws \Exception
     */
    public function getModuleConfig($moduleName) {
        if (empty($moduleName)) {            
            throw new \Exception("Module cannot be empty", 202);
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
                throw new \Exception("Module {$moduleName} not found", 202);
            }
        }
        return $config;
    }    
    
    /**     
     * Get database script base
     * 
     * @access public
     * @param string $moduleName Module name
     * @return string
     */
    public function getDBScriptBase($moduleName) {        
        $moduleConfig = $this->getModuleConfig($moduleName);
        return $moduleConfig->getPath('database', false);
    }

    /**
     * Get Page Class name
     * 
     * @access public
     * @param component\url\lib\Page $page
     * @param boolean $safeCheck Exception throws on TRUE
     * @return string
     * @throws \Exception
     */
    public function getPageClass($page, $safeCheck = false) {
        $app = \simbola\Simbola::app();
        $moduleConfig = $this->getModuleConfig($page->module);
        if ($page->type == component\url\lib\Page::TYPE_CONTROLLER) {
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
