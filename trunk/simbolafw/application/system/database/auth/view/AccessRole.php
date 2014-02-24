<?php
namespace \application\system\database\auth\view;

/**
 * Description of AccessRole
 *
 * @author FARFLK
 */
class AccessRole extends \application\system\library\dbsetup\View{

    public function init() {
        $this->setModule('system');
        $this->setLu("logger");
        $this->setName("access_role");
    }
    
    public function setup() { 
        //view created by framework execution. dummy view definition for the security
    }
}
