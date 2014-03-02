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
     * Reload the resource cache
     */
    public function reloadCache() {
        lib\ResItem::reloadCache();
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
