<?php
namespace simbola\core\component\db\driver;
/**
 * PGSQL Driver definitions
 *
 * @author Faraj Farook
 */
class PgSQLDriver extends AbstractDbDriver {

    /**
     * Connect to PGSQL database
     * This is a framework function. Do not use this function on implementations.
     */
    public function connect() {
        $port = "5432"; // default port
        $server = $this->server;
        if (strpos($server, ":")) {
            $arr = explode(":", $server);
            $port = $arr[0];
            $server = $arr[1];
        }
        $connStr = "host={$server} port={$port} dbname={$this->dbname} user={$this->username} password={$this->password}";
        $this->connection = pg_pconnect($connStr);
    }

    /**
     * Implementation execute multiple query strings
     * This is a framework function. Do not use this function on implementations.
     * 
     * @param string $sql Query string
     * @param array $params Parameters
     * @param boolean $log Used to setup the logging on database query execution
     *          TRUE  - enable DB logs on execution (default)
     *          FALSE - disable DB logs on execution 
     * @return resource PGSQL result
     */
    protected function _execute_multi($sql, $params = array(), $log = true) {
        return $this->_execute($sql);
    }

    /**
     * Implementation execute function of query
     * This is a framework function. Do not use this function on implementations.
     * 
     * @param string $sql Query string
     * @param array $params Parameters
     * @param boolean $log Used to setup the logging on database query execution
     *          TRUE  - enable DB logs on execution (default)
     *          FALSE - disable DB logs on execution 
     * @return resource PGSQL result
     */
    protected function _execute($sql, $params = array(), $log = true) {
        return pg_query($this->connection, $sql);
    }

    /**
     * Returns the string description of the last error
     * This is a framework function. Do not use this function on implementations.
     * 
     * @return string
     */
    protected function _error() {
        return pg_errormessage($this->connection);
    }

    /**
     * Number of rows in the result object
     * This is a framework function. Do not use this function on implementations.
     * 
     * @param resource $result
     * @return integet
     */
    protected function _num_rows($result) {
        return pg_num_rows($result);
    }

    /**
     * Fetch the data as an associate array
     * This is a framework function. Do not use this function on implementations.
     * 
     * @param resource $result
     * @return array
     */
    protected function _fetch_assoc($result) {
        return pg_fetch_assoc($result);
    }

    /**
     * Name of the field specified by the column index
     * This is a framework function. Do not use this function on implementations. 
     * 
     * @param resource $result
     * @param integer $index The index of the column
     * @return type
     */
    protected function _field_name($result, $index) {
        return pg_field_name($result, $index);
    }

    protected function _num_fields($result) {
        return pg_num_fields($result);
    }

    /**
     * Returns the escaped string representation of the string provided
     * This is a framework function. Do not use this function on implementations. 
     * 
     * @param string $string
     * @return string Escaped string for the query
     */
    protected function _escape_string($string) {
        return pg_escape_string($this->connection, $string);
    }
    
    /**
     * Directly call the mysql functions from the full qualify names
     * 
     * @param string $func Function name in full
     * @param array $params Function parameters
     * @return mixed
     */
    public function directCall($func, $params = array()) {
        $sql = "SELECT $func(";
        foreach ($params as $key => $value) {
            $type = (is_array($value) && isset($value['type'])) ? $value['type'] : Database::$STRING;
            $value = (is_array($value) && isset($value['value'])) ? $value['value'] : $value;
            if ($value == '') {
                $sql.="NULL,";
            } elseif ($type == Database::$STRING || $type == Database::$DATE) {
                $sql.="'$value',";
            } elseif ($type == Database::$NUMBER) {
                $value = is_numeric($value) ? $value : 'NULL';
                $sql.="$value,";
            }
        }
        $sql = rtrim($sql, ",");
        $sql .= ") AS data";
        $dataArray = $this->query($sql);
        return $dataArray[0]['data'];
    }

