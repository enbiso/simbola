<?php
namespace application\system\library\dbsetup;

/**
 * Description of DbObject
 *
 * @author Faraj
 */
abstract class DbObject {
    protected $db;
    protected $module;
    protected $lu;
    protected $name;
    protected $content;
    protected $type;
    protected $revCount = 0;
    
    function __construct($db) {
        $this->db = $db;
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
                $returnValue = $this->db->execute($this->content);
                $this->insertRev();
            }
            $this->increaseRev();
        }else{
            $returnValue = $this->db->execute($this->content);
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
        $tblName = \simbola\Simbola::app()->db->getTableName('system', 'dbsetup', 'revision');
        $sql = "INSERT INTO {$tblName} (rev) VALUES('{$this->getRevId()}')";
        $this->db->execute($sql);
    }
    
    private function initTables() {
        $tblName = \simbola\Simbola::app()->db->getTableName('system', 'dbsetup', 'revision');
        if(!\simbola\Simbola::app()->db->tableExist('system','dbsetup','revision')){
            \simbola\Simbola::app()->db->execute("CREATE TABLE {$tblName} ( rev VARCHAR(100) NOT NULL UNIQUE)");
        }
    }
    
    private function isNotExecuted(){         
        $tblName = \simbola\Simbola::app()->db->getTableName('system', 'dbsetup', 'revision');
        $sql = "SELECT count(1) cnt FROM {$tblName} WHERE rev = '{$this->getRevId()}'";
        $out = $this->db->query($sql);
        return $out[0]['cnt'] == '0';
    }
}

?>
