<?php

namespace simbola\core\component\auth\lib;

/**
 * Description of PermObject
 *
 * @author farflk
 */
class PermObject {

    private $module, $logicalUnit, $object, $type;    

    public function __construct($mobj, $logicalUnit = null, $object = null, $type = null) {
        if($mobj instanceof \simbola\core\component\url\lib\Page){
            $this->logicalUnit = $mobj->logicalUnit;
            $this->module = $mobj->module;
            $this->object = $mobj->action;
            $this->type = strtolower($mobj->type);
        } else {
            $this->logicalUnit = $logicalUnit;
            $this->module = $mobj;
            $this->object = $object;
            $this->type = $type;            
        }
    }
    
    public function getAccessItem() {                
        $accessItem = "{$this->module}.{$this->type}.{$this->logicalUnit}";
        if (!is_null($this->object)) {
            $accessItem = "{$accessItem}.{$this->object}";
        }        
        return $accessItem;
    }

}

?>
