<?php
namespace simbola\core\component\resource;
/**
 * Resource component definitions
 *
 * @author Faraj Farook
 */
class Resource extends \simbola\core\component\system\lib\Component{
    
    /**
     * Initialization of the component
     */
    public function init() {
        parent::init();
        $resBase = $this->getCacheBase();
        if(!file_exists($resBase)){
            mkdir($resBase);
            $this->reloadCache();
        }
    }
    
    /**
     * Setup default values
     */
    public function setupDefault() {
        $this->setParamDefault('CDN', true);
    }
    
    /**
     * Get the resource cache base for the module
     * 
     * @param string $module Module name nullable
     * @return string Cache base file path
     */
    function getCacheBase($module = false) {
        $cacheBase = \simbola\Simbola::app()->basepath('app').DIRECTORY_SEPARATOR.'resource';
        if($module) {
            $cacheBase .= DIRECTORY_SEPARATOR . $module;
        }
        return $cacheBase;        
    }
    
    /**
     * Get the actual reource path
     * 
     * @param string $module Module name
     * @return string Actual resource path
     */
    function getResourceBase($module) {
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
    function reloadCache() {
        foreach (\simbola\Simbola::app()->getModuleNames() as $moduleName) {            
            $source = $this->getResourceBase($moduleName);
            if(file_exists($source)){
                $dest = $this->getCacheBase($moduleName);
                sfile_recursive_copy($source, $dest);
            }
        }                    
    }
    
    /**
     * Gets the resource tag representation
     * 
     * @param string $type Resource type ResItem::TYPE_*
     * @param string $module Module name
     * @param string $name resource name
     * @return string HTML Tag
     */
    public function getResourceTag($type, $module, $name) {
        $res = $this->getResource($type, $module, $name);
        return $res->getTag();
    }
    
    /**
     * Create a ResItem object
     * 
     * @param string $type Resource type ResItem::TYPE_*
     * @param string $module Module name
     * @param string $name resource name
     * @return \simbola\core\component\resource\lib\ResItem
     */
    public function getResource($type, $module, $name) {
        $res = new lib\ResItem($type, $module, $name);
        return $res;
    }
    
    /**
     * Loads the resource items by name using the config.json
     * 
     * @param type $module Module Name
     * @param type $name Resource Set Name
     * @return array ResItem
     */
    public function loadResItems($module, $name) {
        $configFile = $this->getResourceBase($module)
                . DIRECTORY_SEPARATOR . $name
                . DIRECTORY_SEPARATOR . $name . ".json";
        $resItems = [];
        if(file_exists($configFile)){
            $config = (array)json_decode(file_get_contents($configFile));
            if($this->getParam("CDN") && key_exists("cdn", $config)){
                foreach ($config["cdn"] as $type => $paths) {
                    foreach ($paths as $path) {
                        $resItems[] = new lib\ResItem($type, $module, $path, 'cdn');   
                    }
                }
            }elseif(key_exists("local", $config)){
                foreach ($config["local"] as $type => $paths) {
                    foreach ($paths as $path) {
                        $resItems[] = new lib\ResItem($type, $module, $path, 'local');   
                    }
                }
            }
        }
        return $resItems;
    }
}

?>
