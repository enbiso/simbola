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
        $db = \simbola\Simbola::app()->db;
        if ($this->isNewInstallation()) {
            if (!$db->moduleExist($this->moduleName)) {
                $db->moduleCreate($this->moduleName);
            }
            $tableName = $db->getTableName($this->moduleName, $this->luName, $this->tableName);
            $sql = "CREATE TABLE {$tableName} (
                            date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                            type VARCHAR(10),
                            trace VARCHAR(1000),
                            message VARCHAR(1000)
                        )";
            $db->execute($sql);
            $viewName = $db->getViewName($this->moduleName, $this->luName, $this->viewName);
            $sql = "CREATE OR REPLACE VIEW {$viewName} AS 
                        SELECT * FROM {$tableName} ORDER BY date DESC";
            $db->execute($sql);
        }

        if (!isset($this->params['TYPES'])) {
            $this->params['TYPES'] = array('ERROR');
        }
        parent::init();
    }

    public function isNewInstallation() {
        $db = \simbola\Simbola::app()->db;
        return !$db->tableExist($this->moduleName, $this->luName, $this->tableName);
    }

    public function add($type, $info) {
        if (in_array($type, $this->params['TYPES']) && $this->isInit) {
            $db = \simbola\Simbola::app()->db;
            $tableName = \simbola\Simbola::app()->db->getTableName($this->moduleName, $this->luName, $this->tableName);
            $info = \simbola\Simbola::app()->db->escapeString($info);
            $traceArray = array_reverse(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
            $traces = array();
            foreach ($traceArray as $trace) {
                $traces[] = (isset($trace['class'])?$trace['class'].".":'') . $trace['function'] . "()";                                    
            }            
            $traces = \simbola\Simbola::app()->db->escapeString(implode("\n", $traces));
            $sql = "INSERT INTO {$tableName}(type, message, trace) VALUES('{$type}','{$info}','{$traces}')";
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
