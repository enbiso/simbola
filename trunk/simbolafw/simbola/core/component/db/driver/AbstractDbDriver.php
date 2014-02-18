<?php
namespace simbola\core\component\db\driver;

use simbola\Simbola;
/**
 * Description of AbstractDriver
 *
 * @author farflk
 */
abstract class AbstractDbDriver {    
    protected $server;
    protected $username;
    protected $password;    
    protected $dbname;  
    protected $debug;
    protected $pageLength;  
    protected $connection;    
    
    abstract public function connect();    
    abstract public function _execute($sql);
    abstract public function _execute_multi($sql);
    abstract public function _error();
    abstract public function _num_rows($result);            
    abstract public function _num_fields($result);       
    abstract public function _field_name($result,$index);       
    abstract public function _fetch_assoc($result);    
    abstract public function tableExist($module, $lu_name, $table_name);
    abstract public function moduleExist($module);
    abstract public function moduleCreate($module);
    abstract function directCall($func, $params = array());
    abstract function directView($view, $select = "*", $where = null, $page = null, $pageLenth = null, $order = null);        
    abstract function call($module, $lu, $func, $params = array());        
    abstract function view($module, $lu, $view, $select = "*", $where = null, $page = null, $pageLenth = null, $order = null);
    abstract function getTableName($module, $lu, $name);
    abstract function getViewName($module, $lu, $name);
    abstract function getProcedureName($module, $lu, $name);
    abstract function escapeString($string);

    public function executeMulti($sql) {        
        $result = $this->_execute_multi($sql);
        return $this->errorHandle($result,$sql);
    }
   
    public function execute($sql) {
        $result = $this->_execute($sql);
        return $this->errorHandle($result,$sql);
    }
    
    public function errorHandle($result,$sql) {
        if(Simbola::app()->isDev()){
            if($this->debug || !$result){
                echo '<pre>Sql: '.$sql.'</pre>';        
            }
            if(!$result){            
                $message = '<div>Error: '. $this->_error() .'</div>';
                die($message);
            }
        }
        return $result;   
    }
    
    public function getDataArray($result) {        
        $data = array();
        $rowNumber = 0;
        while (($rowData = $this->_fetch_assoc($result))) {
            $data[$rowNumber++] = $rowData;            
        }
        return $data;
    }
    
    public function getFieldNames($result) {
        $rows = $this->_num_fields($result);
        $fieldNames = array();
        for ($index = 0; $index < $rows; $index++) {
            $fieldNames[$index] = $this->_field_name($result, $index);
        }
        return $fieldNames;   
    }
    
    public function countRows($result) {
        return $this->_num_rows($result);
    }
    
    public function query($sql){
        $result = $this->execute($sql);
        return $this->getDataArray($result);
    }
    
    public function getDbName() {
        return $this->dbname;
    }
    
    public function setDebug($debug) {
        $this->debug = $debug;
    }
    
    public function setDbName($dbname) {
        $this->dbname = $dbname;
    }
    
    public function setServer($server) {
        $this->server = $server;
    }
    
    public function setUsername($username) {
        $this->username = $username;
    }
    
    public function setPassword($password) {
        $this->password = $password;
    }
    
    public function setPageLength($pageLength) {
        $this->pageLength = $pageLength;
    }
}

?>
