<?php
namespace application\system\service;
/**
 * Description of UserService
 *
 * @author Faraj
 */
class AuthService extends \simbola\core\application\AppService{
     
    public $schema_login = array(
        'req' => array('params' => array('username','password')),
        'res' => array(),
        'err' => array('LOGIN_FAILED'),
    );

    function actionLogin() {        
        if(!\simbola\Simbola::app()->auth->login(
                $this->_req_params("username"),
                $this->_req_params("password"))){            
            $this->_err("LOGIN_FAILED");
        }
    }
    
    public $schema_logout = array(
        'req' => array('params' => array()),
        'res' => array(),
        'err' => array(),
    );

    function actionLogout() {
        \simbola\Simbola::app()->auth->logout();
    }        
}

?>
