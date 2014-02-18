<?php
namespace application\system\controller;

class FlexigridCOntroller extends \simbola\core\application\AppController {

    public function __construct() {
        $this->name = "flexigrid";
        $this->securityBypass = true;
    }

    public function data($module, $lu, $view, $id, $filter, $direct) {
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $rp = isset($_POST['rp']) ? $_POST['rp'] : null;        
        $sortname = !empty($_POST['sortname']) ? $_POST['sortname'] : null;
        $sortorder = !empty($_POST['sortorder']) ? $_POST['sortorder'] : null;
        $query = isset($_POST['query']) ? $_POST['query'] : false;
        $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
        $where = "";
        if (\simbola\Simbola::app()->db->getVendor() == 'MYSQL') {
            if (strpos($query, "%")) {
                $where = (!$query || !$qtype) ? null : "`{$qtype}` LIKE '{$query}'";
            } else if (is_string($query)) {
                $where = (!$query || !$qtype) ? null : "`{$qtype}` = '{$query}'";
            } else {
                $where = (!$query || !$qtype) ? null : "`{$qtype}` = {$query}";
            }
        } else if (\simbola\Simbola::app()->db->getVendor() == 'PGSQL') {
            if (strpos($query, "%")) {
                $where = (!$query || !$qtype) ? null : "{$qtype} LIKE '{$query}'";
            } else if (is_string($query)) {
                $where = (!$query || !$qtype) ? null : "{$qtype} = '{$query}'";
            } else {
                $where = (!$query || !$qtype) ? null : "{$qtype} = {$query}";
            }
        }
        if (isset($filter) && $filter != "_FILTER_") {
            $where .= (($where != "") ? " AND " : " ") . $filter;
        }
        $select = "*";
        $rdata = null;

        $order = null;
        if(isset($sortname) && isset($sortorder)){
            $order = $sortname . " " . $sortorder;
        }
        
        if ($direct) {
            $view_fullname = \simbola\Simbola::app()->db->getViewName($module, $lu, $view);
            $rdata = \simbola\Simbola::app()->db->directView($view_fullname, $select, $where, $page, $rp, $order);
        } else {
            $rdata = \simbola\Simbola::app()->db->view($module, $lu, $view, $select, $where, $page, $rp, $order);
        }
        $data['page'] = $page;
        $data['total'] = $rdata['total_rows'];
        $rows = array();
        foreach ($rdata['data'] as $rowdata) {
            $rows[] = array('id' => $rowdata[$id], 'cell' => $rowdata);
        }
        $data['rows'] = $rows;
        return $data;
    }
    
    public function checkSecurity($page) {
        $permObj = new \simbola\core\component\auth\lib\PermObject(
                $this->get('module'), 
                $this->get('lu'), 
                $this->get('view'), 'view');
        return parent::checkSecurity($page) && \simbola\Simbola::app()->auth->checkPermission($permObj);
    }

    public function actionData() {
        if($this->issetGet(array('module', 'lu', 'view', 'id'))){ 
            $direct = $this->issetGet(array('direct')) ? $this->get('direct') == 'true' : false;
            $filter = $this->issetGet(array('filter')) ? $this->get("filter") == '_FILTER_' : null;
            $data = $this->data($this->get("module"), $this->get('lu'), $this->get('view'), $this->get("id"), $filter, $direct);
            $this->json($data);
        }
    }

}