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
     * Resource component
     * @var \simbola\core\component\resource\Resource 
     */
    private $resExt;

    /**
     * Contructor
     * 
     * @param string $type Resource type - ResItem::TYPE_*
     * @param string $module Module name
     * @param string $name Resource name
     */
    public function __construct($type = null, $module = null, $name = null, $source = 'local') {
        $this->data['module'] = $module;
        $this->data['type'] = $type;
        $this->data['name'] = $name;
        $this->data['source'] = $source;
        $this->resExt = \simbola\Simbola::app()->resource;
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
        $filePath = $this->resExt->getResourceBase($this->module) 
                . DIRECTORY_SEPARATOR 
                . str_replace("/", DIRECTORY_SEPARATOR, $this->name);        
        return file_exists($filePath);
    }

    /**
     * Get the resource URL
     * 
     * @param boolean $absolute TRUE - absolute URL, FALSE - relative URL (default)
     * @return string
     */
    function getUrl($absolute = false) {
        if($this->source == 'local'){
            $url = "resource/{$this->module}/{$this->name}";
            $appUrlBase = \simbola\Simbola::app()->url->getAppUrlBase();
            if ($absolute) {
                $url = \simbola\Simbola::app()->url->getBaseUrl() . $url;
            } else if(!empty($appUrlBase)) {            
                $url = "/" . $appUrlBase . $url;
            } else {
                $url = "/" . $url;
            }
            return $url;
        } else {
            return $this->name;
        }
    }
    
    /**
     * Returns the HTML Tag of the resource
     * 
     * @return string HTML Tag
     */
    function getTag() {
        $path = $this->getUrl();
        switch ($this->type) {
            case self::TYPE_JS:
                return "<script type='text/javascript' src='{$path}'></script>" . PHP_EOL;                                
            case self::TYPE_CSS:
                return "<link rel='stylesheet' type='text/css' href='{$path}'>" . PHP_EOL;                
            default:
                return $path;                
        }
    }
}

?>
