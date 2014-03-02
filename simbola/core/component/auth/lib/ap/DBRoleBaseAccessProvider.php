<?php

namespace simbola\core\component\auth\lib\ap;

/**
 * Description of DBRoleBaseAccessProvider
 *
 * @author Faraj
 */
abstract class DBRoleBaseAccessProvider extends RoleBaseAccessProvider {

    //Table names
    const TBL_ITEM = 'item';
    const TBL_SESSION = 'session';
    const TBL_CHILD = 'child';
    const TBL_ASSIGN = 'assign';
    const TBL_USER = 'user';
    //View names
    const VIW_ROLE = 'role';
    const VIW_ACCESS_OBJECT = 'access_object';
    const VIW_ACCESS_ROLE = 'access_role';
    const VIW_ENDUSER_ROLE = 'enduser_role';
    const VIW_SYSTEM_USER = 'system_user';
    const VIW_USER_ROLE = 'user_role';
    const VIW_OBJECT_RELATION = 'object_relation';

    /**
     *
     * @var type Module name
     */
    protected $moduleName = 'system';
    
    /**
     * Logical Unit name
     */
    protected $luName = "auth";
    
    /**
     * Database driver
     * @var \simbola\core\component\db\driver\AbstractDbDriver
     */
    protected $dbDriver;

    /**
     * Contructor
     */
    public function __construct() {
         $this->dbDriver = \simbola\Simbola::app()->db->getDriver();
    }
    
    /**
     * Switch the authentication item for the given type
     * 
     * @param string $name
     * @param AuthType $type
     */
    public function itemSwitch($name, $type) {
        if ($this->itemExist($name)) {
            $sql = "UPDATE {$this->getTableName(SELF::TBL_ITEM)} 
                       SET item_type = {$type}
                     WHERE item_name = '{$name}'";
            $this->dbExecute($sql);
        }
    }

    //create table abstraction
    abstract function createTblAuthAssign();
    abstract function createTblAuthChild();
    abstract function createTblAuthItem();
    abstract function createTblAuthUser();
    abstract function createTblAuthSession();

    //create view abstraction
    abstract function createViewAccessRole();
    abstract function createViewAccessObject();
    abstract function createViewEnduserRole();
    abstract function createViewRole();
    abstract function createViewSystemUser();
    abstract function createViewUserRole();
    abstract function createViewObjectRelation();

    /**
     * Get table name
     * 
     * @param string $name Table name
     * @return string Fully qualified table name
     */
    protected function getTableName($name) {
        return $this->dbDriver->getTableName($this->moduleName, $this->luName, $name);
    }

    /**
     * Get view name
     * 
     * @param string $name View name
     * @return string Fully qualified view name
     */
    protected function getViewName($name) {
        return $this->dbDriver->getViewName($this->moduleName, $this->luName, $name);
    }

    /**
     * Get function name
     * 
     * @param string $name Function name
     * @return string Fully qualified function name
     */
    protected function getProcedureName($name) {
        return $this->dbDriver->getProcedureName($this->moduleName, $this->luName, $name);
    }

    /**
     * Check view exist
     * 
     * @param string $name View name
     * @return boolean
     */
    public function viewExist($name) {
        return $this->dbDriver->viewExist($this->moduleName, $this->luName, $name);
    }

    /**
     * Check table exist
     * 
     * @param string $name Table name
     * @return boolean
     */
    public function tableExist($name) {
        return $this->dbDriver->tableExist($this->moduleName, $this->luName, $name);
    }

    /**
     * Check module exist
     * 
     * @return boolean
     */
    public function moduleExist() {
        return $this->dbDriver->moduleExist($this->moduleName);
    }

    /**
     * Create the module
     * 
     * @return boolean
     */
    public function moduleCreate() {
        return $this->dbDriver->moduleCreate($this->moduleName);
    }

