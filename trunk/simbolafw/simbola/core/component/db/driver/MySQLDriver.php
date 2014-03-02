<?php

namespace simbola\core\component\db\driver;

use simbola\core\component\db\Database;

/**
 * MySQL Driver definitions
 *
 * @author Faraj Farook
 */
class MySQLDriver extends AbstractDbDriver {

    /**
     * Connect to MySQL database
     * This is a framework function. Do not use this function on implementations.
     */
    public function connect() {
        $this->connection = mysqli_connect($this->server, $this->username, $this->password);        
        mysqli_select_db($this->connection, $this->dbname);
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
     * @return \mysqli_result MySQLi result
     */
    protected function _execute_multi($sql, $params = array(), $log = true) {
        return $this->_execute($sql, $params, $log);
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
     * @return \mysqli_result MySQLi result
     */
    protected function _execute($sql, $params = array(), $log = true) {
        if ($log) {
            slog_db($sql . " - " . var_export($params, true));
        }
        return mysqli_query($this->connection, $sql);
    }

    /**
     * Fetch the data as an associate array
     * This is a framework function. Do not use this function on implementations.
     * 
     * @param \mysqli_result $result
     * @return array
     */
    protected function _fetch_assoc($result) {
        return mysqli_fetch_assoc($result);
    }

    /**
     * Number of rows in the result object
     * This is a framework function. Do not use this function on implementations.
     * 
     * @param \mysqli_result $result
     * @return integet
     */
    protected function _num_rows($result) {
        return mysqli_num_rows($result);
    }

    /**
     * Number of fields in the result
     * This is a framework function. Do not use this function on implementations.
     * 
     * @param \mysqli_result $result
     * @return integer
     */
    protected function _num_fields($result) {
        return mysqli_num_fields($result);
    }

    /**
     * Name of the field specified by the column index
     * This is a framework function. Do not use this function on implementations. 
     * 
     * @param \mysqli_result $result
     * @param integer $index The index of the column
     * @return type
     */
    protected function _field_name($result, $index) {
        $fields = mysqli_fetch_fields($result);
        return $fields[$index]->name;
    }

    /**
     * Returns the string description of the last error
     * This is a framework function. Do not use this function on implementations.
     * 
     * @return string
     */
    protected function _error() {
        return mysqli_error($this->connection);
    }

    /**
     * Returns the escaped string representation of the string provided
     * This is a framework function. Do not use this function on implementations. 
     * 
     * @param string $string
     * @return string Escaped string for the query
     */
    protected function _escape_string($string) {
        return mysqli_escape_string($this->connection, $string);
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
            $limit = " LIMIT $from,$length";
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
        $table_fullname = $this->getTableName($module, $lu, $name);
        $sql = "SELECT count(1) AS count
                FROM information_schema.tables 
                WHERE table_schema = '" . $this->getDBName() . "' 
                AND table_name = '{$table_fullname}'";
        $data = $this->query($sql);        
        return $data[0]['count'] > 0;
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
        $view_fullname = $this->getViewName($module, $lu, $name);
        $sql = "SELECT count(1) AS count
                FROM information_schema.views 
                WHERE table_schema = '" . $this->getDBName() . "' 
                AND table_name = '{$view_fullname}'";
        $data = $this->query($sql);
        return $data[0]['count'] > 0;
    }

    /**
     * Create the module in the mysql database
     * 
     * @param string $module Module name
     * @return boolean
     */
    public function moduleCreate($module) {
        return true;
    }

    /**
     * Check if the module exist in the mysql database
     * 
     * @param string $module Module name
     * @return boolean
     */
    public function moduleExist($module) {
        return true;
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
        return "{$module}_{$lu}_{$func}";
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
        return "{$module}_{$lu}_tbl_{$name}";
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
        return "{$module}_{$lu}_{$name}";
    }

    /**
     * 
     * @param string $module Module name
     * @param string $lu Logical unit name
     * @param string $name Table name     
     * @param boolean $allFields Specifies if the framework fields should be ignored.
     * @return array The meta data in the following format
     *                  array( 'table'  => array(
     *                              'name' => [TABLE NAME]),
     *                         'columns'   => [COLUMNS],
     *                         'relations' => array(
     *                              'belongs_to' => [BELONGS TO],
     *                              'has_many'   => [HAS MANY]),
     */
    public function getMetaInfo($module, $lu, $name, $allFields = false) {
        $tblName = $this->getTableName($module, $lu, $name);
        //get column meta
        $cols = array();
        $sql = 'SELECT * 
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_SCHEMA = \'' . $this->dbname . '\' 
                  AND TABLE_NAME = \'' . $tblName . '\'';
        foreach ($this->query("SHOW COLUMNS FROM {$tblName}") as $colResult) {
            if ($allFields || !sstring_starts_with($colResult['Field'], "_")) {
                $type = $colResult['Type'];
                $length = null;
                if (sstring_ends_with($type, ')')) {
                    $type = substr($colResult['Type'], 0, strpos($colResult['Type'], "("));
                    $length = (int) substr($colResult['Type'], strpos($colResult['Type'], "(") + 1, -1);
                }
                $cols[$colResult['Field']] = array(
                    'name' => $colResult['Field'],
                    'type' => $type,
                    'length' => $length,
                    'nullable' => $colResult['Null'] == 'YES',
                    'primary_key' => $colResult['Key'] == 'PRI',
                    'unique' => $colResult['Key'] == 'UNI',
                    'default' => $colResult['Default'],
                    'auto_increment' => $colResult['Extra'] == 'auto_increment',
                );
            }
        }
        //get relations - belongs to
        $belongsTo = array();
        $sql = 'SELECT CONSTRAINT_NAME name,
                       TABLE_NAME table_name, 
                       COLUMN_NAME column_name, 
                       REFERENCED_TABLE_NAME ref_table_name, 
                       REFERENCED_COLUMN_NAME ref_column_name
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = \'' . $this->dbname . '\'
                  AND TABLE_NAME = \'' . $tblName . '\'
                  AND REFERENCED_TABLE_SCHEMA IS NOT NULL 
                  AND REFERENCED_TABLE_NAME IS NOT NULL
                  AND REFERENCED_COLUMN_NAME IS NOT NULL';
        foreach ($this->query($sql) as $relResult) {
            $relResult['table'] = $this->getSourceFromTableName($relResult['table_name']);
            $relResult['ref_table'] = $this->getSourceFromTableName($relResult['ref_table_name']);
            $belongsTo[$relResult['name']] = $relResult;
        }
        //get relations - has many
        $hasMany = array();
        $sql = 'SELECT CONSTRAINT_NAME name,
                       TABLE_NAME table_name, 
                       COLUMN_NAME column_name, 
                       REFERENCED_TABLE_NAME ref_table_name, 
                       REFERENCED_COLUMN_NAME ref_column_name
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE REFERENCED_TABLE_SCHEMA = \'' . $this->dbname . '\'
                  AND REFERENCED_TABLE_NAME = \'' . $tblName . '\'
                  AND REFERENCED_TABLE_SCHEMA IS NOT NULL 
                  AND REFERENCED_TABLE_NAME IS NOT NULL
                  AND REFERENCED_COLUMN_NAME IS NOT NULL';
        foreach ($this->query($sql) as $relResult) {
            $relResult['table'] = $this->getSourceFromTableName($relResult['table_name']);
            $relResult['ref_table'] = $this->getSourceFromTableName($relResult['ref_table_name']);
            $hasMany[$relResult['name']] = $relResult;
        }
        //return info
        return array(
            'table' => array(
                'name' => $tblName,
            ),
            'columns' => $cols,
            'relations' => array(
                'belongs_to' => $belongsTo,
                'has_many' => $hasMany,
            ),
        );
    }

    /**
     * Gets the Module, LU and Name from the fully qualifed name
     * 
     * @param string $fullName Fully qualifed procedure/function name
     * @return array Associate array of module, lu and name
     */
    public function getSourceFromProcedureName($fullName) {
        $arr = explode("_", $fullName);
        return array(
            'module' => $arr[0],
            'lu' => $arr[1],
            'name' => implode("_", array_slice($arr, 2)),
        );
    }

    /**
     * Gets the Module, LU and Name from the fully qualifed name
     * 
     * @param string $fullName Fully qualifed table name
     * @return array Associate array of module, lu and name
     */
    public function getSourceFromTableName($fullName) {
        $arr = explode("_", $fullName);
        return array(
            'module' => $arr[0],
            'lu' => $arr[1],
            'name' => implode("_", array_slice($arr, 3)),
        );
    }

    /**
     * Gets the Module, LU and Name from the fully qualifed name
     * 
     * @param string $fullName Fully qualifed view name
     * @return array Associate array of module, lu and name
     */
    public function getSourceFromViewName($fullName) {
        $arr = explode("_", $fullName);
        return array(
            'module' => $arr[0],
            'lu' => $arr[1],
            'name' => implode("_", array_slice($arr, 2)),
        );
    }

}

?>
