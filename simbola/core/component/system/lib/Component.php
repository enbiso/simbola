<?php

namespace simbola\core\component\system\lib;

/**
 * Component abtraction
 *
 * @author Faraj
 */
abstract class Component {

    /**
     * Component is initialized
     * 
     * @var boolean 
     */
    protected $isInit = false;
    
    /**
     * Component parameters
     * 
     * @var array 
     */
    protected $params = array();

    /**
     * Set the component parameters
     * 
     * @param array $params
     */
    public function setParams($params = array()) {
        $this->params = array_merge($this->params, $params);
    }

    /**
     * Get the component parameters
     * 
     * @return array
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * Get the component parameter for the given name
     * 
     * @param string $name
     * @return mixed
     */
    public function getParam($name) {
        return $this->params[$name];
    }

    /**
     * Set the default parameter for the given parameter name
     * 
     * @param string $name
     * @param mixed $default
     */
    public function setParamDefault($name, $default) {
        if (!isset($this->params[$name])) {
            $this->params[$name] = $default;
        }
    }

    /**
     * Function used to return the default if the params array doesnt have
     * a value for the specified key. If so return the value in the parameter
     * 
     * @param array $params Parameters
     * @param string $key Key name
     * @param mixed $default Paramter value to return
     * @return mixed return parameter
     */
    public static function GetValue($params, $key, $default) {
        return isset($params[$key]) ? $params[$key] : $default;
    }

    /**
     * Override to setup the defaults
     */
    public function setupDefault() {
        
    }

    /**
     * Called after the parameter is set 
     * Override to extend functionality
     */
    public function setup() {
        
    }

    /**
     * Called before the application execution
     * Override to extend functionality
     */    
    public function init() {
        $this->isInit = true;
    }

    /**
     * Called after the execution
     * Override to extend functionality
     */
    public function destroy() {
        
    }

}

?>