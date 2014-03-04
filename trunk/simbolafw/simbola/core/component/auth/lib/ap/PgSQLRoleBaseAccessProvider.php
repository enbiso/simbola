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
                $this->itemCreate($default_role, AuthType::ENDUSER_ROLE);
            }
            $this->userAssign($username, $default_role);
        }
        return true;
    }

    /**
     * Create table
     * Framework function. Do not use.
     */
    public function createTblAuthUser() {
        $sql = "CREATE TABLE {$this->getTableName(self::TBL_USER)} (                     
                    user_id INTEGER PRIMARY KEY,
                    user_active BOOLEAN DEFAULT TRUE,
                    user_name TEXT UNIQUE,
                    user_password TEXT
                );
                CREATE SEQUENCE {$this->getTableName(self::TBL_USER)}_seq;
                ALTER TABLE {$this->getTableName(self::TBL_USER)} 
                    ALTER COLUMN user_id 
                    SET DEFAULT NEXTVAL('{$this->getTableName(self::TBL_USER)}_seq')";
        $this->dbExecute($sql);
    }

    /**
     * Create table
     * Framework function. Do not use.
     */
    public function createTblAuthSession() {
        $sql = "CREATE TABLE {$this->getTableName(self::TBL_SESSION)} (  
                    id INTEGER PRIMARY KEY,
                    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    client_addr VARCHAR(50),
                    user_id INTEGER,
                    skey TEXT,
                    description TEXT
                );
                CREATE SEQUENCE {$this->getTableName(self::TBL_SESSION)}_seq;
                ALTER TABLE {$this->getTableName(self::TBL_SESSION)} 
                    ALTER COLUMN id 
                    SET DEFAULT NEXTVAL('{$this->getTableName(self::TBL_SESSION)}_seq')";
        $this->dbExecute($sql);
    }

    /**
     * Create view
     * Framework function. Do not use.
     */
    public function createViewSystemUser() {
        $sql = "CREATE OR REPLACE VIEW {$this->getViewName('system_user')} AS 
                    SELECT user_id,user_name,
                           (CASE WHEN user_active THEN 'ACTIVE' ELSE 'DEACTIVE' END) AS user_active
                    FROM {$this->getTableName(self::TBL_USER)}";
        $this->dbExecute($sql);
    }

    /**
     * Create table
     * Framework function. Do not use.
     */
    public function createTblAuthItem() {
        $sql = "CREATE TABLE {$this->getTableName(self::TBL_ITEM)} (                     
                    item_id INTEGER PRIMARY KEY,
                    item_type INTEGER,
                    item_name TEXT UNIQUE,
                    item_description TEXT
                );
                CREATE SEQUENCE {$this->getTableName(self::TBL_ITEM)}_seq;
                ALTER TABLE {$this->getTableName(self::TBL_ITEM)} 
                    ALTER COLUMN item_id 
                    SET DEFAULT NEXTVAL('{$this->getTableName(self::TBL_ITEM)}_seq')";
        $this->dbExecute($sql);
    }

    /**
     * Create view
     * Framework function. Do not use.
     */
    public function createViewAccessRole() {
        $sql = "CREATE OR REPLACE VIEW {{$this->getViewName(self::VIW_ACCESS_ROLE)} AS 
                    SELECT item_id,item_name 
                    FROM {$this->getTableName(self::TBL_ITEM)}
                    WHERE item_type = " . AuthType::ACCESS_ROLE . "";
        $this->dbExecute($sql);
    }

    /**
     * Create view
     * Framework function. Do not use.
     */
    public function createViewAccessObject() {
        $sql = "CREATE OR REPLACE VIEW {$this->getViewName(self::VIW_ACCESS_OBJECT)} AS 
                    SELECT item_id,item_name 
                    FROM {$this->getTableName(self::TBL_ITEM)}
                    WHERE item_type = " . AuthType::ACCESS_OBJECT . "";
        $this->dbExecute($sql);
    }

    /**
     * Create view
     * Framework function. Do not use.
     */
    public function createViewEnduserRole() {
        $sql = "CREATE OR REPLACE VIEW {$this->getViewName(self::VIW_ENDUSER_ROLE)} AS 
                    SELECT item_id,item_name 
                    FROM {$this->getTableName(self::TBL_ITEM)}
                    WHERE item_type = " . AuthType::ENDUSER_ROLE . "";
        $this->dbExecute($sql);
    }

    /**
     * Create table
     * Framework function. Do not use.
     */
    public function createViewRole() {
        $sql = "CREATE OR REPLACE VIEW {$this->getViewName(self::VIW_ROLE)} AS 
                    SELECT item_id,item_name,
                   (CASE WHEN item_type = " . AuthType::ENDUSER_ROLE . " THEN 'ENDUSER_ROLE' 
                         WHEN item_type = " . AuthType::ACCESS_ROLE . " THEN 'ACCESS_ROLE' 
                    END) AS item_type
                    FROM {$this->getTableName(self::TBL_ITEM)}
                    WHERE item_type IN(" . AuthType::ENDUSER_ROLE . "," . AuthType::ACCESS_ROLE . ")";
        $this->dbExecute($sql);
    }

    /**
     * Create table
     * Framework function. Do not use.
     */
    public function createTblAuthChild() {
        $sql = "CREATE TABLE {$this->getTableName(self::TBL_CHILD)} (                     
                    parent_id INTEGER REFERENCES {$this->getTableName(self::TBL_ITEM)}(item_id),
                    child_id INTEGER REFERENCES {$this->getTableName(self::TBL_ITEM)}(item_id),
                    PRIMARY KEY(parent_id, child_id)
                )";
        $this->dbExecute($sql);
    }

    /**
     * Create table
     * Framework function. Do not use.
     */
    public function createTblAuthAssign() {
        $sql = "CREATE TABLE {$this->getTableName(self::TBL_ASSIGN)} (                     
                    user_id INTEGER REFERENCES {$this->getTableName(self::TBL_USER)}(user_id),
                    item_id INTEGER REFERENCES {$this->getTableName(self::TBL_ITEM)}(item_id),
                    PRIMARY KEY(user_id, item_id)
                )";
        $this->dbExecute($sql);
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
