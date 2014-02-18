<?php

namespace simbola\core\component\system\lib;

/**
 * Description of Component
 *
 * @author Faraj
 */
abstract class Component {

    protected $params = array();

    public function setParams($params = array()) {
        foreach ($params as $key => $value) {
            $this->params[$key] = $value;
        }
    }

    public function getParams() {
        return $this->params;
    }

    public function getParam($name) {
        return $this->params[$name];
    }

    public function setParamDefault($name, $default) {
        if (!isset($this->params[$name])) {
            $this->params[$name] = $default;
        }
    }

    public static function GetValue($params, $key, $default) {
        return isset($params[$key]) ? $params[$key] : $default;
    }

    //parameter defaults
    public function setupDefault() {
        
    }

    //called after param is set
    public function setup() {
        
    }

    //called before the application execution
    public function init() {
        
    }

    //called after the execution
    public function destroy() {
        
    }

}

?>