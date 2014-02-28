<?php

namespace simbola\core\component\auth;

use simbola\Simbola;

/**
 * Description of Auth
 *
 * @author Faraj
 *  
 */
class Auth extends \simbola\core\component\system\lib\Component {
    
    const USERNAME = 'system.username';
    const SESSION_KEY = 'system.session_key';
    
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

    public function getUserSession($username = null, $sessionKey = null) {
        $session = Simbola::app()->session;
        $data = array(self::USERNAME => $username, self::SESSION_KEY => $sessionKey);
        if (!isset($username)) {
            $data[self::USERNAME] = $session->get(self::USERNAME);
        }
        if (!isset($sessionKey)) {
            $data[self::SESSION_KEY] = $session->get(self::SESSION_KEY);
        }
        return $data;
    }
    
    public function isLogged($username = null, $sessionKey = null) {             
        $params = $this->getUserSession($username, $sessionKey);
        return $this->getRBAP()->userSessionCheck($params[self::USERNAME], $params[self::SESSION_KEY]);        
    }

    public function login($username, $password = false, $sessionInfo = '') {
        $sessionKey = $this->getRBAP()->userAuthenticate($username, $password, $sessionInfo);        
        if ($sessionKey) {
            $session = Simbola::app()->session;
            $session->set(self::USERNAME, $username);
            $session->set(self::SESSION_KEY, $sessionKey);
            return $sessionKey;
        } else {
            return false;
        }
    }

    public function updateSession($username, $sessionKey) {
        $session = Simbola::app()->session;
        if ($this->isLogged()) {            
            return array('auth' => array(
                            'username' => $session->get(self::USERNAME), 
                            'skey' => $session->get(self::SESSION_KEY)), 
                         'reload' => false);
        } elseif ($this->getRBAP()->userSessionCheck($username, $sessionKey)) {            
            $session->set(self::USERNAME, $username);
            $session->set(self::SESSION_KEY, $sessionKey);
            return array('auth' => array(
                            'username' => $session->get(self::USERNAME), 
                            'skey' => $session->get(self::SESSION_KEY)), 
                         'reload' => true);
        } else {
            return array('auth' => array(
                            'username' => $this->params['GUEST_USERNAME'], 
                            'skey' => ''), 
                         'reload' => false);
        }
    }

    public function logout($username = null, $sessionKey = null) {
        //get username and session key associated
        $session = Simbola::app()->session;
        $session->set(self::USERNAME, null);
        $session->set(self::SESSION_KEY, null);
        $sParams = $this->getUserSession($username, $sessionKey);
        return $this->getRBAP()->userSessionRevoke($sParams[self::USERNAME], $sParams[self::SESSION_KEY]);
    }

    public function getRoles($username = null, $sessionKey = null) {
        if ($this->isLogged($username, $sessionKey)) {
            $params = $this->getUserSession($username, $sessionKey);
            $username = $this->getUsername($params[self::USERNAME], $params[self::SESSION_KEY]);
            return $this->getRBAP()->userRoles($username);
        } else {
            return array($this->params['GUEST_ROLE']);
        }
    }

    public function getSessionKey($username = null, $sessionKey = null) {
        if (isset($username) && isset($sessionKey)) {
            return $sessionKey;
        } else {
            $session = Simbola::app()->session;
            $skey = $this->isLogged() ? $session->get(self::SESSION_KEY) : '';
            return $skey;
        }
    }

    public function getUsername($username = null, $sessionKey = null) {
        if (isset($username) && isset($sessionKey)) {            
            return $username;
        } else {
            $session = Simbola::app()->session;
            $uname = $this->isLogged() ? $session->get(self::USERNAME) : $this->params['GUEST_USERNAME'];
            return $uname;
        }
    }

    public function getId($username = null, $sessionKey = null) {
        $params = $this->getUserSession($username, $sessionKey);
        $this->getRBAP()->userId($this->getUsername($params[self::USERNAME]));
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

    /**
     * Get attached RBAP
     * @return lib\ap\RoleBaseAccessProvider
     */
    public function getRBAP() {
        return $this->rbap;
    }

}

?>
