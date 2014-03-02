<?php

namespace simbola\core\application;

/** 
 * The abstract base class that should be used to define the Application Module 
 * configuration 
 *
 * @author Faraj
 * 
 * @property-read string $layout Module layout
 * @property-read string $view View folder of the module
 * @property-read string $name Module name
 * @property-read string $service Service folder of the module
 * @property-read string $library Library folder of the module
 * @property-read string $controller Controller folder of the module
 * @property-read string $term Term folder of the module
 * @property-read string $database Database folder of the module
 * @property-read string $resource Resource folder of the module
 * @property-read string $default_route Default route page path
 */
abstract class AppModuleConfig {

    /**
     * The variable which contains the configuration of the the module
     * 
     * @var array 
     */
    private $config = array(
        'LAYOUT' => 'layout/main',
        'VIEW' => 'view',
        'NAME' => null,
        'MODEL' => 'model',
        'SERVICE' => 'service',
        'LIBRARY' => 'library',
        'CONTROLLER' => 'controller',
        'TERM' => 'terms',
        'DATABASE' => 'database',
        'RESOURCE' => 'resource',
        'DEFAULT_ROUTE' => 'site/index',
    );    

    /**
     * Used to set the module name in the configuration
     * 
     * @param string $value Name of the module
     */
    public function name($value) {
        $this->set('NAME', $value);
    }
    
    /**
     * Used to set the configuration values for the module
     * 
     * @param string $name
     * @param mixed $value
     */
    public function set($name, $value){
        $this->config[$name] = $value;
    }
            
    /**
     * Returns the config array of the module
     * 
     * @return array
     */
    function getConfig() {
        $this->initialize();
        return $this->config;
    }
    
    /**
     * Fetch the module configuration parameter of the name specified
     * 
     * @param string $name Name of the module configuration parameter
     * @return mixed
     */
    function getParam($name) {
        return $this->config[$name];
    }
    
    /**
     * Used by the PHP interpreter to make the module config parameter as
     * a property
     * 
     * @param string $name Name of the module configuration parameter
     * @return type 
     */
    public function __get($name) {
        return $this->config[strtoupper($name)];
    }

    /**
     * Framework function used to initalize the default values specified in the 
     * Application setup parameters under the DEFAULT section
     * DO NOT EXPLICITLY USE THIS FUNCTION
     * 
     * @param array $default
     */
    public final function setDefault($default) {
        foreach ($this->config as $key => $value) {
            if(array_key_exists($key, $default)){
                $this->set($key, $default[$key]);
            }
        }
        $this->setDefaultOverride();
    }
    
    /**
     * Used to fetch the module paths as by the given parameters
     * 
     * @param string $pathOf The path of the module component ie, database, view, etc..
     *                       Default set to FALSE, which returns the module path
     * @param boolean $full TRUE - Full path (default), FALSE - relative path
     * @return string
     */
    public function getPath($pathOf = false, $full = true) {
        $app = \simbola\Simbola::app();
        $path = $app->getParam('BASE') . DIRECTORY_SEPARATOR . $this->name;
        if($pathOf){
            $path = $path . DIRECTORY_SEPARATOR . $this->$pathOf;        
        }
        if($full){
            $path = $app->basepath('app') . DIRECTORY_SEPARATOR . $path;
        }
        return $path;
    }
    
    /**
     * Used to fetch the module path of the module specified
     * 
     * @param string $name Name of the module
     * @param boolean $full TRUE - Full path (default), FALSE - relative path
     * @return string
     */
    public static function GetPathOfModule($name, $full = true) {
        $app = \simbola\Simbola::app();
        $path = $app->getParam('BASE') . DIRECTORY_SEPARATOR . $name;
        if($full){
            $path = $app->basepath('app') . DIRECTORY_SEPARATOR . $path;
        }        
        return $path;
    }
    
    /**
     * Used to fetch the namespace string representation of the module or module
     * component specified
     * 
     * @param string $nsOf Specifies the module component, ie, database, view, etc...
     *                     Default set to false which returns the namespace of the 
     *                     module
     * @return string
     */
    public function getNamespace($nsOf = false) {
        $app = \simbola\Simbola::app();
        $ns = "\\{$app->getParam('BASE')}\\{$this->name}";
        if($nsOf){
            $ns = $ns."\\{$this->$nsOf}";
        }
        return $ns;
    }
    
    /**
     * The function used to override the paramter set to the module from the
     * Application setup configuration param DEFAULT.
     * 
     * Overriding this function, the developer can provide custom configruations
     * for the module
     */
    public function setDefaultOverride() {}
}

?>
