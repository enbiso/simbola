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
     * @return returnValue
     */
    protected function execute() {
        $returnValue = array();
        if ($this->enableRev) {
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

    /**
     * Initialization
     */
    abstract function init();

    /**
     * Setup db object
     */
    abstract function setup();

    /**
     * Dummy DB execute to enable revision increase
     */
    public function dummyExecute() {
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
     */
    private function insertRev() {
        $content = $this->dbDriver->escapeString(implode(";\n", $this->content).";");
        $tblName = $this->dbDriver->getTableName('system', 'dbsetup', 'revision');
        $sql = "INSERT INTO {$tblName} (rev, content) VALUES('{$this->getRevId()}','{$content}')";
        $this->dbDriver->execute($sql);
    }

    /**
     * Initalization of revision table
     */
    private function initTables() {        
        if (!$this->dbDriver->tableExist('system', 'dbsetup', 'revision')) {
            $dbObjClassName = AbstractDbObject::getClass("system", "dbsetup", "table", "revision");
            $dbObj = new $dbObjClassName($this->dbDriver);
            $dbObj->setup();            
        }
    }
    /**
     * Check if object not executed
     * @return boolean
     */
    public function isNotExecuted() {
        $tblName = $this->dbDriver->getTableName('system', 'dbsetup', 'revision');
        $sql = "SELECT count(1) cnt FROM {$tblName} WHERE rev = '{$this->getRevId()}'";
        $out = $this->dbDriver->query($sql);
        return $out[0]['cnt'] == '0';
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
        return \simbola\Simbola::app()->getModuleNamespace($module, "database")
                    . "\\" . $lu . '\\' . $type . '\\' . $name;
    }
    
}

?>