    /**
     * Initialization
     */
    public function init() {
        if (!$this->moduleExist()) {
            $this->moduleCreate();
        }
        if ($this->isNewInstallation()) {
            $this->createTblAuthItem();
            $this->createTblAuthChild();
            $this->createTblAuthUser();
            $this->createTblAuthAssign();
            $this->createTblAuthSession();
            $this->createViewAccessObject();
            $this->createViewAccessRole();
            $this->createViewEnduserRole();
            $this->createViewRole();
            $this->createViewObjectRelation();
            $this->createViewSystemUser();
            $this->createViewUserRole();            
        }        
    }

    /**
     * Check if new instalation
     * 
     * @return boolean
     */
    public function isNewInstallation() {
        return !$this->tableExist(SELF::TBL_ITEM);            
    }
    
    /**
     * Import RBAP Data to the system
     * 
     * @param array $allData
     * @return boolean
     */
    public function import($allData) {         
        foreach ($allData as $type => $data) {
            switch ($type) {
                case 'access_object':
                    foreach ($data as $datum) {
                        if (!$this->itemExist($datum['object'])) {
                            $this->itemCreate($datum['object'], AuthType::ACCESS_OBJECT);
                        }
                    }
                    break;
                case 'access_role':
                    foreach ($data as $datum) {
                        if (!$this->itemExist($datum['role'])) {
                            $this->itemCreate($datum['role'], AuthType::ACCESS_ROLE);
                        }
                    }
                    break;
                case 'enduser_role':
                    foreach ($data as $datum) {
                        if (!$this->itemExist($datum['role'])) {
                            $this->itemCreate($datum['role'], AuthType::ENDUSER_ROLE);
                        }
                    }
                    break;
                case 'object_relation':
                    foreach ($data as $datum) {
                        if (!$this->childExist($datum['parent'], $datum['child'])) {
                            $this->childAssign($datum['parent'], $datum['child']);
                        }
                    }
                    break;
                case 'system_user':
                    foreach ($data as $datum) {
                        if (!$this->userExist($datum['user'])) {
                            $this->userCreate($datum['user']);
                        }
                        if($datum['active'] == 'active') {
                            $this->userActivate($datum['user']);
                        }  else {
                            $this->userDeactivate($datum['user']);
                        }
                    }
                    break;
                case 'user_role':
                    foreach ($data as $datum) {
                        if (!$this->userAssigned($datum['user'], $datum['role'])) {
                            $this->userAssign($datum['user'], $datum['role']);
                        }
                    }
                    break;
            }
        }
        return true;
    }

    /**
     * Export the simbola RBAP data to an array
     * 
     * @param array $types Types to export 'access_object', 'access_role', 'enduser_role', 'object_relation', 'user_role', 'system_user'
     * @return array
     */
    public function export($types = array('access_object', 'access_role', 'enduser_role', 'object_relation')) {
        $data = array();
        foreach ($types as $type) {
            $data[$type] = $this->dbQuery("SELECT * FROM {$this->getViewName($type)}");
        }
        return $data;
    }

    /**
     * Get all the items of the given type
     * 
     * @param AuthType $type 
     * @return array
     */
    public function itemGet($type) {
        $sql = "SELECT item_id, item_name, item_description FROM {$this->getTableName(SELF::TBL_ITEM)}
                WHERE item_type = {$type}";
        $data = $this->dbQuery($sql);
        return $data;
    }

    /**
     * Get the user id for the given username
     * 
     * @param string $username name
     * @return integer
     */
    public function userId($username) {
        $sql = "SELECT user_id FROM {$this->getTableName(SELF::TBL_USER)} WHERE user_name = '{$username}'";
        $data = $this->dbQuery($sql);
        if (count($data) > 0) {
            return $data[0]['user_id'];
        }
        return false;
    }

    /**
     * Get the username for the given user ID
     * 
     * @param integer $userId User ID
     * @return boolean
     */
    public function userUsername($userId) {
        $sql = "SELECT user_name FROM {$this->getTableName(SELF::TBL_USER)} WHERE user_id = '{$userId}'";
        $data = $this->dbQuery($sql);
        if (count($data) > 0) {
            return $data[0]['user_name'];
        }
        return false;
    }

