<?php
namespace \application\system\database\auth\view;

/**
 * Description of Role
 *
 * @author FARFLK
 */
class Role extends \application\system\library\dbsetup\View{

    public function init() {
        $this->setModule('system');
        $this->setLu("logger");
        $this->setName("system_user");
    }
    
    public function setup() { 
        //view created by framework execution. dummy view definition for the security
    }
}
