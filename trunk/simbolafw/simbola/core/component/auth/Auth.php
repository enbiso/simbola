<?php

namespace simbola\core\component\auth;

use simbola\Simbola;

define("AUTH_ITEM_TYPE_ACCESS_OBJECT",  "object");
define("AUTH_ITEM_TYPE_ENDUSER_ROLE",   "enduser");
define("AUTH_ITEM_TYPE_ACCESS_ROLE",    "access");

/**
 * Auth component definitions
 *
 * @author Faraj Farook
 *  
 */
class Auth extends \simbola\core\component\system\lib\Component {
    
    const USERNAME = 'system.username';
    const SESSION_KEY = 'system.session_key';
    
    /**
     * Role based access provider
     * @var lib\ap\RoleBaseAccessProvider 
     */
    private $rbap = null;
    
    /**
     * Setup default component values
     */
    public function setupDefault() {
        parent::setupDefault();
        $this->setParamDefault('BYPASS', false);           
        $this->setParamDefault('SINGLE_USER', false);           
        $this->setParamDefault('DEFAULT_ROLE', 'app_user'); 
        $this->setParamDefault('GUEST_ROLE', 'app_guest'); 
        $this->setParamDefault('GUEST_USERNAME', 'guest'); 
    }
    
    /**
     * Get default user role
     * 
     * @return string
     */
    public function getDefaultRole() {
        return $this->getParam('DEFAULT_ROLE');
    }

    /**
     * Gets user session information
     * 
     * @param string $username Username
     * @param string $sessionKey Session Key
     * @return array
     */
    private function getUserSession($username = null, $sessionKey = null) {
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
    
    /**
     * Check if is logged
     * 
     * @param string $username Username
     * @param string $sessionKey Session Key
     * @return boolean
     */
    public function isLogged($username = null, $sessionKey = null) {   
        $params = $this->getUserSession($username, $sessionKey);
        return $this->getRBAP()->userSessionCheck($params[self::USERNAME], $params[self::SESSION_KEY]);        
    }

    /**
     * User login 
     * 
     * @param string $username Username
     * @param string $password Password
     * @param string $sessionInfo Session Information
     * @return string Session Key
     */
    public function login($username, $password = false, $sessionInfo = '') {
        $sessionKey = $this->getRBAP()->userAuthenticate($username, $password, $sessionInfo, $this->getParam('SINGLE_USER'));        
        if ($sessionKey) {
            $session = Simbola::app()->session;
            $session->set(self::USERNAME, $username);
            $session->set(self::SESSION_KEY, $sessionKey);
            return $sessionKey;
        } else {
            return false;
        }
    }

    /**
     * User logout
     * 
     * @param string $username Username
     * @param string $sessionKey Session Key
     * @return boolean
     */
    public function logout($username = null, $sessionKey = null) {
        //get username and session key associated
        $session = Simbola::app()->session;
        $session->set(self::USERNAME, null);
        $session->set(self::SESSION_KEY, null);
        $sParams = $this->getUserSession($username, $sessionKey);
        return $this->getRBAP()->userSessionRevoke($sParams[self::USERNAME], $sParams[self::SESSION_KEY]);
    }

    /**
     * Get user roles
     * 
     * @param string $username Username
     * @param string $sessionKey Session Key
     * @return array
     */
    public function getRoles($username = null, $sessionKey = null) {
        if ($this->isLogged($username, $sessionKey)) {
            $params = $this->getUserSession($username, $sessionKey);
            $username = $this->getUsername($params[self::USERNAME], $params[self::SESSION_KEY]);
            return $this->getRBAP()->userRoles($username);
        } else {
            return array($this->params['GUEST_ROLE']);
        }
    }

    /**
     * Gets the session key
     * 
     * @param string $username Username
     * @param string $sessionKey Session Key
     * @return string Session Key
     */
    public function getSessionKey($username = null, $sessionKey = null) {
        if (isset($username) && isset($sessionKey)) {
            return $sessionKey;
        } else {
            $session = Simbola::app()->session;
            $skey = $this->isLogged() ? $session->get(self::SESSION_KEY) : '';
            return $skey;
        }
    }

    /**
     * Gets the username
     * 
     * @param string $username Username
     * @param string $sessionKey Session Key
     * @return string Username
     */
    public function getUsername($username = null, $sessionKey = null) {
        if (isset($username) && isset($sessionKey)) {            
            return $username;
        } else {
            $session = Simbola::app()->session;
            $uname = $this->isLogged() ? $session->get(self::USERNAME) : $this->params['GUEST_USERNAME'];
            return $uname;
        }
    }

    /**
     * Gets the user ID
     * 
     * @param string $username Username
     * @param string $sessionKey Session Key
     * @return integer User ID
     */
    public function getId($username = null, $sessionKey = null) {
        $params = $this->getUserSession($username, $sessionKey);
        return $this->getRBAP()->userId($this->getUsername($params[self::USERNAME],$params[self::SESSION_KEY]));
    }
    
    /**
     * Check the permission for the user for the given URL
     * 
     * @param string $urlString URL String
     * @param string $username Username
     * @param string $sessionKey Session Key
     * @return boolean
     */
    public function checkPermissionByUrl($urlString, $username = null, $sessionKey = null) {                    
        $page = new \simbola\core\component\url\lib\Page();
        $page->loadFromUrl($urlString);
        return $this->checkPermissionByPage($page, $username, $sessionKey);
    }
    
    /**
     * Check the permission for the user for the given page object
     * 
     * @param \simbola\core\component\url\lib\Page $page
     * @param string $username Username
     * @param string $sessionKey Session Key
     * @return boolean
     */
    public function checkPermissionByPage($page, $username = null, $sessionKey = null) {
        $permObj = new lib\PermObject($page);        
        return $this->checkPermission($permObj, $username, $sessionKey);
    }
    
    /**
     * Check the permission for the user for the given permission object
     * 
     * @param lib\PermObject $permObj Permision object
     * @param string $username Username
     * @param string $sessionKey Session Key
     * @return boolean
     */
    public function checkPermission($permObj, $username = null, $sessionKey = null) {                            
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
        if (!isset($sessionKey)) {
            $sessionKey = $session->get('session_key');
        }
        //get roles
        $roles = $this->getRoles($username, $sessionKey);
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

    /**
     * Initialize the component
     */
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
     * 
     * @return lib\ap\RoleBaseAccessProvider
     */
    public function getRBAP() {
        return $this->rbap;
    }

}

?>
