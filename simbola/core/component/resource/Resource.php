<?php
namespace simbola\core\component\resource;
/**
 * Description of Resource
 *
 * @author Faraj
 */
class Resource extends \simbola\core\component\system\lib\Component{
    
    public function init() {
        parent::init();
        $resBase = $this->getResourceBase();
        if(!file_exists($resBase)){
            mkdir($resBase);
            $this->loadCache();
        }
    }
    
    public function loadCache() {
        lib\ResItem::reloadCache();
    }
    
    public function getResourceBase() {
        return \simbola\Simbola::app()->basepath('app').DIRECTORY_SEPARATOR.'resource';
    }
    
    public function getResourceTag($type, $module, $name) {
        $res = $this->getResource($type, $module, $name);
        return $res->getTag();
    }
    
    public function getResource($type, $module, $name) {
        $res = new lib\ResItem($type, $module, $name);
        return $res;
    }
}

?>
