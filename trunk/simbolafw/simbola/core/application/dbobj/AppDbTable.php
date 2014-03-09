<?php

namespace simbola\core\application\dbobj;

/**
 * Description of Table
 *
 * @author Faraj
 */
abstract class AppDbTable extends AbstractDbObject {

    public function __construct($db) {
        parent::__construct($db);
        $this->type = "table";
    }

    function getTableName() {
        return $this->dbDriver->getTableName($this->module, $this->lu, $this->name);
    }

    function addTable() {
        $this->setContent("CREATE TABLE {$this->getTableName()} ("
                . "_id VARCHAR(64) NOT NULL, "
                . "_version TIMESTAMP NOT NULL, "
                . "_created TIMESTAMP NOT NULL, "
                . "_state VARCHAR(15))");
        $this->execute(true);
    }

    function addColumns($columns) {
        foreach ($columns as $column) {
            $this->setContent("ALTER TABLE {$this->getTableName()} ADD COLUMN {$column}");
            $this->execute(true);
        }        
    }

    function removeColumns($columns) {        
        foreach ($columns as $column) {
            $this->setContent("ALTER TABLE {$this->getTableName()} DROP COLUMN {$column}");
            $this->execute(true);
        }        
    }

    function alterColumns($columns) {        
        foreach ($columns as $column => $newColumnDesc) {
            $this->setContent("ALTER TABLE {$this->getTableName()} CHANGE COLUMN {$column} {$newColumnDesc}");
            $this->execute(true);
        }        
    }

    function addPrimaryKey($columns) {
        if (is_array($columns)) {
            $columns = implode(",", $columns);
        }
        $this->setContent("ALTER TABLE {$this->getTableName()} ADD PRIMARY KEY({$columns})");
        $this->execute(true);
    }

    function removePrimaryKey() {
        $this->setContent("ALTER TABLE {$this->getTableName()} DROP PRIMARY KEY");
        $this->execute(true);
    }

    function addForeignKeys($fkeys) {        
        foreach ($fkeys as $fkey => $fkeyDesc) {
            if(is_array($fkeyDesc)){
                $tableName = $this->dbDriver->getTableName($fkeyDesc[1],$fkeyDesc[2],$fkeyDesc[3]);
                $fkeyDesc = "({$fkeyDesc[0]}) REFERENCES {$tableName}({$fkeyDesc[4]})";
            }
            $this->setContent("ALTER TABLE {$this->getTableName()} ADD CONSTRAINT {$fkey} FOREIGN KEY {$fkeyDesc}");
            $this->execute(true);
        }        
    }

    function removeForeignKeys($fkeys) {        
        foreach ($fkeys as $fkey) {
            $this->setContent("ALTER TABLE {$this->getTableName()} DROP FOREIGN KEY {$fkey}");
            $this->execute(true);
        }        
    }

}

?>
