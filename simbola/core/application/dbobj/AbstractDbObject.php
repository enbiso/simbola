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
    protected $content = array();
    protected $type;
    protected $revCount = 0;
    
    /**
     * Enable revisions Flag
     * @var boolean 
     */
    protected $enableRev = true;

    /**
     * Constructor
     * @param \simbola\core\component\db\driver\AbstractDbDriver $db Database Driver
     */
    function __construct($db) {
        $this->dbDriver = $db;
        $this->init();
    }

    /**
     * Set module name
     * @param string $module Module name
     */
    function setModule($module) {
        $this->module = $module;
    }

    /**
     * Set LU name
     * @param string $lu logical unit name
     */
    function setLu($lu) {
        $this->lu = $lu;
    }

    /**
     * Set database object name
     * @param string $name Name
     */
    function setName($name) {
        $this->name = $name;
    }

    /**
     * Get DB object name
     * @return string Name
     */
    function getName() {
        return $this->name;
    }

    /**
     * Set SQL content
     * @param string $content SQL content
     */
    function setContent($content) {
        if(is_string($content)){
            $content = array($content);
        }
        $this->content = $content;
    }

    /**
     * Execute db object     
     */
    protected function execute() {  
        if ($this->enableRev) {
            $this->initTables();
            if ($this->isNotExecuted()) {
                $this->content[] = $this->insertRevScript();
                $this->dbDriver->executeMulti(implode(";", $this->content));          
            }
            $this->increaseRev();
        } else {
            $this->dbDriver->executeMulti(implode(";", $this->content));
        }        
    }

    /**
     * Initialization
     */
    protected abstract function init();

    /**
     * Setup db object
     */
    public abstract function setup();

    /**
     * Dummy DB execute to enable revision increase
     */
    protected function dummyExecute() {
        $this->increaseRev();
    }

    /**
     * Increase revision
     */
    private function increaseRev() {
        $this->revCount++;
    }

    /**
     * Get revision ID
     * @return string Rev ID
     */
    private function getRevId() {
        $rev = "{$this->module}.{$this->lu}.{$this->type}.{$this->name}.r{$this->revCount}";
        return $rev;
    }

    /**
     * Insert new revison
     * @return string SQL Stmt
     */
    private function insertRevScript() {
        $content = $this->dbDriver->escapeString(implode(";\n", $this->content).";");
        $tblName = $this->dbDriver->getTableName('system', 'setup', 'dbRevision');
        $sql = "INSERT INTO {$tblName} (rev, content) VALUES('{$this->getRevId()}','{$content}')";
        return $sql;
    }

    /**
     * Initalization of revision table
     */
    private function initTables() {        
        if (!$this->dbDriver->tableExist('system', 'setup', 'dbRevision')) {
            $dbObjClassName = AbstractDbObject::getClass("system", "setup", "table", "dbRevision");
            $dbObj = new $dbObjClassName($this->dbDriver);
            $dbObj->setup();            
        }
    }
    /**
     * Check if object not executed
     * @return boolean
     */
    public function isNotExecuted() {
        $tblName = $this->dbDriver->getTableName('system', 'setup', 'dbRevision');
        $sql = "SELECT count(1) cnt FROM {$tblName} WHERE rev = '{$this->getRevId()}'";
        $out = $this->dbDriver->query($sql);
        return $out[0]['cnt'] == '0';
    }
   
    /**
     * Run the custom SQL script(s)
     * @param string/array $sqls SQL statement(s)
     */
    public function runSQLs($sqls) {
        if(is_string($sqls)){
            $sqls = array($sqls);
        }        
        $this->setContent($sqls);
        $this->execute();
    }
    
    /**
     * Get object class name
     * @param type $module Module name
     * @param type $lu Logical Unit
     * @param type $type Object Type
     * @param type $name Object name
     * @return String Class name
     */
    public static function getClass($module, $lu, $type, $name) {
        $name = sstring_underscore_to_camelcase($name, true);
        return \simbola\Simbola::app()->getModuleNamespace($module, "database")
                    . "\\" . $lu . '\\' . $type . '\\' . $name;
    }
    
}

?>
