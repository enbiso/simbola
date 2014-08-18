<?php

namespace simbola\core\component\resource\lib;

/**
 * ResObject definitions
 *
 * @author Faraj Farook
 * 
 * @property string $module Module name
 * @property string $type Resource type - ResItem::TYPE_*
 * @property string $name Resource name
 */
class ResItem {

    const TYPE_IMAGE = 'image';
    const TYPE_MISC = 'misc';
    const TYPE_CSS = 'css';
    const TYPE_JS = 'js';
    
    /**
     * Resource data
     * @var array
     */
    private $data;

    /**
     * Contructor
     * 
     * @param string $type Resource type - ResItem::TYPE_*
     * @param string $module Module name
     * @param string $name Resource name
     */
    public function __construct($type = null, $module = null, $name = null) {
        $this->data['module'] = $module;
        $this->data['type'] = $type;
        $this->data['name'] = $name;
    }

    /**
     * PHP interpreter function
     * 
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value) {
        $this->data[$name] = $value;
    }

    /**
     * PHP interpreter function
     * 
     * @param string $name
     * @return mixed
     */
    public function __get($name) {
        return $this->data[$name];
    }
    
    /**
     * Check if the given reource is available
     * 
     * @return boolean
     */
    function exist() {
        $filePath = self::getSourceBase($this->module) . DIRECTORY_SEPARATOR . str_replace("/", DIRECTORY_SEPARATOR, $this->name);        
        return file_exists($filePath);
    }

    /**
     * Get the resource URL
     * 
     * @param boolean $absolute TRUE - absolute URL, FALSE - relative URL (default)
     * @return string
     */
    function getUrl($absolute = false) {
        $url = "/resource/{$this->module}/{$this->name}";
        $appUrlBase = \simbola\Simbola::app()->url->getAppUrlBase();
        if ($absolute) {
            $url = \simbola\Simbola::app()->url->getBaseUrl() . $url;
        } else if(!empty($appUrlBase)) {            
            $url = "/" . $appUrlBase . $url;
        }
        return $url;
    }
    
    /**
     * Returns the HTML Tag of the resource
     * 
     * @return string HTML Tag
     */
    function getTag() {
        $path = $this->getUrl(true);
        switch ($this->type) {
            case self::TYPE_JS:
                return "<script type='text/javascript' src='{$path}'></script>" . PHP_EOL;                                
            case self::TYPE_CSS:
                return "<link rel='stylesheet' type='text/css' href='{$path}'>" . PHP_EOL;                
            default:
                return $path;                
        }
    }
    
    /**
     * Get the resource cache base for the module
     * 
     * @param string $module Module name
     * @return string Cache base file path
     */
    static function getCacheBase($module) {
        return \simbola\Simbola::app()->resource->getResourceBase() . DIRECTORY_SEPARATOR
                . $module;
    }
    
    /**
     * Get the actual reource path
     * 
     * @param string $module Module name
     * @return string Actual resource path
     */
    static function getSourceBase($module) {
        $moduleConfig = \simbola\Simbola::app()->getModuleConfig($module);
        $path = \simbola\Simbola::app()->basepath('app') . DIRECTORY_SEPARATOR
                . \simbola\Simbola::app()->getParam('BASE') . DIRECTORY_SEPARATOR
                . $moduleConfig->name . DIRECTORY_SEPARATOR
                . $moduleConfig->resource;
        return $path;
    }

    
    /**
     * Reload the resource cache
     */
    static function reloadCache() {
        foreach (\simbola\Simbola::app()->getModuleNames() as $moduleName) {
            $source = self::getSourceBase($moduleName);
            $dest = self::getCacheBase($moduleName);
            sfile_recursive_copy($source, $dest);
        }                    
    }

}

?>
