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
        $skey = \simbola\Simbola::app()->auth->login(
                $this->_req_params("username"),
                $this->_req_params("password"));        
        if($skey){            
            $this->_res('username', $this->_req_params("username"));
            $this->_res('skey', $skey);
        }else{
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
