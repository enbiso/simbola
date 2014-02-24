<?php

namespace simbola\core\component\resource\lib;

/**
 * Description of ResObject
 *
 * @author Faraj
 */
class ResItem {

    public static $TYPE_IMAGE = 'image';
    public static $TYPE_MISC = 'misc';
    public static $TYPE_CSS = 'css';
    public static $TYPE_JS = 'js';
    private $data;

    public function __construct($type = null, $module = null, $name = null) {
        $this->data['module'] = $module;
        $this->data['type'] = $type;
        $this->data['name'] = $name;
    }

    public function __set($name, $value) {
        $this->data[$name] = $value;
    }

    public function __get($name) {
        return $this->data[$name];
    }
    
    function exist() {
        $filePath = self::getSourceBase($this->module) . DIRECTORY_SEPARATOR . str_replace("/", DIRECTORY_SEPARATOR, $this->name);        
        return file_exists($filePath);
    }

    function getUrl($absolute = false) {
        $url = "/resource/{$this->module}/{$this->name}";
        if ($absolute) {
            $url = \simbola\Simbola::app()->url->getBaseUrl() . $url;
        } else {
            $url = "/" . \simbola\Simbola::app()->url->getAppUrlBase() . $url;
        }
        return $url;
    }
    
    function getTag() {
        $path = $this->getUrl(true);
        switch ($this->type) {
            case self::$TYPE_JS:
                return "<script type='text/javascript' src='{$path}'></script>" . PHP_EOL;
                break;
            case self::$TYPE_CSS:
                return "<link rel='stylesheet' type='text/css' href='{$path}'></link>" . PHP_EOL;
                break;
            default:
                return $path;
                break;
        }
    }
    
    //Static functions
    static function getCacheBase($module) {
        return \simbola\Simbola::app()->resource->getResourceBase() . DIRECTORY_SEPARATOR
                . $module;
    }
    
    static function getSourceBase($module) {
        $moduleConfig = \simbola\Simbola::app()->getModuleConfig($module);
        $path = \simbola\Simbola::app()->basepath('app') . DIRECTORY_SEPARATOR
                . \simbola\Simbola::app()->getParam('BASE') . DIRECTORY_SEPARATOR
                . $moduleConfig->name . DIRECTORY_SEPARATOR
                . $moduleConfig->resource;
        return $path;
    }

    
    static function reloadCache() {
        foreach (\simbola\Simbola::app()->getModuleNames() as $moduleName) {
            $source = self::getSourceBase($moduleName);
            $dest = self::getCacheBase($moduleName);
            sfile_recursive_copy($source, $dest);
        }                    
    }

}

?>
