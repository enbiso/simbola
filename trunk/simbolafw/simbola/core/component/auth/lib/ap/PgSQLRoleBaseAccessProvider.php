<?php
namespace simbola\core\component\auth\lib\ap;
/**
 * Description of MySQLRoleBaseAccessProvider
 *
 * @author Faraj
 */
class PgSQLRoleBaseAccessProvider extends DBRoleBaseAccessProvider {

    public function create($name, $type) {
        if (!$this->authItemExist($name)) {
            $sql = "INSERT INTO {$this->getTableName($this->tblAuthItem)} (item_id,item_name,item_type)
                        VALUES(NEXTVAL('{$this->getTableName($this->tblAuthItem)}_seq'),'{$name}','{$type}')";
            $this->dbExecute($sql);
        }
    }

    public function userCreate($user_name) {
        $sql = "INSERT INTO {$this->getTableName($this->tblAuthUser)} (user_id,user_name,user_password)
                    VALUES(NEXTVAL('{$this->getTableName($this->tblAuthUser)}_seq'),'{$user_name}',md5('{$user_name}'))";
        $this->dbExecute($sql);
    }

    public function createTblAuthUser() {
        $sql = "CREATE TABLE {$this->getTableName($this->tblAuthUser)} (                     
                    user_id INTEGER PRIMARY KEY,
                    user_active BOOLEAN DEFAULT TRUE,
                    user_name TEXT UNIQUE,
                    user_password TEXT
                );
                CREATE SEQUENCE {$this->getTableName($this->tblAuthUser)}_seq;
                ALTER TABLE {$this->getTableName($this->tblAuthUser)} 
                    ALTER COLUMN user_id 
                    SET DEFAULT NEXTVAL('{$this->getTableName($this->tblAuthUser)}_seq')";
        $this->dbExecute($sql);
    }
    
    public function createTblAuthSession() {        
        $sql = "CREATE TABLE {$this->getTableName($this->tblAuthSession)} (  
                    id INTEGER PRIMARY KEY,
                    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    client_addr VARCHAR(50),
                    user_id INTEGER,
                    skey TEXT,
                    description TEXT
                );
                CREATE SEQUENCE {$this->getTableName($this->tblAuthSession)}_seq;
                ALTER TABLE {$this->getTableName($this->tblAuthSession)} 
                    ALTER COLUMN id 
                    SET DEFAULT NEXTVAL('{$this->getTableName($this->tblAuthSession)}_seq')";
        $this->dbExecute($sql);    
    }

    public function createViewSystemUser() {
        $sql = "CREATE OR REPLACE VIEW {$this->getViewName('system_user')} AS 
                    SELECT user_id,user_name,
                           (CASE WHEN user_active THEN 'ACTIVE' ELSE 'DEACTIVE' END) AS user_active
                    FROM {$this->getTableName($this->tblAuthUser)}";
        $this->dbExecute($sql);
    }

    public function createTblAuthItem() {
        $sql = "CREATE TABLE {$this->getTableName($this->tblAuthItem)} (                     
                    item_id INTEGER PRIMARY KEY,
                    item_type INTEGER,
                    item_name TEXT UNIQUE,
                    item_description TEXT
                );
                CREATE SEQUENCE {$this->getTableName($this->tblAuthItem)}_seq;
                ALTER TABLE {$this->getTableName($this->tblAuthItem)} 
                    ALTER COLUMN item_id 
                    SET DEFAULT NEXTVAL('{$this->getTableName($this->tblAuthItem)}_seq')";
        $this->dbExecute($sql);
    }

    public function createViewAccessRole() {
        $sql = "CREATE OR REPLACE VIEW {{$this->getViewName('access_role')} AS 
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

    public function createTblAuthChild() {
        $sql = "CREATE TABLE {$this->getTableName($this->tblAuthChild)} (                     
                    parent_id INTEGER REFERENCES {$this->getTableName($this->tblAuthItem)}(item_id),
                    child_id INTEGER REFERENCES {$this->getTableName($this->tblAuthItem)}(item_id),
                    PRIMARY KEY(parent_id, child_id)
                )";
        $this->dbExecute($sql);
    }

    public function createTblAuthAssign() {
        $sql = "CREATE TABLE {$this->getTableName($this->tblAuthAssign)} (                     
                    user_id INTEGER REFERENCES {$this->getTableName($this->tblAuthUser)}(user_id),
                    item_id INTEGER REFERENCES {$this->getTableName($this->tblAuthItem)}(item_id),
                    PRIMARY KEY(user_id, item_id)
                )";
        $this->dbExecute($sql);
    }

    public function userRemove($user_name) {
        $sql = "DELETE FROM {$this->getTableName($this->tblAuthUser)} WHERE user_name = '{$user_name}'";
        $this->dbExecute($sql);
    }

}

?>
