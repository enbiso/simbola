<?php

namespace simbola\core\component\auth;

use simbola\Simbola;

/**
 * Description of Auth
 *
 * @author Faraj
 */
class Auth extends \simbola\core\component\system\lib\Component {

    private $rbap = null;
    
    public function setupDefault() {
        parent::setupDefault();
        $this->setParamDefault('BYPASS', false);           
        $this->setParamDefault('DEFAULT_ROLE', 'app_user'); 
        $this->setParamDefault('GUEST_ROLE', 'app_guest'); 
        $this->setParamDefault('GUEST_USERNAME', 'guest'); 
    }
    
    public function getDefaultRole() {
        return $this->getParam('DEFAULT_ROLE');
    }

    public function isLogged($username = null, $session_key = null) {
        $session = Simbola::app()->session;
        if (!isset($username)) {
            $username = $session->get('username');
        }
        if (!isset($session_key)) {
            $session_key = $session->get('session_key');
        }
        //PT001 - Performance tunning - Start
        $userSessionCheck = $session->get('system.auth.user_session_check');
        if(is_null($userSessionCheck) || !$userSessionCheck){
            $userSessionCheck = $this->getRBAP()->userSessionCheck($username, $session_key);
            $session->set('system.auth.user_session_check', $userSessionCheck);
        }
        return $userSessionCheck;
        //PT001 - Performance tunning - End
    }

    public function login($username, $password = false, $session_info = '') {
        $session_key = $this->getRBAP()->userAuthenticate($username, $password, $session_info);
        slog_debug($session_key);
        if ($session_key) {
            $session = Simbola::app()->session;
            $session->set('username', $username);
            $session->set('session_key', $session_key);
            return $session_key;
        } else {
            return false;
        }
    }

    public function updateSession($username, $session_key) {
        $session = Simbola::app()->session;
        if ($this->isLogged()) {
            return array('auth' => array('username' => $session->get('username'), 'skey' => $session->get('session_key')), 'reload' => false);
        } elseif ($this->getRBAP()->userSessionCheck($username, $session_key)) {
            $session->set('username', $username);
            $session->set('session_key', $session_key);
            return array('auth' => array('username' => $session->get('username'), 'skey' => $session->get('session_key')), 'reload' => true);
        } else {
            return array('auth' => array('username' => $this->params['GUEST_USERNAME'], 'skey' => ''), 'reload' => false);
        }
    }

    public function logout($username = null, $session_key = null) {
        //get username and session key associated
        $session = Simbola::app()->session;
        if (!isset($username)) {
            $username = $session->get('username');
        }
        if (!isset($session_key)) {
            $session_key = $session->get('session_key');
        }
        $session->set('username', null);
        $session->set('session_key', null);
        //PT001 - Performance tunning - Start
        $session->set('system.auth.user_session_check',null);
        //PT001 - Performance tunning - End
        //PT004 - Performance tunning - Start
        $session->set('system.auth.user_roles', null);
        //PT004 - Performance tunning - End
        return $this->getRBAP()->userSessionRevoke($username, $session_key);
    }

    public function getRoles($username = null, $session_key = null) {
        if ($this->isLogged($username, $session_key)) {
            //PT004 - Performance tunning - Start
            $session = Simbola::app()->session;
            $userRoles = $session->get('system.auth.user_roles');
            if(is_null($userRoles)){
                $username = $this->getUsername($username, $session_key);
                $userRoles = $this->getRBAP()->userRoles($username);
                $session->set('system.auth.user_roles', $userRoles);
            }
            return $userRoles;
            //PT004 - Performance tunning - Start
        } else {
            return array($this->params['GUEST_ROLE']);
        }
    }

    public function getSessionKey($username = null, $session_key = null) {
        if (isset($username) && isset($session_key)) {
            return $session_key;
        } else {
            $session = Simbola::app()->session;
            $skey = $this->isLogged() ? $session->get('session_key') : '';
            return $skey;
        }
    }

    public function getUsername($username = null, $session_key = null) {
        if (isset($username) && isset($session_key)) {            
            return $username;
        } else {
            $session = Simbola::app()->session;
            $uname = $this->isLogged() ? $session->get('username') : $this->params['GUEST_USERNAME'];
            return $uname;
        }
    }

    public function getId($username = null, $session_key = null) {
        return $this->getRBAP()->userId($this->getUsername($username, $session_key));
    }
    
    public function checkPermissionByUrl($url_string, $username = null, $session_key = null) {                    
        $page = new \simbola\core\component\url\lib\Page();
        $page->loadFromUrl($url_string);
        return $this->checkPermissionByPage($page, $username, $session_key);
    }
    
    public function checkPermissionByPage($page, $username = null, $session_key = null) {
        $permObj = new lib\PermObject($page);        
        return $this->checkPermission($permObj, $username, $session_key);
    }
    
    public function checkPermission($permObj, $username = null, $session_key = null) {                            
        if(!$permObj instanceof lib\PermObject){
            throw new \Exception("Perm Object Invalid");
        }
        
        if (isset($this->params['BYPASS']) && $this->params['BYPASS'] == true) {
            return true;
        }
        //get username and session key associated
        $session = Simbola::app()->session;
        if (!isset($username)) {
            $username = $session->get('username');
        }
        if (!isset($session_key)) {
            $session_key = $session->get('session_key');
        }
        //get roles
        $roles = $this->getRoles($username, $session_key);
        $rbap = $this->getRBAP();
        //check if permitted        
        $permited = false;
        $accessItem = $permObj->getAccessItem();
        foreach ($roles as $role) {
            $permited |= $rbap->childExistRecurse($role, $accessItem);
            if ($permited) {
                return $permited;
            }
        }
        return $permited;
    }

    public function init() {
        parent::init();
        switch (Simbola::app()->db->getVendor()) {
            case 'MYSQL':
                $this->rbap = new lib\ap\MySQLRoleBaseAccessProvider();
                break;
            case 'PGSQL':
                $this->rbap = new lib\ap\PgSQLRoleBaseAccessProvider();
                break;
        }
        if (!isset($this->params['DB'])) {
            $this->params['DB'] = array();
        }
        if($this->rbap->isNewInstallation()){
            $this->rbap->init($this->params['DB']);
            $basicSecPath = \simbola\Simbola::app()->basepath('fw') 
                    . DIRECTORY_SEPARATOR . 'core'
                    . DIRECTORY_SEPARATOR . 'component'
                    . DIRECTORY_SEPARATOR . 'auth'
                    . DIRECTORY_SEPARATOR . 'data'
                    . DIRECTORY_SEPARATOR . 'basic_security.json';
            $this->rbap->import(json_decode(file_get_contents($basicSecPath),true));
        }
    }

    public function getRBAP() {
        return $this->rbap;
    }

}

?>
