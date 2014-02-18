<?php
namespace \application\system\database\auth\view;

/**
 * Description of SystemUser
 *
 * @author FARFLK
 */
class SystemUser extends \application\system\library\dbsetup\View{

    public function init() {
        $this->setLu("logger");
        $this->setName("log");
    }
    
    public function setup() { 
        //view created by framework execution. dummy view definition for the security
    }
}
