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
        $resBase = $this->getResourceBase();
        if(!file_exists($resBase)){
            mkdir($resBase);
            $this->reloadCache();
        }
    }
    
    /**
     * Get the resource cache base for the module
     * 
     * @param string $module Module name
     * @return string Cache base file path
     */
    function getCacheBase($module) {
        return \simbola\Simbola::app()->resource->getResourceBase() . DIRECTORY_SEPARATOR
                . $module;
    }
    
    /**
     * Get the actual reource path
     * 
     * @param string $module Module name
     * @return string Actual resource path
     */
    function getSourceBase($module) {
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
            $source = $this->getSourceBase($moduleName);
            if(file_exists($source)){
                $dest = $this->getCacheBase($moduleName);
                sfile_recursive_copy($source, $dest);
            }
        }                    
    }
    
    /**
     * Get the resource base file path
     * 
     * @return string
     */
    public function getResourceBase() {
        return \simbola\Simbola::app()->basepath('app').DIRECTORY_SEPARATOR.'resource';
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
}

?>
