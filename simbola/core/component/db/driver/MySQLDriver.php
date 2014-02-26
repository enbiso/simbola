<?php

namespace simbola\core\component\db\driver;

use simbola\core\component\db\Database;

/**
 * Description of MySQLDriver
 *
 * @author farflk
 */
class MySQLDriver extends AbstractDbDriver {

    //DIRECT DB FUNCTIONS - START
    public function connect() {
        $this->connection = mysqli_connect($this->server, $this->username, $this->password);        
        mysqli_select_db($this->connection, $this->dbname);
//        $this->connection = new \PDO("mysql:dbname={$this->dbname};host={$this->server};charset=utf8", $this->username, $this->password);
    }

    public function _execute_multi($sql, $params = array(), $log = true) {
        return $this->_execute($sql, $params, $log);
    }

    public function _execute($sql, $params = array(), $log = true) {
        if ($log) {
            slog_db($sql . " - " . var_export($params, true));
        }
        return mysqli_query($this->connection, $sql);
//        $stmt = $this->connection->prepare($sql);
//        foreach ($params as $key => $value) {
//            $stmt->bindParam(':' . $key, $value);
//        }
//        $stmt->execute();
//        return $stmt;
    }

    public function _fetch_assoc($result) {
        return mysqli_fetch_assoc($result);
//        $data = $result->fetchAll(\PDO::FETCH_NAMED);
//        if(count($data) > 0){
//            return $data[0];
//        }else{
//            return array();
//        }        
    }

    public function _num_rows($result) {
        return mysqli_num_rows($result);
//        return $result->rowCount();
    }

    public function _num_fields($result) {
        return mysqli_num_fields($result);
//        return $result->columnCount();
    }

    public function _field_name($result, $index) {
        $fields = mysqli_fetch_fields($result);
        return $fields[$index]->name;
//        $meta = $result->getColumnMeta($index);
//        return $meta['name'];
    }

    public function _error() {
        return mysqli_error($this->connection);
//        return $this->connection->errorInfo();
    }

    public function _escape_string($string) {
        return mysqli_escape_string($this->connection, $string);
//        return $string;
    }
    //DIRECT DB FUNCTIONS - END

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

    public function call($module, $lu, $name, $params = array()) {
        $func = $this->getProcedureName($module, $lu, $name);
        return $this->directCall($func, $params);
    }

    public function view($module, $lu, $name, $select = "*", $where = null, $page = null, $pageLenth = null, $order = null) {
        $view = $this->getViewName($module, $lu, $name);
        return $this->directView($view, $select, $where, $page, $pageLenth, $order);
    }

    public function tableExist($module, $lu, $name) {
        $table_fullname = $this->getTableName($module, $lu, $name);
        $sql = "SELECT count(1) AS count
                FROM information_schema.tables 
                WHERE table_schema = '" . $this->getDBName() . "' 
                AND table_name = '{$table_fullname}'";
        $data = $this->query($sql);        
        return $data[0]['count'] > 0;
    }

    public function viewExist($module, $lu, $name) {
        $view_fullname = $this->getViewName($module, $lu, $name);
        $sql = "SELECT count(1) AS count
                FROM information_schema.views 
                WHERE table_schema = '" . $this->getDBName() . "' 
                AND table_name = '{$view_fullname}'";
        $data = $this->query($sql);
        return $data[0]['count'] > 0;
    }

    public function moduleCreate($module) {
        return true;
    }

    public function moduleExist($module) {
        return true;
    }

    public function getProcedureName($module, $lu, $name) {
        return "{$module}_{$lu}_{$func}";
    }

    public function getTableName($module, $lu, $name) {
        return "{$module}_{$lu}_tbl_{$name}";
    }

    public function getViewName($module, $lu, $name) {
        return "{$module}_{$lu}_{$name}";
    }

    public function escapeString($string) {
        return $this->_escape_string($string);
    }

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

    public function getSourceFromProcedureName($procName) {
        $arr = explode("_", $procName);
        return array(
            'module' => $arr[0],
            'lu' => $arr[1],
            'name' => implode("_", array_slice($arr, 2)),
        );
    }

    public function getSourceFromTableName($tableName) {
        $arr = explode("_", $tableName);
        return array(
            'module' => $arr[0],
            'lu' => $arr[1],
            'name' => implode("_", array_slice($arr, 3)),
        );
    }

    public function getSourceFromViewName($viewName) {
        $arr = explode("_", $viewName);
        return array(
            'module' => $arr[0],
            'lu' => $arr[1],
            'name' => implode("_", array_slice($arr, 2)),
        );
    }

}

?>