    /**
     * Get all the users
     * 
     * @return array
     */
    public function userGet() {
        $sql = "SELECT user_id, user_name, user_active FROM {$this->getTableName(SELF::TBL_USER)}";
        $data = $this->dbQuery($sql);
        return $data;
    }

    /**
     * Check if ayth item exist
     * 
     * @param string $name Item name
     * @return boolean
     */
    public function itemExist($name) {
        $sql = "SELECT COUNT(1) AS row_count FROM {$this->getTableName(SELF::TBL_ITEM)} WHERE item_name = '{$name}'";
        $data = $this->dbQuery($sql);
        return ($data[0]['row_count'] > 0);
    }

    /**
     * Create an auth tem
     * 
     * @param string $name Auth item
     * @param AuthType $type Auth Item type
     * @return boolean
     */
    public function itemCreate($name, $type) {
        if (!empty($name) && !$this->itemExist($name)) {
            $sql = "INSERT INTO {$this->getTableName(SELF::TBL_ITEM)} (item_name,item_type)
                        VALUES('{$name}','{$type}')";
            $this->dbExecute($sql);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete an item
     * 
     * @param string $name Item name
     */
    public function itemDelete($name) {
        if ($this->itemExist($name)) {
            $sql = "DELETE FROM {$this->getTableName(SELF::TBL_CHILD)} 
                     WHERE parent_id = (SELECT item_id FROM {$this->getTableName(SELF::TBL_ITEM)} WHERE item_name = '{$name}')
                        OR child_id  = (SELECT item_id FROM {$this->getTableName(SELF::TBL_ITEM)} WHERE item_name = '{$name}')";
            $this->dbExecute($sql);
            $sql = "DELETE FROM {$this->getTableName(SELF::TBL_ASSIGN)} 
                     WHERE item_id = (SELECT item_id FROM {$this->getTableName(SELF::TBL_ITEM)} WHERE item_name = '{$name}')";
            $this->dbExecute($sql);
            $sql = "DELETE FROM {$this->getTableName(SELF::TBL_ITEM)} WHERE item_name = '{$name}'";
            $this->dbExecute($sql);
        }
    }

    /**
     * Rename an item
     * 
     * @param string $name Item name
     * @param string $newUsername Item new name
     */
    public function itemRename($name, $newUsername) {
        if ($this->itemExist($name)) {
            $sql = "UPDATE {$this->getTableName(SELF::TBL_ITEM)} 
                       SET item_name = '{$newUsername}'
                     WHERE item_name = '{$name}'";
            $this->dbExecute($sql);
        }
    }

    /**
     * Assign a child item to the parent item
     * 
     * @param string $parent Parent name
     * @param string $child Child name
     */
    public function childAssign($parent, $child) {
        if ((!$this->childExist($parent, $child)) && (!$this->childExistRecurse($child, $parent))) {
            $sql = "INSERT INTO {$this->getTableName(SELF::TBL_CHILD)} (parent_id,child_id)
                        VALUES ((SELECT item_id FROM {$this->getTableName(SELF::TBL_ITEM)} WHERE item_name = '{$parent}'),
                                (SELECT item_id FROM {$this->getTableName(SELF::TBL_ITEM)} WHERE item_name = '{$child}'))";
            $this->dbExecute($sql);
        }
    }

    /**
     * Revoke a child item from a parent item
     * 
     * @param string $parent Parent name
     * @param string $child Child name
     */
    public function childRevoke($parent, $child) {
        $sql = "DELETE FROM {$this->getTableName(SELF::TBL_CHILD)} 
                WHERE parent_id = (SELECT item_id FROM {$this->getTableName(SELF::TBL_ITEM)} WHERE item_name = '{$parent}')
                  AND child_id  = (SELECT item_id FROM {$this->getTableName(SELF::TBL_ITEM)} WHERE item_name = '{$child}')";
        $this->dbExecute($sql);
    }

    /**
     * Check if a child is an actual child of a parent
     * 
     * @param string $parent Parent name
     * @param string $child Child name
     * @return boolean
     */
    public function childExist($parent, $child) {
        $sql = "SELECT COUNT(1) AS row_count FROM {$this->getTableName(SELF::TBL_CHILD)} 
                WHERE parent_id = (SELECT item_id FROM {$this->getTableName(SELF::TBL_ITEM)} WHERE item_name = '{$parent}')
                  AND child_id  = (SELECT item_id FROM {$this->getTableName(SELF::TBL_ITEM)} WHERE item_name = '{$child}')";
        $data = $this->dbQuery($sql);
        return ($data[0]['row_count'] > 0);
    }

    /**
     * Check if a child is an actual child of a parent recursively
     * 
     * @param string $parent Parent name
     * @param string $child Child name
     * @return boolean
     */
    public function childExistRecurse($parent, $child) {
        $sql = "SELECT count(1) AS row_count FROM {$this->getTableName(SELF::TBL_CHILD)} 
                WHERE parent_id = (SELECT item_id FROM {$this->getTableName(SELF::TBL_ITEM)} WHERE item_name = '{$parent}')
                  AND child_id  = (SELECT item_id FROM {$this->getTableName(SELF::TBL_ITEM)} WHERE item_name = '{$child}')";
        $data = $this->dbQuery($sql);
        if ($data[0]['row_count'] > 0) {
            return true;
        } else {
            $children = $this->children($parent);
            $exist = false;
            foreach ($children as $child_entry) {
                $exist |= $this->childExistRecurse($child_entry['item_name'], $child);
                if ($exist) {
                    return true;
                }
            }
            return false;
        }
    }

    /**
     * Gets the children of a parent item
     * 
     * @param string $parent Parent name
     * @return array
     */
    public function children($parent) {
        $sql = "SELECT ai.item_id AS item_id,
                       ai.item_name AS item_name,
                       ai.item_type AS item_type
                FROM {$this->getTableName(SELF::TBL_CHILD)} ac, {$this->getTableName(SELF::TBL_ITEM)} ai
                WHERE ac.parent_id = (SELECT item_id FROM {$this->getTableName(SELF::TBL_ITEM)} WHERE item_name = '{$parent}')
                  AND ac.child_id = ai.item_id
                  AND ( NOT ai.item_type = " . AuthType::ACCESS_OBJECT . " )";
        $data = $this->dbQuery($sql);
        return $data;
    }

    /**
     * Create a new user
     * 
     * @param string $username Username
     * @param string $password Password if not provided defaults to the username
     * @param boolean $with_default_role Assigned to the default role
     * @return boolean
     */
    public function userCreate($username, $password = null, $with_default_role = false) {
        $password = is_null($password) ? $username : $password;
        $sql = "INSERT INTO {$this->getTableName(SELF::TBL_USER)} (user_name, user_password)
                    VALUES('{$username}',md5('{$password}'))";
        $this->dbExecute($sql);
        if ($with_default_role) {
            $default_role = \simbola\Simbola::app()->auth->getDefaultRole();
            if (!$this->itemExist($default_role)) {
                $this->itemCreate($default_role, AuthType::ENDUSER_ROLE);
            }
            $this->userAssign($username, $default_role);
        }
        return true;
    }

    /**
     * Removes a user
     * 
     * @param string $username Username
     */
    public function userRemove($username) {
        $sql = "DELETE FROM {$this->getTableName(SELF::TBL_USER)} WHERE user_name = '{$username}'";
        $this->dbExecute($sql);
    }

    /**
     * Rename a username
     * 
     * @param string $username Username
     * @param string $newUsername New username
     */
    public function userRename($username, $newUsername) {
        $sql = "UPDATE {$this->getTableName(SELF::TBL_USER)} 
                   SET user_name = '{$newUsername}'
                 WHERE user_name = '{$username}'";
        $this->dbExecute($sql);
    }

    /**
     * Change the user password
     * 
     * @param string $username Usename
     * @param string $newPassword New password
     */
    public function userResetPassword($username, $newPassword) {
        $sql = "UPDATE {$this->getTableName(SELF::TBL_USER)} 
                   SET user_password = md5('{$newPassword}')
                 WHERE user_name = '{$username}'";
        $this->dbExecute($sql);
    }

    /**
     * Activate the user
     * 
     * @param string $username Username
     */
    public function userActivate($username) {
        $sql = "UPDATE {$this->getTableName(SELF::TBL_USER)} 
                   SET user_active = true
                 WHERE user_name = '{$username}'";
        $this->dbExecute($sql);
    }

    /**
     * Deactivate the user
     * 
     * @param string $username Username
     */
    public function userDeactivate($username) {
        $sql = "UPDATE {$this->getTableName(SELF::TBL_USER)} 
                   SET user_active = false
                 WHERE user_name = '{$username}'";
        $this->dbExecute($sql);
    }

    /**
     * Authenticates the user
     * 
     * @param string $username Username
     * @param string $password Password
     * @param string $sessionInfo Session information
     * @return boolean
     */
    public function userAuthenticate($username, $password = false, $sessionInfo = '') {
        $sql = "SELECT COUNT(1) AS row_count FROM {$this->getTableName(SELF::TBL_USER)} 
                WHERE user_name = '{$username}' 
                  AND user_active = true";
        if (is_string($password)) {
            $sql = "{$sql} AND user_password = md5('{$password}')";
        }
        $data = $this->dbQuery($sql);
        if ($data[0]['row_count'] > 0) {
            if ($sessionInfo !== FALSE) {
                //create session
                $session_key = uniqid("simbola.session.", TRUE);
                $sql = "INSERT INTO {$this->getTableName(SELF::TBL_SESSION)} (client_addr, user_id, skey, description) 
                            VALUES (
                                '" . $_SERVER['REMOTE_ADDR'] . "',
                                (SELECT user_id FROM {$this->getTableName(SELF::TBL_USER)} WHERE user_name = '{$username}'),
                                '" . $session_key . "','" . $sessionInfo . "')";
                $this->dbExecute($sql);
                return $session_key;
            } else {
                //do not create session if description is set to bool FALSE                
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * Return the user session key for the iser
     * 
     * @param string $username Username
     * @return string Session key
     */
    public function userSession($username) {
        $sql = "SELECT skey FROM {$this->getTableName(SELF::TBL_SESSION)} 
                    WHERE user_id = (SELECT user_id 
                                     FROM {$this->getTableName(SELF::TBL_USER)} 
                                     WHERE user_name = '{$username}')";
        $data = $this->dbQuery($sql);
        if (count($data) > 0) {
            return $data[0]['skey'];
        } else {
            return false;
        }
    }

    /**
     * User session verification
     * 
     * @param string $username Username
     * @param string $sessionKey Session key
     * @return boolean
     */
    public function userSessionCheck($username, $sessionKey) {
        $sql = "SELECT COUNT(1) AS row_count  FROM {$this->getTableName(SELF::TBL_SESSION)} 
                    WHERE user_id = (SELECT user_id 
                                     FROM {$this->getTableName(SELF::TBL_USER)} 
                                     WHERE user_name = '{$username}')
                      AND skey = '{$sessionKey}'";
        $data = $this->dbQuery($sql);
        return ($data[0]['row_count'] > 0);
    }

    /**
     * Assign user to an item (Role)
     * 
     * @param type $username Username
     * @param type $role Role item name
     */
    public function userAssign($username, $role) {
        if (!$this->userAssigned($username, $role)) {
            $sql = "INSERT INTO {$this->getTableName(SELF::TBL_ASSIGN)} (user_id,item_id)
                        VALUES (
                            (SELECT user_id FROM {$this->getTableName(SELF::TBL_USER)} WHERE user_name = '{$username}'),
                            (SELECT item_id FROM {$this->getTableName(SELF::TBL_ITEM)} WHERE item_name = '{$role}'))";
            $this->dbExecute($sql);
        }
    }

    /**
     * Check user assigned to an item (Role)
     * 
     * @param string $username Username
     * @param string $role Role item name
     * @return boolean
     */
    public function userAssigned($username, $role) {
        $sql = "SELECT COUNT(1) AS row_count FROM {$this->getTableName(SELF::TBL_ASSIGN)} 
                WHERE user_id = (SELECT user_id FROM {$this->getTableName(SELF::TBL_USER)} WHERE user_name = '{$username}')
                  AND item_id  = (SELECT item_id FROM {$this->getTableName(SELF::TBL_ITEM)} WHERE item_name = '{$role}')";
        $data = $this->dbQuery($sql);
        return ($data[0]['row_count'] > 0);
    }

    /**
     * Remove user from a role
     * 
     * @param string $username Username
     * @param string $role Role item name
     */
    public function userRevoke($username, $role) {
        $sql = "DELETE FROM {$this->getTableName(SELF::TBL_ASSIGN)}
                    WHERE user_id = (SELECT user_id FROM {$this->getTableName(SELF::TBL_USER)} WHERE user_name = '{$username}')
                      AND item_id = (SELECT item_id FROM {$this->getTableName(SELF::TBL_ITEM)} WHERE item_name = '{$role}')";
        $this->dbExecute($sql);
    }

    /**
     * Fetch the roles assigned to the user
     * 
     * @param string $username Username
     * @return array
     */
    public function userRoles($username) {
        $sql = "SELECT ai.item_name AS item_name
                FROM {$this->getTableName(SELF::TBL_ASSIGN)} aa, {$this->getTableName(SELF::TBL_ITEM)} ai
                WHERE aa.user_id = (SELECT user_id FROM {$this->getTableName(SELF::TBL_USER)} WHERE user_name = '{$username}')
                  AND aa.item_id = ai.item_id";
        $data = $this->dbQuery($sql);
        $roles = array();
        foreach ($data as $role_entry) {
            $roles[] = $role_entry['item_name'];
        }
        return $roles;
    }

    /**
     * Check user exist
     * 
     * @param string $username Username 
     * @return boolean
     */
    public function userExist($username) {
        $sql = "SELECT COUNT(1) AS row_count FROM {$this->getTableName(SELF::TBL_USER)} WHERE user_name = '{$username}'";
        $data = $this->dbQuery($sql);
        return ($data[0]['row_count'] > 0);
    }

    /**
     * Revoke user session by IDs
     * 
     * @param integer $sessionId Session ID
     * @param integer $userId User ID
     * @return boolean
     */
    public function userSessionRevokeById($sessionId, $userId) {
        $sql = "SELECT COUNT(1) AS row_count FROM {$this->getTableName(SELF::TBL_SESSION)} WHERE id = {$sessionId} AND user_id = {$userId}";
        $data = $this->dbQuery($sql);
        if ($data[0]['row_count'] > 0) {
            $sql = "DELETE FROM {$this->getTableName(SELF::TBL_SESSION)} WHERE id = {$sessionId} AND user_id = {$userId}";
            $this->dbExecute($sql);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Revoke user session
     * 
     * @param string $username Username
     * @param string $sessionKey Session key
     */
    public function userSessionRevoke($username, $sessionKey) {
        $sql = "DELETE FROM {$this->getTableName(SELF::TBL_SESSION)} 
                      WHERE skey = '{$sessionKey}'
                        AND user_id = (SELECT user_id FROM {$this->getTableName(SELF::TBL_USER)} WHERE user_name = '{$username}')";
        $this->dbExecute($sql);
    }

    /**
     * Execute database query
     * 
     * @param string $sql SQL Query
     * @return array Array of results
     */
    public function dbExecute($sql) {
        return $this->dbDriver->execute($sql);
    }

    /**
     * Query database
     * 
     * @param string $sql SQL Query
     * @return array Array of results
     */
    public function dbQuery($sql) {
        return $this->dbDriver->query($sql);
    }

}

?>
