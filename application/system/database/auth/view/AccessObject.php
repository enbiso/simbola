<?php
namespace \application\system\database\auth\view;

/**
 * Description of AccessObject
 *
 * @author FARFLK
 */
class AccessObject extends \application\system\library\dbsetup\View{

    public function init() {
        $this->setModule('system');
        $this->setLu("logger");
        $this->setName("access_object");
    }
    
    public function setup() { 
        //view created by framework execution. dummy view definition for the security
    }
}
