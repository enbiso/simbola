<?php

namespace simbola\core\component\auth\lib;

/**
 * Permision Object deifnitions
 *
 * @author Faraj Farook
 */
class PermObject {

    private $module, $logicalUnit, $object, $type;    

    /**
     * Contructor
     * 
     * @param \simbola\core\component\auth\lib\Page $mobj Module or page object
     * @param string $logicalUnit Logical unit name
     * @param string $object Object name
     * @param ap\AuthType $type
     */
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
    
    /**
     * Gets the access item string
     * 
     * @return string
     */
    public function getAccessItem() {                
        $prepender = false;
        if(sstring_contains($this->type,".")){
            $types = explode(".", $this->type);
            $this->type = $types[0];
            $prepender = implode(".", array_slice($types, 1));
        }
        $accessItem = "{$this->module}.{$this->type}.{$this->logicalUnit}";        
        if (!is_null($this->object)) {
            $accessItem = "{$accessItem}.{$this->object}";
        }        
        if($prepender){
            $accessItem = "{$accessItem}.{$prepender}";
        }
        return $accessItem;
    }

}

?>
