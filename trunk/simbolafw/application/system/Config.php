<?php
namespace application\system;
/**
 * Description of Module Configuration
 *
 * Module 	: system
 * Created	: 06JUL2013
 * Purpose 	: System core functionalities
 *
 * Change Logs
 * -----------------------------------------------------------
 * 06JUL2013 Faraj: Created the module system
 *  
 * @author Faraj
 */
class Config extends \simbola\core\application\AppModuleConfig{
    public function __construct() {
        $this->name('system');        
    }
    
    public function setDefaultOverride() {
        $this->set('DEFAULT_ROUTE', 'www/index');
        $this->set("LAYOUT", "layout/main");
    }
}

?>
