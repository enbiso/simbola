<?php

namespace simbola\core\component\log;

/**
 * Log component definitions
 *
 * @author Faraj Farook
 */
class Log extends \simbola\core\component\system\lib\Component {

    const TYPE_INFO = 'INFO';
    const TYPE_WARN = 'WARN';
    const TYPE_ERROR = 'ERROR';
    const TYPE_SYSTEM = 'SYSTEM';
    const TYPE_DEBUG = 'DEBUG';
    const TYPE_TRACE = 'TRACE';
    const TYPE_LOG = 'LOG';
    const TYPE_DB = 'DB';

    /**
     * Module name
     * @var string 
     */
    private $moduleName = 'system';
    
    /**
     * Logical unit name
     * @var string 
     */
    private $luName = 'logger';
    
    /**
     * Table name
     * @var string 
     */
    private $tableName = 'log';

    /**
     * Initialization of the component
     */
    public function init() {
        $dbDriver = \simbola\Simbola::app()->db->getDriver();
        if ($this->isNewInstallation()) {
            $dbObjClassName = AbstractDbObject::getClass($this->moduleName, $this->luName, "table", $this->tableName);
            $dbObj = new $dbObjClassName($dbDriver);
            $dbObj->execute(true);
        }

        if (!isset($this->params['TYPES'])) {
            $this->params['TYPES'] = array('ERROR');
        }
        parent::init();
    }

    /**
     * Check if the component database object exist
     * 
     * @return boolean
     */
    public function isNewInstallation() {
        $dbDriver = \simbola\Simbola::app()->db->getDriver();
        return !$dbDriver->tableExist($this->moduleName, $this->luName, $this->tableName);
    }

    /**
     * Add log message
     * 
     * @param string $type Type of log Log::TYPE_*
     * @param string $message Log message
     */
    public function add($type, $message) {
        if (in_array($type, $this->params['TYPES']) && $this->isInit) {
            $dbDriver = \simbola\Simbola::app()->db->getDriver();
            $tableName = $dbDriver->getTableName($this->moduleName, $this->luName, $this->tableName);
            $message = $dbDriver->escapeString($message);
            $traceArray = array_reverse(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
            $traces = array();
            foreach ($traceArray as $trace) {
                $traces[] = (isset($trace['class'])?$trace['class'].".":'') . $trace['function'] . "()";                                    
            }            
            $traces = $dbDriver->escapeString(implode("\n", $traces));
            $sql = "INSERT INTO {$tableName}(type, message, trace) VALUES('{$type}','{$message}','{$traces}')";
            $dbDriver->execute($sql, array(), false);
        }
    }

    /**
     * Add system logs
     * 
     * @param string $message
     */
    public function system($message) {
        $this->add(self::TYPE_SYSTEM, $message);
    }

    /**
     * Add info logs
     * 
     * @param string $message
     */
    public function info($message) {
        $this->add(self::TYPE_INFO, $message);
    }

    /**
     * Add error logs
     * 
     * @param string $message
     */
    public function error($message) {
        $this->add(self::TYPE_ERROR, $message);
    }

    /**
     * Add warn logs
     * 
     * @param string $message
     */
    public function warn($message) {
        $this->add(self::TYPE_WARN, $message);
    }

    /**
     * Add debug logs
     * 
     * @param string $message
     */
    public function debug($message) {
        $this->add(self::TYPE_DEBUG, $message);
    }

    /**
     * Add trace logs
     * 
     * @param string $message
     */
    public function trace($message) {
        $this->add(self::TYPE_TRACE, $message);
    }
/**
     * Add default logs
     * 
     * @param string $message
     */
    public function log($message) {
        $this->add(self::TYPE_LOG, $message);
    }

    /**
     * Add database logs
     * 
     * @param string $message
     */
    public function db($message) {
        $this->add(self::TYPE_DB, $message);
    }

}

?>
