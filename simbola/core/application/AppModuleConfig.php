<?php

namespace simbola\core\application;

/**
 * Description of AppModuleConfig
 *
 * @author Faraj
 */
abstract class AppModuleConfig {

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

    public function name($value) {
        $this->set('NAME', $value);
    }
    
    public function set($name, $value){
        $this->config[$name] = $value;
    }
            
    function getConfig() {
        $this->initialize();
        return $this->config;
    }
    
    function getParam($name) {
        return $this->config[$name];
    }
    
    public function __get($name) {
        return $this->config[strtoupper($name)];
    }

    public function setDefault($default) {
        foreach ($this->config as $key => $value) {
            if(array_key_exists($key, $default)){
                $this->set($key, $default[$key]);
            }
        }
        $this->setDefaultOverride();
    }
    
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
    
    public static function GetPathOfModule($name, $full = true) {
        $app = \simbola\Simbola::app();
        $path = $app->getParam('BASE') . DIRECTORY_SEPARATOR . $name;
        if($full){
            $path = $app->basepath('app') . DIRECTORY_SEPARATOR . $path;
        }
        return $path;
    }
    
    public function getNamespace($nsOf = false) {
        $app = \simbola\Simbola::app();
        $ns = "\\{$app->getParam('BASE')}\\{$this->name}";
        if($nsOf){
            $ns = $ns."\\{$this->$nsOf}";
        }
        return $ns;
    }
    
    public function setDefaultOverride() {}
}

?>
