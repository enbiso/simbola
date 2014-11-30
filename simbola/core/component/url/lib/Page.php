<?php

namespace simbola\core\component\url\lib;

/**
 * Page
 *
 * @author Faraj Farook
 * 
 * @property string $type Page type - CONTROLLER / SERVICE
 * @property string $module Page module name
 * @property string $logicalUnit Page logical unit name
 * @property string $action Page action name
 * @property array $params Page arguments
 */
class Page {
    
    const TYPE_CONTROLLER = "CONTROLLER";
    const TYPE_SERVICE = "SERVICE";    
    
    /**
     * Contains the page data
     *  
     * @var array
     */
    private $data = array(
        'type' => null,
        'module' => null,
        'logicalUnit' => null,
        'action' => null,
        'params' => array(),
    );
    
    /**
     * Alias value if exist
     * @var string Alias
     */
    private $alias = false;
    
    /**
     * Consturct Page object
     * @param type $value Array or String
     */
    public function __construct($value = NULL) {
        if(is_array($value)){
            $this->loadFromArray($value);
        }elseif(is_string($value)){
            $this->loadFromUrl($value);
        }
    }
    
    /**
     * Returns the function name of the action
     * 
     * @return string
     */
    public function getActionFunction() {
        return "action".ucfirst($this->action);
    }

