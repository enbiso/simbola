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

    protected $moduleName = 'system';
    protected $luName = "auth";

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

    protected function getTableName($name) {
        return \simbola\Simbola::app()->db->getTableName($this->moduleName, $this->luName, $name);
    }

    protected function getViewName($name) {
        return \simbola\Simbola::app()->db->getViewName($this->moduleName, $this->luName, $name);
    }

    protected function getProcedureName($name) {
        return \simbola\Simbola::app()->db->getProcedureName($this->moduleName, $this->luName, $name);
    }

    public function viewExist($view_name) {
        return \simbola\Simbola::app()->db->viewExist($this->moduleName, $this->luName, $view_name);
    }

    public function tableExist($table_name) {
        return \simbola\Simbola::app()->db->tableExist($this->moduleName, $this->luName, $table_name);
    }

    public function schemaExist() {
        return \simbola\Simbola::app()->db->moduleExist($this->moduleName);
    }

    public function createSchema() {
        return \simbola\Simbola::app()->db->moduleCreate($this->moduleName);
    }

    public function init() {
        if (!$this->schemaExist()) {
            $this->createSchema();
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

    public function isNewInstallation() {
        return !$this->tableExist(SELF::TBL_ITEM);            
    }
    
    //import, export
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

    public function export($types = array('access_object', 'access_role', 'enduser_role', 'object_relation')) {
        $data = array();
        foreach ($types as $type) {
            $data[$type] = $this->dbQuery("SELECT * FROM {$this->getViewName($type)}");
        }
        return $data;
    }

    public function itemGet($type) {
        $sql = "SELECT item_id, item_name, item_description FROM {$this->getTableName(SELF::TBL_ITEM)}
                WHERE item_type = {$type}";
        $data = $this->dbQuery($sql);
        return $data;
    }

    public function userId($username) {
        $sql = "SELECT user_id FROM {$this->getTableName(SELF::TBL_USER)} WHERE user_name = '{$username}'";
        $data = $this->dbQuery($sql);
        if (count($data) > 0) {
            return $data[0]['user_id'];
        }
        return false;
    }

    public function userUsername($user_id) {
        $sql = "SELECT user_name FROM {$this->getTableName(SELF::TBL_USER)} WHERE user_id = '{$user_id}'";
        $data = $this->dbQuery($sql);
        if (count($data) > 0) {
            return $data[0]['user_name'];
        }
        return false;
    }

    public function userGet() {
        $sql = "SELECT user_id, user_name, user_active FROM {$this->getTableName(SELF::TBL_USER)}";
        $data = $this->dbQuery($sql);
        return $data;
    }

    public function itemExist($name) {
        $sql = "SELECT COUNT(1) AS row_count FROM {$this->getTableName(SELF::TBL_ITEM)} WHERE item_name = '{$name}'";
        $data = $this->dbQuery($sql);
        return ($data[0]['row_count'] > 0);
    }

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

    public function itemRename($name, $newUsername) {
        if ($this->itemExist($name)) {
            $sql = "UPDATE {$this->getTableName(SELF::TBL_ITEM)} 
                       SET item_name = '{$newUsername}'
                     WHERE item_name = '{$name}'";
            $this->dbExecute($sql);
        }
    }

    public function childAssign($parent, $child) {
        if ((!$this->childExist($parent, $child)) && (!$this->childExistRecurse($child, $parent))) {
            $sql = "INSERT INTO {$this->getTableName(SELF::TBL_CHILD)} (parent_id,child_id)
                        VALUES ((SELECT item_id FROM {$this->getTableName(SELF::TBL_ITEM)} WHERE item_name = '{$parent}'),
                                (SELECT item_id FROM {$this->getTableName(SELF::TBL_ITEM)} WHERE item_name = '{$child}'))";
            $this->dbExecute($sql);
        }
    }

    public function childRevoke($parent, $child) {
        $sql = "DELETE FROM {$this->getTableName(SELF::TBL_CHILD)} 
                WHERE parent_id = (SELECT item_id FROM {$this->getTableName(SELF::TBL_ITEM)} WHERE item_name = '{$parent}')
                  AND child_id  = (SELECT item_id FROM {$this->getTableName(SELF::TBL_ITEM)} WHERE item_name = '{$child}')";
        $this->dbExecute($sql);
    }

    public function childExist($parent, $child) {
        $sql = "SELECT COUNT(1) AS row_count FROM {$this->getTableName(SELF::TBL_CHILD)} 
                WHERE parent_id = (SELECT item_id FROM {$this->getTableName(SELF::TBL_ITEM)} WHERE item_name = '{$parent}')
                  AND child_id  = (SELECT item_id FROM {$this->getTableName(SELF::TBL_ITEM)} WHERE item_name = '{$child}')";
        $data = $this->dbQuery($sql);
        return ($data[0]['row_count'] > 0);
    }

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

    public function userRemove($username) {
        $sql = "DELETE FROM {$this->getTableName(SELF::TBL_USER)} WHERE user_name = '{$username}'";
        $this->dbExecute($sql);
    }

    public function userRename($username, $newUsername) {
        $sql = "UPDATE {$this->getTableName(SELF::TBL_USER)} 
                   SET user_name = '{$newUsername}'
                 WHERE user_name = '{$username}'";
        $this->dbExecute($sql);
    }

    public function userResetPassword($username, $newPassword) {
        $sql = "UPDATE {$this->getTableName(SELF::TBL_USER)} 
                   SET user_password = md5('{$newPassword}')
                 WHERE user_name = '{$username}'";
        $this->dbExecute($sql);
    }

    public function userActivate($username) {
        $sql = "UPDATE {$this->getTableName(SELF::TBL_USER)} 
                   SET user_active = true
                 WHERE user_name = '{$username}'";
        $this->dbExecute($sql);
    }

    public function userDeactivate($username) {
        $sql = "UPDATE {$this->getTableName(SELF::TBL_USER)} 
                   SET user_active = false
                 WHERE user_name = '{$username}'";
        $this->dbExecute($sql);
    }

    public function userAuthenticate($username, $password = false, $session_info = '') {
        $sql = "SELECT COUNT(1) AS row_count FROM {$this->getTableName(SELF::TBL_USER)} 
                WHERE user_name = '{$username}' 
                  AND user_active = true";
        if (is_string($password)) {
            $sql = "{$sql} AND user_password = md5('{$password}')";
        }
        $data = $this->dbQuery($sql);
        if ($data[0]['row_count'] > 0) {
            if ($session_info !== FALSE) {
                //create session
                $session_key = uniqid("simbola.session.", TRUE);
                $sql = "INSERT INTO {$this->getTableName(SELF::TBL_SESSION)} (client_addr, user_id, skey, description) 
                            VALUES (
                                '" . $_SERVER['REMOTE_ADDR'] . "',
                                (SELECT user_id FROM {$this->getTableName(SELF::TBL_USER)} WHERE user_name = '{$username}'),
                                '" . $session_key . "','" . $session_info . "')";
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

    public function userSessionCheck($username, $session_key) {
        $sql = "SELECT COUNT(1) AS row_count  FROM {$this->getTableName(SELF::TBL_SESSION)} 
                    WHERE user_id = (SELECT user_id 
                                     FROM {$this->getTableName(SELF::TBL_USER)} 
                                     WHERE user_name = '{$username}')
                      AND skey = '{$session_key}'";
        $data = $this->dbQuery($sql);
        return ($data[0]['row_count'] > 0);
    }

    public function userAssign($username, $item_name) {
        if (!$this->userAssigned($username, $item_name)) {
            $sql = "INSERT INTO {$this->getTableName(SELF::TBL_ASSIGN)} (user_id,item_id)
                        VALUES (
                            (SELECT user_id FROM {$this->getTableName(SELF::TBL_USER)} WHERE user_name = '{$username}'),
                            (SELECT item_id FROM {$this->getTableName(SELF::TBL_ITEM)} WHERE item_name = '{$item_name}'))";
            $this->dbExecute($sql);
        }
    }

    public function userAssigned($username, $item_name) {
        $sql = "SELECT COUNT(1) AS row_count FROM {$this->getTableName(SELF::TBL_ASSIGN)} 
                WHERE user_id = (SELECT user_id FROM {$this->getTableName(SELF::TBL_USER)} WHERE user_name = '{$username}')
                  AND item_id  = (SELECT item_id FROM {$this->getTableName(SELF::TBL_ITEM)} WHERE item_name = '{$item_name}')";
        $data = $this->dbQuery($sql);
        return ($data[0]['row_count'] > 0);
    }

    public function userRevoke($username, $item_name) {
        $sql = "DELETE FROM {$this->getTableName(SELF::TBL_ASSIGN)}
                    WHERE user_id = (SELECT user_id FROM {$this->getTableName(SELF::TBL_USER)} WHERE user_name = '{$username}')
                      AND item_id = (SELECT item_id FROM {$this->getTableName(SELF::TBL_ITEM)} WHERE item_name = '{$item_name}')";
        $this->dbExecute($sql);
    }

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

    public function userExist($username) {
        $sql = "SELECT COUNT(1) AS row_count FROM {$this->getTableName(SELF::TBL_USER)} WHERE user_name = '{$username}'";
        $data = $this->dbQuery($sql);
        return ($data[0]['row_count'] > 0);
    }

    public function userSessionRevokeById($session_id, $user_id) {
        $sql = "SELECT COUNT(1) AS row_count FROM {$this->getTableName(SELF::TBL_SESSION)} WHERE id = {$session_id} AND user_id = {$user_id}";
        $data = $this->dbQuery($sql);
        if ($data[0]['row_count'] > 0) {
            $sql = "DELETE FROM {$this->getTableName(SELF::TBL_SESSION)} WHERE id = {$session_id} AND user_id = {$user_id}";
            $this->dbExecute($sql);
            return true;
        } else {
            return false;
        }
    }

    public function userSessionRevoke($username, $session_key) {
        $sql = "DELETE FROM {$this->getTableName(SELF::TBL_SESSION)} 
                      WHERE skey = '{$session_key}'
                        AND user_id = (SELECT user_id FROM {$this->getTableName(SELF::TBL_USER)} WHERE user_name = '{$username}')";
        $this->dbExecute($sql);
    }

    public function dbExecute($sql) {
        return \simbola\Simbola::app()->db->execute($sql);
    }

    public function dbQuery($sql) {
        return \simbola\Simbola::app()->db->query($sql);
    }

}

?>
