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
     * Create view
     * Framework function. Do not use.
     */
    public function createViewSystemUser() {
        $sql = "CREATE OR REPLACE VIEW {$this->getViewName(self::VIW_SYSTEM_USER)} AS 
                    SELECT user_name user,
                           IF(user_active, 'active','deactive') AS active
                    FROM {$this->getTableName(self::TBL_USER)}";
        $this->dbExecute($sql);
    }

    /**
     * Create view
     * Framework function. Do not use.
     */
    public function createViewAccessRole() {
        $sql = "CREATE OR REPLACE VIEW {$this->getViewName(self::VIW_ACCESS_ROLE)} AS 
                    SELECT item_name role
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
                    SELECT item_name object
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
                    SELECT item_name role
                    FROM {$this->getTableName(self::TBL_ITEM)}
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
                    FROM {$this->getTableName(self::TBL_ITEM)}
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
}

?>