    /**
     * PHP interpreter funtion to represent the page data as properties
     * 
     * @param string $name Property name
     * @return mixed
     */
    public function __get($name) {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        } else {
            return null;
        }
    }
    
    /**
     * PHP interpreter funtion to represent the page data as properties
     * 
     * @param string $name Property name
     * @param mixed $value Property value     
     */
    public function __set($name, $value) {
        $this->data[$name] = $value;
    }

    /**
     * Used to set the default page parameters
     * 
     * @param string $name
     * @param mixed $value
     */
    public function setDefault($name, $value) {
        if (empty($this->data[$name])) {
            $this->data[$name] = $value;
        }
    }

    /**
     * Load page data from the array representation of the URL
     * array([PATH],[KEY_1] => [VALUE_1], [KEY_2] => [VALUE_2],... )
     * 
     * @param array $url
     * @param bool $append Append Paramsters default false
     */
    public function loadFromArray($url, $append = false) {
        if (count($url) > 0) {            
            $this->loadFromUrl($url[0]);
        }
        if (count($url) > 1) {
            if($append) {
                $this->params = array_slice($url, 1);
            } else {
                $this->params = array_merge($this->params, array_slice($url, 1));
            }
        }        
    }

    /**
     * Load the page data from the string representation of the URL
     * 
     * http://www.example.com/app/index.php/www/site/index[KEY:VALUE]...
     * @param string $urlString String representation of the URL
     * @param boolean $append Append Parameters defalt false
     * @throws \Exception URL String is empty
     */
    public function loadFromUrl($urlString, $append = false) {
        $urlString = urldecode($urlString);                
        if(strlen($urlString)==0){
            throw new \Exception("URL String empty");
        }
        $urlString = ($urlString[0] == '/') ? substr($urlString, 1) : $urlString;
        //remove index.php if exist        
        $urlString = str_replace(array("index.php/","index.php"), "", $urlString);        
        //remove url_base if exist
        $url_base = \simbola\Simbola::app()->url->getParam('URL_BASE');
        if($url_base){
            $urlString = str_replace($url_base."/", "", $urlString);
        }        
        //Alias - Start
        $urlString = $this->resolveAlias($urlString);        
        //Alias - End 
        
        if (($pos = strpos($urlString, "[")) > 0) {
            $temp_url = substr($urlString, 0, $pos);
            $paramString = str_replace($temp_url, "", $urlString);
            $urlString = $temp_url;
            $rpos = strrpos($paramString, "]");
            $paramString = substr($paramString, 1, $rpos - 1);
            $params = explode("][", $paramString);
            if(!$append){
                $this->params = array();
            }
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
        $_GET = array_merge(isset($_GET)?$_GET:array(), $this->params);
        $_REQUEST = array_merge(isset($_REQUEST)?$_REQUEST:array(), $this->params);

        //remove the params from url
        if (strpos($urlString, '?')) {
            $urlString = substr($urlString, 0, strpos($urlString, '?'));
        }        
                
        if ($urlString == \simbola\Simbola::app()->getParam('SERVICE_API')) {
            //service            
            $this->type = Page::TYPE_SERVICE;
            $this->module = isset($_POST['module'])?$_POST['module']:'';
            $this->logicalUnit = isset($_POST['service'])?$_POST['service']:'';
            $this->action = isset($_POST['action'])?$_POST['action']:'';
            $this->params = isset($_POST['params'])?$_POST['params']:array();            
        } else {
            //controller
            $this->type = Page::TYPE_CONTROLLER;
            $urlData = explode("/", $urlString);
            $this->module = empty($urlData[0]) ? null : $urlData[0];
            $this->logicalUnit = empty($urlData[1]) ? null : $urlData[1];
            $this->action = empty($urlData[2]) ? null : $urlData[2];            
        }       
    }

    /**
     * Resolve the URL with URL String accoding to the ALIAS
     * @param String $urlString URL String
     * @return String
     */
    private function resolveAlias($urlString) {
        $url = \simbola\Simbola::app()->url;
        $alias = $url->getParam("ALIAS");
        $urlKey = $urlString;
        $urlParam = null;
        if (($pos = strpos($urlString, "[")) > 0) {
            $urlKey = substr($urlString, 0, $pos);
            $urlParam  = substr($urlString, $pos);
        }        
        if(!empty($urlKey) && array_key_exists($urlKey, $alias)){
            $this->alias = $this->parseBase() . $urlKey;
            if(is_array($alias[$urlKey])){
                $aliasPage = new Page();
                $aliasPage->loadFromArray($alias[$urlKey]);
                $urlString = $aliasPage->encode(false) . $aliasPage->parseParams() . (is_null($urlParam)? "" : $urlParam);
            }else{
                $urlString = $alias[$urlKey] . (is_null($urlParam) ? "" : $urlParam);
            }
        }
        return $urlString;
    }
    
    /**
     * Check if the parameter is set for the given name
     * 
     * @param string $key Param key name
     * @return boolean
     */
    public function issetParam($key) {
        return array_key_exists($key, $this->params);
    }
    
    /**
     * Get the URL string
     *  (index.php)/www/site/index[KEY:VALUE]     
     * @param boolean $ignoreAlias Ignore Alias DEFAULT false
     * @return string
     */
    public function getUrl($ignoreAlias = false) {
        $path = "";
        if(!$ignoreAlias && $this->alias){
            $path = $this->alias;
        }else{
            $path = $this->encode();
        }
        $path .= $this->parseParams();
        return $path;
    }
    
    /**
     * Get the param String as 
     *  [KEY:VALUE][KEY:VALUE]
     * @return string
     */
    private function parseParams() {
        $path = "";
        foreach ($this->params as $key => $value) {
            $path .= "[$key:$value]";
        }        
        return $path;
    }

    /**
     * Parse the app base with index
     * @return string URL String
     */
    private function parseBase() {
        $path = "/";
        if(\simbola\Simbola::app()->url->getParam('URL_BASE')){
            $path .= \simbola\Simbola::app()->url->getParam('URL_BASE');
        }
        if (!\simbola\Simbola::app()->url->getParam('HIDE_INDEX')) {
            $path .= "/index.php/";
        }
        return $path;
    }
    
    /**
     * Implementation function of the URL string generator
     * @param bool $absolute Absolute encoding with app path and index.php DEFAULT TRUE
     * @return string
     */
    private function encode($absolute = true) {
        $path = "";
        if($absolute){
            $path = $this->parseBase();        
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
        if ($this->type == self::TYPE_CONTROLLER) {
            $path .= "$action";
        } else if($this->type == self::TYPE_SERVICE) {
            $path .= \simbola\Simbola::app()->getParam("SERVICE_API");
        }
        return $path;
    }

    /**
     * Returns the URL string representation with the base URL
     *  http://www.example.com/app/index.php/www/site/index[KEY:VALUE]
     * @param boolean $ignoreAlias Ignore Alias DEFAULT false
     * @return string
     */
    public function getAbsoluteUrl($ignoreAlias = false) {
        return \simbola\Simbola::app()->url->getBaseUrl(false) . $this->getUrl($ignoreAlias);
    }

}

?>
