<?php

namespace application\system\controller;

/**
 * Description of SimGridController
 *
 * @author Faraj
 */
class SimGridController extends \simbola\core\application\AppController {

    public function checkSecurity($page) {
        $permObj = new \simbola\core\component\auth\lib\PermObject(
                $this->post('module'),
                $this->post('lu'),
                $this->post('name'), 'table');        
        return parent::checkSecurity($page) 
                && \simbola\Simbola::app()->auth->checkPermission($permObj);;
    }
    
    function actionData() {
        if ($this->issetPost(array('module', 'lu', 'name', 'columns'))) {

            $modelns = \simbola\Simbola::app()->getModuleNameSpace($this->post("module"), "model");
            $class = $modelns . "\\" . $this->post("lu") . "\\" . ucfirst($this->post("name"));

            $queryOptions['limit'] = $this->issetPost("limit") ? $this->post("limit") : 100;
            $queryOptions['offset'] = $this->issetPost("offset") ? $this->post("offset") : 0;

            if ($this->issetPost("order")) {
                $queryOptions['order'] = $this->post("order");
            }

            if ($this->issetPost("group")) {
                $queryOptions['group'] = $this->post("group");
            }

            if ($this->issetPost("conditions")) {
                $queryOptions['conditions'] = $this->post("conditions");
            }
            
            $rows = array();            
            $objects = $class::find('all', $queryOptions);
            $columns = array();
            foreach ($objects as $object) {         
                $row = array();
                slog_trace(var_export($this->post('columns'), true));
                foreach ($this->post('columns') as $column) {                    
                     if(!is_numeric($column)){
                         $value = $object;                         
                         foreach (explode(".", $column) as $property) {
                             $value = $value->$property;
                         }
                         $row[$column] = $value;                    
                     }
                }                
                $rows[] = $row;
                $columns = array_keys($row);
            }
            $this->setViewData('query', $queryOptions);
            $this->setViewData('rows', $rows);
            $this->setViewData('columns', $columns);
            $this->json();
        }
    }

}

?>
