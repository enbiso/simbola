<?php

namespace simbola\core\application\dbobj;

/**
 * Description of DbObject
 *
 * @author Faraj
 */
abstract class AbstractDbObject {

    /**
     * Db Component instance
     * @var \simbola\core\component\db\driver\AbstractDbDriver 
     */
    protected $dbDriver;
    protected $module;
    protected $lu;
    protected $name;
    protected $content;
    protected $type;
    protected $revCount = 0;

    function __construct($db) {
        $this->dbDriver = $db;
        $this->init();
    }

    function setModule($module) {
        $this->module = $module;
    }

    function setLu($lu) {
        $this->lu = $lu;
    }

    function setName($name) {
        $this->name = $name;
    }

    function getName() {
        return $this->name;
    }

    function setContent($content) {
        if(is_string($content)){
            $content = array($content);
        }
        $this->content = $content;
    }

    function execute($revision = false) {
        $returnValue = array();
        if ($revision) {
            $this->initTables();
            if ($this->isNotExecuted()) {
                foreach ($this->content as $contentEntry) {
                    $returnValue[] = $this->dbDriver->execute($contentEntry);
                }
                $this->insertRev();
            }
            $this->increaseRev();
        } else {
            foreach ($this->content as $contentEntry) {
                $returnValue[] = $this->dbDriver->execute($contentEntry);
            }
        }
        return $returnValue;
    }

    abstract function init();

    abstract function setup();

    public function dummyExecute() {
        $this->increaseRev();
    }

    private function increaseRev() {
        $this->revCount++;
    }

    private function getRevId() {
        $rev = "{$this->module}.{$this->lu}.{$this->type}.{$this->name}.r{$this->revCount}";
        return $rev;
    }

    private function insertRev() {
        $content = $this->dbDriver->escapeString(implode(";\n", $this->content).";");
        $tblName = $this->dbDriver->getTableName('system', 'dbsetup', 'revision');
        $sql = "INSERT INTO {$tblName} (rev, content) VALUES('{$this->getRevId()}','{$content}')";
        $this->dbDriver->execute($sql);
    }

    private function initTables() {
        $tblName = $this->dbDriver->getTableName('system', 'dbsetup', 'revision');
        if (!$this->dbDriver->tableExist('system', 'dbsetup', 'revision')) {
            $this->dbDriver->execute(
                    "CREATE TABLE {$tblName} ( 
                        id BIGINT PRIMARY KEY AUTO_INCREMENT,
                        rev VARCHAR(100) NOT NULL UNIQUE,
                        content TEXT NOT NULL
                    )");
        }
    }

    private function isNotExecuted() {
        $tblName = $this->dbDriver->getTableName('system', 'dbsetup', 'revision');
        $sql = "SELECT count(1) cnt FROM {$tblName} WHERE rev = '{$this->getRevId()}'";
        $out = $this->dbDriver->query($sql);
        return $out[0]['cnt'] == '0';
    }

}

?>
