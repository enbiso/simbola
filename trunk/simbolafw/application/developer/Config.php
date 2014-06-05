<?php
namespace application\developer;
/**
 * Description of Module Configuration
 *
 * Module 	: developer
 * Created	: 06Jul2013
 * Purpose 	: Development tool
 *
 * Change Logs
 * -----------------------------------------------------------
 * 06Jul2013 guest: Created the module webcode
 * 14Jul2013 faraj: Changed the module name to developer
 *  
 * @author guest
 */
class Config extends \simbola\core\application\AppModuleConfig{
    
    public function __construct() {
        $this->name("developer");        
    }
    
    public function setDefaultOverride() {
        $this->set("LAYOUT", "layout/main"); 
    }
}

?>
