<?php

namespace simbola\core\application\dbobj;

/**
 * Description of Table
 *
 * @author Faraj
 */
abstract class AppDbTable extends AbstractDbObject {

    /**
     * Constructor
     * @param \simbola\core\component\db\driver\AbstractDbDriver $db DB Driver
     */
    public function __construct($db) {
        parent::__construct($db);
        $this->type = "table";
    }

    /**
     * Get table name
     * @return String
     */
    function getTableName() {
        return $this->dbDriver->getTableName($this->module, $this->lu, $this->name);
    }

    /**
     * create table
     */
    function addTable() {
        $this->setContent("CREATE TABLE {$this->getTableName()} ("
                . "_id VARCHAR(64) NOT NULL, "
                . "_version TIMESTAMP NOT NULL, "
                . "_created TIMESTAMP NOT NULL, "
                . "_state VARCHAR(15))");
        $this->execute();
    }

    /**
     * Add columns
     * @param Array $columns Column definition
     */
    function addColumns($columns) {        
        $colStmts = array();
        foreach ($columns as $column) {
             $colStmts[] = "ADD COLUMN {$column}";            
        }           
        $this->setContent("ALTER TABLE {$this->getTableName()} " . implode(",", $colStmts));
        $this->execute();
    }

    /**
     * Remove columns
     * @param Array $columns Column names
     */
    function removeColumns($columns) {        
        $colStmts = array();
        foreach ($columns as $column) {
             $colStmts[] = "DROP COLUMN {$column}";            
        }           
        $this->setContent("ALTER TABLE {$this->getTableName()} " . implode(",", $colStmts));                   
        $this->execute();
    }

    /**
     * Alter columns
     * @param Array $columns Column name => new definition
     */
    function alterColumns($columns) {        
        $colStmts = array();
        foreach ($columns as $column => $newColumnDesc) {
             $colStmts[] = "CHANGE COLUMN {$column} {$newColumnDesc}";            
        }           
        $this->setContent("ALTER TABLE {$this->getTableName()} " . implode(",", $colStmts));
        $this->execute();
    }

    /**
     * Add primary key
     * @param array $columns Keys
     */
    function addPrimaryKey($columns) {
        if (is_array($columns)) {
            $columns = implode(",", $columns);
        }
        $this->setContent("ALTER TABLE {$this->getTableName()} ADD PRIMARY KEY({$columns})");        
        $this->execute();
    }
    
    /**
     * Remove the existing and change the primary key
     * @param array $columns keys
     */
    function changePrimaryKey($columns) {
        if (is_array($columns)) {
            $columns = implode(",", $columns);
        }
        $content[] = "ALTER TABLE {$this->getTableName()} DROP PRIMARY KEY";
        $content[] = "ALTER TABLE {$this->getTableName()} ADD PRIMARY KEY({$columns})";
        $this->setContent($content);
        $this->execute();
    }

    /**
     * Remove the primary key
     */
    function removePrimaryKey() {
        $this->setContent("ALTER TABLE {$this->getTableName()} DROP PRIMARY KEY");        
        $this->execute();
    }

    /**
     * Add foriegn key
     * @param array $fkeys key_name => Key definitions
     */
    function addForeignKeys($fkeys) {        
        foreach ($fkeys as $fkey => $fkeyDesc) {
            if(is_array($fkeyDesc)){
                $tableName = $this->dbDriver->getTableName($fkeyDesc[1],$fkeyDesc[2],$fkeyDesc[3]);
                $fkeyDesc = "({$fkeyDesc[0]}) REFERENCES {$tableName}({$fkeyDesc[4]})";
            }
            $this->setContent("ALTER TABLE {$this->getTableName()} ADD CONSTRAINT {$fkey} FOREIGN KEY {$fkeyDesc}");            
            $this->execute();
        }        
    }

    /**
     * Remove foriegn keys
     * @param array $fkeys Key name array
     */
    function removeForeignKeys($fkeys) {        
        foreach ($fkeys as $fkey) {
            $this->setContent("ALTER TABLE {$this->getTableName()} DROP FOREIGN KEY {$fkey}");            
            $this->execute();
        }        
    }
    
}
