<?php
namespace simbola\core\component\db\driver;

use simbola\Simbola;
/**
 * AbstractDriver definitions
 *
 * @author Faraj Farook
 */
abstract class AbstractDbDriver {    
    
    /**     
     * Server name HOSTNAME:PORT
     * @var string
     */
    protected $server;
    
    /**
     * Database username
     * @var string
     */
    protected $username;
    
    /**
     * Database password
     * @var string
     */
    protected $password;
    
    /**
     * Database name
     * @var string
     */
    protected $dbname;  
    
    /**
     * Page count of desplay
     * @var integer 
     */
    protected $pageLength;  
    
    /**
     * Database connection object
     * @var mixed
     */
    protected $connection;    
    
    abstract function connect();    
    abstract protected function _execute($sql, $params = array(), $log = true);
    abstract protected function _execute_multi($sql, $params = array(), $log = true);
    abstract protected function _error();
    abstract protected function _num_rows($result);            
    abstract protected function _num_fields($result);       
    abstract protected function _field_name($result,$index);       
    abstract protected function _fetch_assoc($result);   
    abstract protected function _escape_string($string);
    abstract function tableExist($module, $lu, $name);
    abstract function viewExist($module, $lu, $name);
    abstract function moduleExist($module);
    abstract function moduleCreate($module);
    abstract function getMetaInfo($module, $lu, $name, $allFields = false);
    abstract function directCall($func, $params = array());
    abstract function directView($view, $select = "*", $where = null, $page = null, $pageLenth = null, $order = null);        
    abstract function getTableName($module, $lu, $name);
    abstract function getViewName($module, $lu, $name);
    abstract function getProcedureName($module, $lu, $name);
    abstract function getSourceFromTableName($fullName);
    abstract function getSourceFromViewName($fullName);
    abstract function getSourceFromProcedureName($fullName);
    
    /**
     * Escape the string according to the database
     * 
     * @param string $string
     * @return string
     */
    public function escapeString($string) {
        return $this->_escape_string($string);
    }

    
    /**
     * Calls the database function
     * 
     * @param string $module Module name
     * @param string $lu Logical unit name
     * @param string $name Method name
     * @param array $params Parameters
     * @return type mixed
     */
    public function call($module, $lu, $name, $params = array()) {
        $func = $this->getProcedureName($module, $lu, $name);
        return $this->directCall($func, $params);
    }

    /**
     * Calls the database view
     * 
     * @param string $module Module name
     * @param string $lu Logical unit name
     * @param string $name View name
     * @param string $select Select statement entries
     * @param string $where Where clause 
     * @param int $page Page number to display
     * @param int $pageLenth Page length
     * @param string $order Order by clause
     * @return array Data array
     */
    public function view($module, $lu, $name, $select = "*", $where = null, $page = null, $pageLenth = null, $order = null) {
        $view = $this->getViewName($module, $lu, $name);
        return $this->directView($view, $select, $where, $page, $pageLenth, $order);
    }
    
    /**
     * Execute multiple SQL queries
     * 
     * @param array $sql Array of SQL Query to execute
     * @param array $params query parameter
     * @param boolean $log TRUE(default) - DB logs, FALSE - no DB logs
     * @return boolean
     */
    public function executeMulti($sql, $params = array(), $log = true) {        
        $result = $this->_execute_multi($sql, $params, $log);        
        return $this->errorHandleMulti($result,$sql);
    }
   
    /**
     * Execute SQL querie
     * 
     * @param string $sql SQL Query to execute
     * @param array $params query parameter
     * @param boolean $log TRUE(default) - DB logs, FALSE - no DB logs
     * @return result Database quesry results
     */
    public function execute($sql, $params = array(), $log = true) {
        $result = $this->_execute($sql, $params, $log);
        return $this->errorHandle($result,$sql);
    }
    
        /**
     * Implementation of multi error handling
     * 
     * @param boolean $result
     * @param string $sql
     * @return result
     * @throws \Exception DB error
     */
    protected function errorHandleMulti($result, $sql) {
        if(Simbola::app()->isDev()){
            if($result === FALSE){            
                throw new \Exception("Error: " . $sql);                    
            }
        }
        return $result;   
    }
    
    /**
     * Implementation of error handling
     * 
     * @param result $result
     * @param string $sql
     * @return result
     * @throws \Exception DB error
     */
    protected function errorHandle($result, $sql) {
        if(Simbola::app()->isDev()){
            if($result === FALSE){            
                $error = $this->_error();
                if(!empty($error)){
                    throw new \Exception($error);    
                }
            }
        }
        return $result;   
    }
    
    /**
     * Get data array from the result
     * 
     * @param result $result
     * @return array
     */
    public function getDataArray($result) {        
        $data = array();
        $rowNumber = 0;
        while (($rowData = $this->_fetch_assoc($result))) {
            $data[$rowNumber++] = $rowData;            
        }
        return $data;
    }
    
    /**
     * Get field names from the result
     * 
     * @param result $result
     * @return array
     */
    public function getFieldNames($result) {
        $rows = $this->_num_fields($result);
        $fieldNames = array();
        for ($index = 0; $index < $rows; $index++) {
            $fieldNames[$index] = $this->_field_name($result, $index);
        }
        return $fieldNames;   
    }
    
    /**
     * Gets the row count from the result
     * 
     * @param result $result
     * @return integer 
     */
    public function countRows($result) {
        return $this->_num_rows($result);
    }
    
    /**
     * SQL query
     * 
     * @param string $sql SQL Query to execute
     * @param array $params query parameter
     * @param boolean $log TRUE(default) - DB logs, FALSE - no DB logs
     * @return result Database quesry results
     */
    public function query($sql, $params = array(), $log = true) {        
        $result = $this->execute($sql, $params, $log);
        return $this->getDataArray($result);
    }
    
    /**
     * Get database name
     * 
     * @return string
     */
    public function getDbName() {
        return $this->dbname;
    }
    
    /**
     * Set database name
     * 
     * @param string $dbname
     */
    public function setDbName($dbname) {
        $this->dbname = $dbname;
    }
    
    /**
     * Get database server name
     * 
     * @param string $server
     */
    public function setServer($server) {
        $this->server = $server;
    }
    
    /**
     * Set database username
     * 
     * @param string $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }
    
    /**
     * Set database password
     * 
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }
    
    /**
     * Set page length
     * 
     * @param int $pageLength
     */
    public function setPageLength($pageLength) {
        $this->pageLength = $pageLength;
    }
}

?>
