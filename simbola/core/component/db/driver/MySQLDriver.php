<?php
namespace simbola\core\component\db\driver;

use simbola\core\component\db\Database;
/**
 * Description of MySQLDriver
 *
 * @author farflk
 */
class MySQLDriver extends AbstractDbDriver {

    public function connect() {
        $this->connection = mysqli_connect($this->server, $this->username, $this->password);
        mysqli_select_db($this->connection, $this->dbname);
    }

    public function _execute_multi($sql) {
        return $this->_execute($sql);
    }

    public function _execute($sql) {
        return mysqli_query($this->connection, $sql);
    }

    public function _fetch_assoc($result) {
        return mysqli_fetch_assoc($result);
    }

    public function _num_rows($result) {
        return mysqli_num_rows($result);
    }

    public function _num_fields($result) {
        return mysqli_num_fields($result);
    }

    public function _field_name($result, $index) {
        $fields = mysqli_fetch_fields($result);
        return $fields[$index]->name;
    }

    public function _error() {
        return mysqli_error($this->connection);
    }

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
        $sql = "SELECT count(*) AS count
                FROM information_schema.tables 
                WHERE table_schema = '".$this->getDBName()."' 
                AND table_name = '{$table_fullname}'";                
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
        return mysqli_escape_string($this->connection, $string);
    }

}

?>
