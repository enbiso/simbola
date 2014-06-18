<?php

namespace application\system\service;

/**
 * Description of UserService
 *
 * @author FARFLK
 */
class UserService extends \simbola\core\application\AppService {

    public $schema_register = array(
        'req' => array('params' => array('username')),
        'res' => array('user'),
        'err' => array('USER_EXIST'),
    );
    
    function actionRegister() {
        $rbap = \simbola\Simbola::app()->auth->getRBAP();    
        $username = $this->_req_params('username');
        if($rbap->userExist($username)){
            $this->_err("USER_EXIST");            
        }else{
            $rbap->userCreate($username, 
                    $this->_req_params('password'),
                    $this->_req_params('with_default_role'));//create with defaut role            
            $user = \application\system\model\auth\User::find_by_username($username);
            $this->_res('user', $user);
        }
    }

    public $schema_changePassword = array(
        'req' => array('params' => array('username', 'password', 'password_repeat')),
        'res' => array(),
        'err' => array('PASSWORD_MISMATCH'),
    );
    
    function actionChangePassword() {
        $username = $this->_req_params('username');
        $password = $this->_req_params('password');
        $password_repeat = $this->_req_params('password_repeat');
        if ($password == $password_repeat) {
            $rbap = \simbola\Simbola::app()->auth->getRBAP();
            $rbap->userResetPassword($username, $password);            
        } else {
            $this->_err('PASSWORD_MISMATCH');
        }
    }
    
    public $schema_changePasswordSafe = array(
        'req' => array('params' => array('username', 'password', 'new_password', 'password_repeat')),
        'res' => array(),
        'err' => array('PASSWORD_MISMATCH','PASSWORD_INVALID','USER_NOT_EXIST'),
    );
    
    function actionChangePasswordSafe() {
        $rbap = \simbola\Simbola::app()->auth->getRBAP();
        $username = $this->_req_params('username');
        $password = $this->_req_params('password');
        $new_password = $this->_req_params('password');
        $password_repeat = $this->_req_params('password_repeat');
        if ($new_password == $password_repeat) {
            if(!$rbap->userExist($username)){
                $this->_err("USER_NOT_EXIST");            
            }else{
                if($rbap->userAuthenticate($username, $password, false)){
                    $rbap->userResetPassword($username, $new_password);    
                }else{
                    $this->_err("PASSWORD_INVALID");            
                }
            }
        } else {
            $this->_err('PASSWORD_MISMATCH');
        }
    }

    public $schema_deactivate = array(
        'req' => array('params' => array('username')),
        'res' => array(),
        'err' => array(),
    );
    
    function actionDeactivate() {
        $username = $this->_req_params('username');
        $rbap = \simbola\Simbola::app()->auth->getRBAP();
        $rbap->userDeactivate($username);
    }

    public $schema_activate = array(
        'req' => array('params' => array('username')),
        'res' => array(),
        'err' => array(),
    );
    
    function actionActivate() {
        $username = $this->_req_params('username');
        $rbap = \simbola\Simbola::app()->auth->getRBAP();
        $rbap->userActivate($username);        
    }

    public $schema_unregister = array(
        'req' => array('params' => array('username')),
        'res' => array(),
        'err' => array(),
    );
    
    function actionUnregister() {
        $username = $this->_req_params('username');
        $rbap = \simbola\Simbola::app()->auth->getRBAP();
        $rbap->userRemove($username);
    }

    public $schema_resetPassword = array(
        'req' => array('params' => array('username','password')),
        'res' => array(),
        'err' => array('USER_NOT_EXIST'),
    );

    function actionResetPassword() {
        $rbap = \simbola\Simbola::app()->auth->getRBAP();
        if(!$rbap->userExist($this->_req_params('username'))){
            $this->_err("USER_NOT_EXIST");            
        }else{            
            $rbap->userResetPassword($this->_req_params('username'),$this->_req_params('password'));    
        }
    }
    
    public $schema_list = array(
        'req' => array('params' => array()),
        'res' => array('data'),
        'err' => array('LIST_ERROR')
    );

    function actionList() {
        try {
            $search = $this->_req_params('search');
            if(empty($search)){
               $data = \application\system\model\auth\User::find('all');   
            }else{
               $data = \application\system\model\auth\User::find('all', $search);  
            }            
            for ($index = 0; $index < count($data); $index++) {
                $data[$index]->user_password = null;
            }
            $this->_res('data', $data);
        } catch(Exception $ex) {
            $this->_err('LIST_ERROR');
        }
    }
}
