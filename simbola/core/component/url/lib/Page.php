<?php

namespace simbola\core\component\url\lib;

/**
 * Description of Page
 *
 * @author farflk
 */
class Page {

    public static $TYPE_CONTROLLER = "CONTROLLER";
    public static $TYPE_SERVICE = "SERVICE";
    
    private $data = array(
        'type' => null,
        'module' => null,
        'logicalUnit' => null,
        'action' => null,
        'params' => array(),
    );
    
    public function getActionFunction() {
        return "action".ucfirst($this->action);
    }

    public function __get($name) {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        } else {
            return null;
        }
    }

    public function __set($name, $value) {
        $this->data[$name] = $value;
    }

    public function setDefault($name, $value) {
        if (empty($this->data[$name])) {
            $this->data[$name] = $value;
        }
    }

    public function loadFromArray($url) {
        if (count($url) > 0) {
            $this->loadFromUrl($url[0]);
        }
        if (count($url) > 1) {
            $this->params = array_slice($url, 1);
        }
    }

    public function loadFromUrl($url_string) {
        $url_string = urldecode($url_string);                
        if(strlen($url_string)==0){
            throw new \Exception("URL String empty");
        }
        $url_string = ($url_string[0] == '/') ? substr($url_string, 1) : $url_string;
        if (($pos = strpos($url_string, "[")) > 0) {
            $temp_url = substr($url_string, 0, $pos);
            $param_string = str_replace($temp_url, "", $url_string);
            $url_string = $temp_url;
            $rpos = strrpos($param_string, "]");
            $param_string = substr($param_string, 1, $rpos - 1);
            $params = explode("][", $param_string);
            foreach ($params as $param) {
                $tempArr = explode(":", $param);
                if (count($tempArr) == 1) {
                    $this->params = array_merge($this->params, array($param => true));
                } else {
                    $this->params = array_merge($this->params, array($tempArr[0] => $tempArr[1]));                    
                }
            }
        }
        //add params to $_GET & $_REQUEST
        $_GET = array_merge($_GET, $this->params);
        $_REQUEST = array_merge($_REQUEST, $this->params);

        //remove the params from url
        if (strpos($url_string, '?')) {
            $url_string = substr($url_string, 0, strpos($url_string, '?'));
        }
        
        //remove index.php if exist
        $url_string = str_replace(array("index.php/","index.php"), "", $url_string);
        
        //remove url_base if exist
        $url_base = \simbola\Simbola::app()->url->getParam('URL_BASE');
        if($url_base){
            $url_string = str_replace($url_base."/", "", $url_string);
        }
                
        if ($url_string == \simbola\Simbola::app()->getParam('SERVICE_API')) {
            //service
            $req = \simbola\Simbola::app()->request;
            $this->type = Page::$TYPE_SERVICE;
            $this->module = $req->post('module');
            $this->logicalUnit = $req->post('service');
            $this->action = $req->post('action');
            $this->params = $req->post('params');            
        } else {
            //controller
            $this->type = Page::$TYPE_CONTROLLER;
            $urlData = explode("/", $url_string);
            $this->module = empty($urlData[0]) ? null : $urlData[0];
            $this->logicalUnit = empty($urlData[1]) ? null : $urlData[1];
            $this->action = empty($urlData[2]) ? null : $urlData[2];
        }
    }

    public function issetParam($key) {
        return array_key_exists($key, $this->params);
    }
    
    public function getUrl() {
        $path = \simbola\Simbola::app()->url->getBaseUrl() . "/" . $this->encode();
        foreach ($this->params as $key => $value) {
            $path .= "[$key:$value]";
        }
        return $path;
    }

    public function encode() {
        $path = "";
        if (!\simbola\Simbola::app()->url->getParam('HIDE_INDEX')) {
            $path = "index.php/";
        }
        $action = "";
        if($this->module != null){
            $action = "{$this->module}";
        }
        if($this->logicalUnit != null){    
            $action .= "/{$this->logicalUnit}";
        }
        if($this->action != null){    
            $action .= "/{$this->action}";
        }
        if ($this->type == self::$TYPE_CONTROLLER) {
            $path .= "$action";
        } else if($this->type == self::$TYPE_SERVICE) {
            $path .= \simbola\Simbola::app()->getParam("SERVICE_API");
        }
        return $path;
    }

    public function getUrlWithBaseUrl() {
        return \simbola\Simbola::app()->url->getBaseUrl() . "/" . $this->getUrl();
    }

}

?>