    /**
     * Fetch the fully qualified view according to the parameters
     * 
     * @param string $view Fully qualified view name
     * @param string $select Select statement entries
     * @param string $where Where clause 
     * @param int $page Page number to display
     * @param int $pageLenth Page length
     * @param string $order Order by clause
     * @return array Data array
     */
    public function directView($view, $select = "*", $where = null, $page = null, $pageLenth = null, $order = null) {
        $where = isset($where) ? " WHERE $where" : "";
        $order = isset($order) ? " ORDER BY $order" : "";
        $length = isset($pageLenth) ? $pageLenth : $this->pageLength;
        if (isset($page)) {
            $from = ($page - 1) * $length;
            $limit = " LIMIT $length OFFSET $from";
        } else {
            $limit = '';
        }

        $sql = "SELECT $select FROM $view" . $where . $order . $limit;        
        $sql_count = "SELECT $select FROM $view" . $where;
        $result = $this->execute($sql);
        $result_count = $this->execute($sql_count);
        $records['data'] = $this->getDataArray($result);
        $records['fields'] = $this->getFieldNames($result);
        $total_rows = $this->countRows($result_count);
        $end_page_num = ceil($total_rows / $length);
        $records['pages'] = range(1, $end_page_num > 0 ? $end_page_num : 1);
        $records['total_rows'] = $total_rows;
        $records['current_page'] = $page;
        return $records;
    }
    
    /**
     * Check the table exist in the database
     * 
     * @param string $module Module name
     * @param string $lu Logical unit name
     * @param string $name Table name
     * @return boolean
     */
    public function tableExist($module, $lu, $name) {
        $sql = "SELECT count(1) FROM pg_tables WHERE schemaname='{$module}' AND tablename='{$lu}_tbl_{$name}'";
        $data = $this->query($sql);
        return $data[0]['count'] > '0';
    }
    
    /**
     * Check if the module exist in the mysql database
     * 
     * @param string $module Module name
     * @return boolean
     */
    public function moduleExist($module) {
        $sql = "SELECT count(*) FROM pg_namespace WHERE nspname = '{$module}'";
        $data = $this->query($sql);
        return $data[0]['count'] > '0';
    }

    /**
     * Check if the view exist in the database
     * 
     * @param string $module Module name
     * @param string $lu Logical unit name
     * @param string $name View name
     * @return type
     */
    public function viewExist($module, $lu, $name) {
        throw new \Exception(__METHOD__." not implemented");
    }

    /**
     * Create the module in the mysql database
     * 
     * @param string $module Module name
     * @return boolean
     */
    public function moduleCreate($module) {
        $sql = "CREATE SCHEMA \"{$module}\"";
        $this->execute($sql);
    }

    /**
     * Get the procedure fully qualified name
     * 
     * @param string $module Module name
     * @param string $lu Logical unit name
     * @param string $name Method name
     * @return string
     */
    public function getProcedureName($module, $lu, $name) {
        return "{$module}.{$lu}_{$func}";
    }
    
    /**
     * Get the table fully qualified name
     * 
     * @param string $module Module name
     * @param string $lu Logical unit name
     * @param string $name Table name
     * @return string
     */
    public function getTableName($module, $lu, $name) {
        return "{$module}.{$lu}_tbl_{$name}";
    }

    /**
     * Get the view fully qualified name
     * 
     * @param string $module Module name
     * @param string $lu Logical unit name
     * @param string $name View name
     * @return string
     */
    public function getViewName($module, $lu, $name) {
        return "{$module}.{$lu}_{$name}";
    }

    /**
     * 
     * @todo Implement me
     * @param type $module
     * @param type $lu
     * @param type $name
     * @param type $allFields
     * @throws \Exception
     */
    public function getMetaInfo($module, $lu, $name, $allFields = false) {
        throw new \Exception(__METHOD__." not implemented");
    }

    /**
     * Gets the Module, LU and Name from the fully qualifed name
     * 
     * @todo Need to implement
     * @param string $fullName Fully qualifed procedure/function name
     * @return array Associate array of module, lu and name
     */
    public function getSourceFromProcedureName($fullName) {
        throw new \Exception(__METHOD__." not implemented");
    }

    /**
     * Gets the Module, LU and Name from the fully qualifed name
     * 
     * @todo Need to implement
     * @param string $fullName Fully qualifed table name
     * @return array Associate array of module, lu and name
     */
    public function getSourceFromTableName($fullName) {
        throw new \Exception(__METHOD__." not implemented");
    }

    /**
     * Gets the Module, LU and Name from the fully qualifed name
     * 
     * @todo Need to implement
     * @param string $fullName Fully qualifed view name
     * @return array Associate array of module, lu and name
     */
    public function getSourceFromViewName($fullName) {
        throw new \Exception(__METHOD__." not implemented");
    }

}

?>
