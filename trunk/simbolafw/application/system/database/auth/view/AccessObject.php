<?php
namespace \application\system\database\auth\view;

/**
 * Description of AccessObject
 *
 * @author FARFLK
 */
class AccessObject extends \application\system\library\dbsetup\View{

    public function init() {
        $this->setLu("logger");
        $this->setName("log");
    }
    
    public function setup() { 
        //view created by framework execution. dummy view definition for the security
    }
}
