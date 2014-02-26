<?php
namespace simbola\core\component\log;
/**
 * Description of Log
 *
 * @author farflk
 */
class Log extends \simbola\core\component\system\lib\Component {

    const INFO = 'INFO';
    const WARN = 'WARN';
    const ERROR = 'ERROR';
    const SYSTEM = 'SYSTEM';
    const DEBUG = 'DEBUG';
    const TRACE = 'TRACE';
    const LOG = 'LOG';
    const DB = 'DB';

    private $moduleName = 'system';
    private $luName = 'logger';    
    private $tableName = 'log';
    private $viewName = 'log';
    
    public function init() {
        parent::init();        
        $db = \simbola\Simbola::app()->db;
        if (!$db->moduleExist($this->moduleName)) {
            $db->moduleCreate($this->moduleName);
        }
        if (!$db->tableExist($this->moduleName, $this->luName, $this->tableName)) {
            $tableName = \simbola\Simbola::app()->db->getTableName($this->moduleName, $this->luName, $this->tableName);
            $sql = "CREATE TABLE {$tableName} (
                            _date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                            _type VARCHAR(10),
                            _message VARCHAR(1000)
                        )";
            $db->execute($sql); 
            $viewName = \simbola\Simbola::app()->db->getViewName($this->moduleName, $this->luName, $this->viewName);
            $sql = "CREATE OR REPLACE VIEW {$viewName} AS 
                        SELECT * FROM {$tableName} ORDER BY _date DESC";   
            $db->execute($sql); 
        }
            
        if(!isset($this->params['TYPES'])){
            $this->params['TYPES'] = array('ERROR');
        }
    }

    public function add($type, $info) {                
        if(in_array($type, $this->params['TYPES'])){            
            $db = \simbola\Simbola::app()->db;
            $tableName = \simbola\Simbola::app()->db->getTableName($this->moduleName, $this->luName, $this->tableName);
            $info = \simbola\Simbola::app()->db->escapeString($info);
            $sql = "INSERT INTO {$tableName}(_type,_message) VALUES('{$type}','{$info}')";
            $db->execute($sql, array(), false);        
        }
    }
    
    public function system($info) {
        $this->add(self::SYSTEM, $info);
    }
    public function info($info) {                        
        $this->add(self::INFO, $info);
    }
    public function error($info) {
        $this->add(self::ERROR, $info);
    }
    public function warn($info) {
        $this->add(self::WARN, $info);
    }
    public function debug($info) {
        $this->add(self::DEBUG, $info);
    }
    public function trace($info) {
        $this->add(self::TRACE, $info);
    }
    public function log($info) {
        $this->add(self::LOG, $info);
    }
    public function db($info) {
        $this->add(self::DB, $info);
    }
}

?>
