<?php
namespace simbola\core\component\auth\lib\ap;
/**
 * Description of MySQLRoleBaseAccessProvider
 *
 * @author Faraj
 */
class MySQLRoleBaseAccessProvider extends DBRoleBaseAccessProvider {

    public function createSchema() {
        return;
    }

    public function createTblAuthChild() {
        $sql = "CREATE TABLE {$this->getTableName($this->tblAuthChild)} (                     
                    parent_id BIGINT REFERENCES {$this->getTableName($this->tblAuthItem)}(item_id),
                    child_id BIGINT REFERENCES {$this->getTableName($this->tblAuthItem)}(item_id),
                    PRIMARY KEY(parent_id, child_id)
                )";
        $this->dbExecute($sql);
    }

    public function createTblAuthAssign() {
        $sql = "CREATE TABLE {$this->getTableName($this->tblAuthAssign)} (                     
                    user_id BIGINT REFERENCES {$this->getTableName($this->tblAuthUser)}(user_id),
                    item_id BIGINT REFERENCES {$this->getTableName($this->tblAuthItem)}(item_id),
                    PRIMARY KEY(user_id, item_id)
                )";
        $this->dbExecute($sql);
    }

    public function createTblAuthItem() {
        $sql = "CREATE TABLE {$this->getTableName($this->tblAuthItem)} (                     
                    item_id BIGINT PRIMARY KEY AUTO_INCREMENT,
                    item_type BIGINT,
                    item_name VARCHAR(500) UNIQUE,
                    item_description TEXT
                )";
        $this->dbExecute($sql);
    }

    public function createTblAuthUser() {
        $sql = "CREATE TABLE {$this->getTableName($this->tblAuthUser)} (                     
                    user_id BIGINT PRIMARY KEY AUTO_INCREMENT,
                    user_active BOOL DEFAULT TRUE,
                    user_name VARCHAR(100) UNIQUE,
                    user_password TEXT
                )";
        $this->dbExecute($sql);
    }

    public function createViewSystemUser() {
        $sql = "CREATE OR REPLACE VIEW {$this->getViewName('system_user')} AS 
                    SELECT user_id,user_name,
                           IF(user_active,'ACTIVE','DEACTIVE') AS user_active
                    FROM {$this->getTableName($this->tblAuthUser)}";
        $this->dbExecute($sql);
    }

        public function createViewAccessRole() {
        $sql = "CREATE OR REPLACE VIEW {$this->getViewName('access_role')} AS 
                    SELECT item_id,item_name 
                    FROM {$this->getTableName($this->tblAuthItem)}
                    WHERE item_type = " . AuthType::ACCESS_ROLE . "";
        $this->dbExecute($sql);
    }

    public function createViewAccessObject() {
        $sql = "CREATE OR REPLACE VIEW {$this->getViewName('access_object')} AS 
                    SELECT item_id,item_name 
                    FROM {$this->getTableName($this->tblAuthItem)}
                    WHERE item_type = " . AuthType::ACCESS_OBJECT . "";
        $this->dbExecute($sql);
    }

    public function createViewEnduserRole() {
        $sql = "CREATE OR REPLACE VIEW {$this->getViewName('enduser_role')} AS 
                    SELECT item_id,item_name 
                    FROM {$this->getTableName($this->tblAuthItem)}
                    WHERE item_type = " . AuthType::ENDUSER_ROLE . "";
        $this->dbExecute($sql);
    }
    
    public function createViewRole() {
        $sql = "CREATE OR REPLACE VIEW {$this->getViewName('role')} AS 
                    SELECT item_id,item_name,
                   (CASE WHEN item_type = " . AuthType::ENDUSER_ROLE . " THEN 'ENDUSER_ROLE' 
                         WHEN item_type = " . AuthType::ACCESS_ROLE . " THEN 'ACCESS_ROLE' 
                    END) AS item_type
                    FROM {$this->getTableName($this->tblAuthItem)}
                    WHERE item_type IN(" . AuthType::ENDUSER_ROLE . "," . AuthType::ACCESS_ROLE . ")";
        $this->dbExecute($sql);
    }

    public function createTblAuthSession() {
        $sql = "CREATE TABLE {$this->getTableName($this->tblAuthSession)} (          
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
