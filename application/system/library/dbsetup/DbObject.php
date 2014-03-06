<?php
namespace application\system\library\dbsetup;

/**
 * Description of DbObject
 *
 * @author Faraj
 */
abstract class DbObject {
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
    
    function setContent($content){
        $this->content = $content;
    }
    
    function execute($revision = false) {
        $returnValue = null;
        if($revision){
            $this->initTables();
            if($this->isNotExecuted()){
                $returnValue = $this->dbDriver->execute($this->content);
                $this->insertRev();
            }
            $this->increaseRev();
        }else{
            $returnValue = $this->dbDriver->execute($this->content);
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
        $tblName = $this->dbDriver->getTableName('system', 'dbsetup', 'revision');
        $sql = "INSERT INTO {$tblName} (rev) VALUES('{$this->getRevId()}')";
        $this->dbDriver->execute($sql);
    }
    
    private function initTables() {
        $tblName = $this->dbDriver->getTableName('system', 'dbsetup', 'revision');
        if(!$this->dbDriver->tableExist('system','dbsetup','revision')){
            $this->dbDriver->execute("CREATE TABLE {$tblName} ( rev VARCHAR(100) NOT NULL UNIQUE)");
        }
    }
    
    private function isNotExecuted(){         
        $tblName = $this->dbDriver->getTableName('system', 'dbsetup', 'revision');
        $sql = "SELECT count(1) cnt FROM {$tblName} WHERE rev = '{$this->getRevId()}'";
        $out = $this->dbDriver->query($sql);
        return $out[0]['cnt'] == '0';
    }
}

?>
