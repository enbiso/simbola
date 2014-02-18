<?php
namespace \application\system\database\logger\view;

/**
 * Description of Log
 *
 * @author FARFLK
 */
class Log extends \application\system\library\dbsetup\View{

    public function init() {
        $this->setLu("logger");
        $this->setName("log");
    }
    
    public function setup() { 
        //view created by framework execution. dummy view definition for the security
    }
}
