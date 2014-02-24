<?php
namespace \application\system\database\auth\view;

/**
 * Description of EnduserRole
 *
 * @author FARFLK
 */
class EnduserRole extends \application\system\library\dbsetup\View{

    public function init() {
        $this->setModule('system');
        $this->setLu("logger");
        $this->setName("enduser_role");
    }
    
    public function setup() { 
        //view created by framework execution. dummy view definition for the security
    }
}
