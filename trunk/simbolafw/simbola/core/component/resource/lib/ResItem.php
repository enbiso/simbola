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

    function getSourceBase() {
        $moduleConfig = \simbola\Simbola::app()->getModuleConfig($this->module);
        $path = \simbola\Simbola::app()->basepath('app') . DIRECTORY_SEPARATOR
                . \simbola\Simbola::app()->getParam('BASE') . DIRECTORY_SEPARATOR
                . $moduleConfig->name . DIRECTORY_SEPARATOR
                . $moduleConfig->resource;
        return $path;
    }
    
    function exist() {
        $filePath = $this->getSourceBase().DIRECTORY_SEPARATOR.$this->name;
        return file_exists($filePath);
    }

    function getCacheBase() {
        return \simbola\Simbola::app()->resource->getResourceBase() . DIRECTORY_SEPARATOR
                . $this->module;
    }

    function getUrl($absolute = false) {
        if(\simbola\Simbola::app()->resource->getParam('MODE') == "DEV"){
            $this->initLoad();
        }
        if($absolute){
            return \simbola\Simbola::app()->url->getBaseUrl() . $this->getUrl();
        }else{
            return \simbola\Simbola::app()->url->getAppUrlBase() . "/resource/{$this->module}/{$this->name}";
        }
    }

    function initLoad() {
        $source = $this->getSourceBase();
        $dest = $this->getCacheBase();
        $this->rcopy($source, $dest);
    }

    private function rcopy($source, $dest) {
        if (is_dir($source)) {
            $dirHandle = opendir($source);
            while ($file = readdir($dirHandle)) {
                if ($file != "." && $file != "..") {
                    if (is_dir($source . "/" . $file)) {
                        if(!file_exists($dest . "/" . $file)){
                            mkdir($dest . "/" . $file, 0755, true);
                        }
                        $this->rcopy($source . "/" . $file, $dest . "/" . $file);
                    } else {
                        if(!file_exists($dest . "/" . $file)){
                            if(!file_exists($dest)){
                                mkdir($dest, 0755, true);
                            }
                            copy($source . "/" . $file, $dest . "/" . $file);
                        }
                    }
                }
            }
            closedir($dirHandle);
        } else {
            if(!file_exists($dest)){
                copy($source, $dest);
            }
        }
    }

    function getTag() {
        $path = $this->getUrl(true);
        switch ($this->type) {
            case self::$TYPE_JS:
                return "<script type='text/javascript' src='{$path}'></script>".PHP_EOL;
                break;
            case self::$TYPE_CSS:
                return "<link rel='stylesheet' type='text/css' href='{$path}'></link>".PHP_EOL;
                break;
            default:
                return $path;
                break;
        }
    }

}

?>
