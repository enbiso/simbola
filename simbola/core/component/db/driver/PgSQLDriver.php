<?php
namespace simbola\core\component\db\driver;
/**
 * Description of PgSQLDriver
 *
 * @author Faraj
 */
class PgSQLDriver extends AbstractDbDriver {

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

    public function _execute_multi($sql, $params = array(), $log = true) {
        return $this->_execute($sql);
    }

    public function _execute($sql, $params = array(), $log = true) {
        return pg_query($this->connection, $sql);
    }

    public function _error() {
        return pg_errormessage($this->connection);
    }

    public function _num_rows($result) {
        return pg_num_rows($result);
    }

    public function _fetch_assoc($result) {
        return pg_fetch_assoc($result);
    }

    public function _field_name($result, $index) {
        return pg_field_name($result, $index);
    }

    public function _num_fields($result) {
        return pg_num_fields($result);
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

    public function call($module, $lu, $name, $params = array()) {
        $func = $this->getProcedureName($module, $lu, $name);
        return $this->directCall($func, $params);
    }

    public function view($module, $lu, $name, $select = "*", $where = null, $page = null, $pageLenth = null, $order = null) {
        $view = $this->getViewName($module, $lu, $name);
        return $this->directView($view, $select, $where, $page, $pageLenth, $order);
    }
    
    public function tableExist($module, $lu, $name) {
        $sql = "SELECT count(1) FROM pg_tables WHERE schemaname='{$module}' AND tablename='{$lu}_tbl_{$name}'";
        $data = $this->query($sql);
        return $data[0]['count'] > '0';
    }

    public function moduleExist($module) {
        $sql = "SELECT count(*) FROM pg_namespace WHERE nspname = '{$module}'";
        $data = $this->query($sql);
        return $data[0]['count'] > '0';
    }

    public function moduleCreate($module) {
        $sql = "CREATE SCHEMA \"{$module}\"";
        $this->execute($sql);
    }

    public function getProcedureName($module, $lu, $name) {
        return "{$module}.{$lu}_{$func}";
    }

    public function getTableName($module, $lu, $name) {
        return "{$module}.{$lu}_tbl_{$name}";
    }

    public function getViewName($module, $lu, $name) {
        return "{$module}.{$lu}_{$name}";
    }

    public function escapeString($string) {
        pg_escape_string($this->connection, $string);
    }

    public function getMetaInfo($module, $lu, $name, $allFields = false) {
        throw new \Exception(__METHOD__." not implemented");
    }

    public function getSourceFromProcedureName($procName) {
        throw new \Exception(__METHOD__." not implemented");
    }

    public function getSourceFromTableName($tableName) {
        throw new \Exception(__METHOD__." not implemented");
    }

    public function getSourceFromViewName($viewName) {
        throw new \Exception(__METHOD__." not implemented");
    }

    public function viewExist($module, $lu_name, $view_name) {
        throw new \Exception(__METHOD__." not implemented");
    }

}

?>
