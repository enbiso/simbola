<?php

namespace simbola\core\component\auth\lib\ap;

/**
 * Description of MySQLRoleBaseAccessProvider
 *
 * @author Faraj
 */
class MySQLRoleBaseAccessProvider extends DBRoleBaseAccessProvider {

    /**
     * Create module
     * 
     * @return boolean
     */
    public function moduleCreate() {
        return true;
    }

    /**
     * Create table
     * Framework function. Do not use.
     */
    public function createTblAuthChild() {
        $sql = "CREATE TABLE {$this->getTableName(SELF::TBL_CHILD)} (                     
                    parent_id BIGINT REFERENCES {$this->getTableName(SELF::TBL_ITEM)}(item_id),
                    child_id BIGINT REFERENCES {$this->getTableName(SELF::TBL_ITEM)}(item_id),
                    PRIMARY KEY(parent_id, child_id)
                )";
        $this->dbExecute($sql);
    }

    /**
     * Create table
     * Framework function. Do not use.
     */
    public function createTblAuthAssign() {
        $sql = "CREATE TABLE {$this->getTableName(SELF::TBL_ASSIGN)} (                     
                    user_id BIGINT REFERENCES {$this->getTableName(SELF::TBL_USER)}(user_id),
                    item_id BIGINT REFERENCES {$this->getTableName(SELF::TBL_ITEM)}(item_id),
                    PRIMARY KEY(user_id, item_id)
                )";
        $this->dbExecute($sql);
    }

    /**
     * Create table
     * Framework function. Do not use.
     */
    public function createTblAuthItem() {
        $sql = "CREATE TABLE {$this->getTableName(SELF::TBL_ITEM)} (                     
                    item_id BIGINT PRIMARY KEY AUTO_INCREMENT,
                    item_type BIGINT,
                    item_name VARCHAR(500) UNIQUE,
                    item_description TEXT
                )";
        $this->dbExecute($sql);
    }

    /**
     * Create table
     * Framework function. Do not use.
     */
    public function createTblAuthUser() {
        $sql = "CREATE TABLE {$this->getTableName(SELF::TBL_USER)} (                     
                    user_id BIGINT PRIMARY KEY AUTO_INCREMENT,
                    user_active BOOL DEFAULT TRUE,
                    user_name VARCHAR(100) UNIQUE,
                    user_password TEXT
                )";
        $this->dbExecute($sql);
    }

    /**
     * Create view
     * Framework function. Do not use.
     */
    public function createViewSystemUser() {
        $sql = "CREATE OR REPLACE VIEW {$this->getViewName(self::VIW_SYSTEM_USER)} AS 
                    SELECT user_name user,
                           IF(user_active, 'active','deactive') AS active
                    FROM {$this->getTableName(SELF::TBL_USER)}";
        $this->dbExecute($sql);
    }

    /**
     * Create view
     * Framework function. Do not use.
     */
    public function createViewAccessRole() {
        $sql = "CREATE OR REPLACE VIEW {$this->getViewName(self::VIW_ACCESS_ROLE)} AS 
                    SELECT item_name role
                    FROM {$this->getTableName(SELF::TBL_ITEM)}
                    WHERE item_type = " . AuthType::ACCESS_ROLE . "";
        $this->dbExecute($sql);
    }

    /**
     * Create view
     * Framework function. Do not use.
     */
    public function createViewAccessObject() {
        $sql = "CREATE OR REPLACE VIEW {$this->getViewName(self::VIW_ACCESS_OBJECT)} AS 
                    SELECT item_name object
                    FROM {$this->getTableName(SELF::TBL_ITEM)}
                    WHERE item_type = " . AuthType::ACCESS_OBJECT . "";
        $this->dbExecute($sql);
    }

    /**
     * Create view
     * Framework function. Do not use.
     */
    public function createViewEnduserRole() {
        $sql = "CREATE OR REPLACE VIEW {$this->getViewName(self::VIW_ENDUSER_ROLE)} AS 
                    SELECT item_name role
                    FROM {$this->getTableName(SELF::TBL_ITEM)}
                    WHERE item_type = " . AuthType::ENDUSER_ROLE . "";
        $this->dbExecute($sql);
    }

    /**
     * Create view
     * Framework function. Do not use.
     */
    public function createViewRole() {
        $sql = "CREATE OR REPLACE VIEW {$this->getViewName(self::VIW_ROLE)} AS 
                    SELECT item_name role,
                   (CASE WHEN item_type = " . AuthType::ENDUSER_ROLE . " THEN 'enduser' 
                         WHEN item_type = " . AuthType::ACCESS_ROLE . " THEN 'access' 
                    END) AS type
                    FROM {$this->getTableName(SELF::TBL_ITEM)}
                    WHERE item_type IN(" . AuthType::ENDUSER_ROLE . "," . AuthType::ACCESS_ROLE . ")";
        $this->dbExecute($sql);
    }
    
    /**
     * Create view
     * Framework function. Do not use.
     */
    public function createViewObjectRelation() {
        $sql = "CREATE OR REPLACE VIEW {$this->getViewName(self::VIW_OBJECT_RELATION)} AS 
                    SELECT (SELECT item_name FROM {$this->getTableName(self::TBL_ITEM)} WHERE item_id = parent_id) parent,
                           (SELECT item_name FROM {$this->getTableName(self::TBL_ITEM)} WHERE item_id = child_id) child
                    FROM {$this->getTableName(self::TBL_CHILD)} ";
        $this->dbExecute($sql);
    }
    
    /**
     * Create view
     * Framework function. Do not use.
     */
    public function createViewUserRole() {
        $sql = "CREATE OR REPLACE VIEW {$this->getViewName(self::VIW_USER_ROLE)} AS 
                    SELECT (SELECT user_name FROM {$this->getTableName(self::TBL_USER)} WHERE user_id = tbl_asgn.user_id) user,
                           (SELECT item_name FROM {$this->getTableName(self::TBL_ITEM)} WHERE item_id = tbl_asgn.item_id) role
                    FROM {$this->getTableName(self::TBL_ASSIGN)} tbl_asgn";
        $this->dbExecute($sql);
    }

    /**
     * Create table
     * Framework function. Do not use.
     */
    public function createTblAuthSession() {
        $sql = "CREATE TABLE {$this->getTableName(SELF::TBL_SESSION)} (          
                    id BIGINT PRIMARY KEY AUTO_INCREMENT,
                    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    client_addr VARCHAR(50),
                    user_id BIGINT,
                    skey TEXT,
                    description TEXT)";
        $this->dbExecute($sql);
    }

}

?>
