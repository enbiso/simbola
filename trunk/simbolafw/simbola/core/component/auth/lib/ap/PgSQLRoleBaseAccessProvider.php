<?php

namespace simbola\core\component\auth\lib\ap;

/**
 * PGSQL Role based access provider
 *
 * @author Faraj Farook
 */
class PgSQLRoleBaseAccessProvider extends DBRoleBaseAccessProvider {

    /**
     * Create an auth tem
     * 
     * @param string $name Auth item
     * @param AuthType $type Auth Item type
     * @return boolean
     */
    public function itemCreate($name, $type) {
        if (!$this->itemExist($name)) {
            $sql = "INSERT INTO {$this->getTableName(self::TBL_ITEM)} (item_id,item_name,item_type)
                        VALUES(NEXTVAL('{$this->getTableName(self::TBL_ITEM)}_seq'),'{$name}','{$type}')";
            $this->dbExecute($sql);
            return true;
        }else{
            return false;
        }
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
        $sql = "INSERT INTO {$this->getTableName(self::TBL_USER)} (user_id,user_name,user_password)
                    VALUES(NEXTVAL('{$this->getTableName(self::TBL_USER)}_seq'),'{$username}',md5('{$password}'))";
        $this->dbExecute($sql);
        if ($with_default_role) {
            $default_role = \simbola\Simbola::app()->auth->getDefaultRole();
            if (!$this->itemExist($default_role)) {
                $this->itemCreate($default_role, AUTH_ITEM_TYPE_ENDUSER_ROLE);
            }
            $this->userAssign($username, $default_role);
        }
        return true;
    }

    /**
     * Create view
     * Framework function. Do not use.
     * 
     * @todo Need to implement
     */
    public function createViewObjectRelation() {
        throw new \Exception(__METHOD__ . 'not implemented.');
    }

    /**
     * Create view
     * Framework function. Do not use.
     * 
     * @todo Need to implement
     */
    public function createViewUserRole() {
        throw new \Exception(__METHOD__ . 'not implemented.');
    }

}

?>
