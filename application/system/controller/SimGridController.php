<?php

namespace application\system\controller;

/**
 * Description of SimGridController
 *
 * @author Faraj
 */
class SimGridController extends \simbola\core\application\AppController {

    public function checkSecurity($page) {
        if($this->issetPost("source")){
            $source = (object)$this->post("source");
            $permObj = new \simbola\core\component\auth\lib\PermObject(
                    $source->module,
                    $source->lu,
                    $source->name, 'entity.query');        
            return parent::checkSecurity($page) 
                    && \simbola\Simbola::app()->auth->checkPermission($permObj);;
        }else{
            return parent::checkSecurity($page);
        }
    }
    
    function actionData() {
        if ($this->issetPost(array('source', 'columns'))) {
            $source = $this->post("source");            
            $modelns = \simbola\Simbola::app()->getModuleNameSpace($source['module'], "model");
            $class = $modelns . "\\" . $source["lu"] . "\\" . ucfirst($source["name"]);

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
            
            if ($this->issetPost("searchConditions")){
                $searchConditions = $this->post('searchConditions');
                if(isset($queryOptions['conditions']) && count($queryOptions['conditions']) > 0){
                    $queryOptions['conditions'][0] .= " AND " . $searchConditions[0];
                    array_push($queryOptions['conditions'], array_slice($searchConditions,1));
                }else{
                    $queryOptions['conditions'] = $searchConditions;
                }
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
                         if($value instanceof \ActiveRecord\DateTime){
                             $value = $value->format('Y-m-d H:i:s P');
                         }
                         $row[$column] = $value;                    
                     }
                }                
                $rows[] = $row;
                $columns = array_keys($row);
            }
            $this->setViewData('query', $queryOptions);
            $this->setViewData('count', $class::count());
            $this->setViewData('columns', $columns);
            $this->setViewData('rows', $rows);
            $this->json();
        } else {
            $this->view("simGrid/data");
        }
    }
    
}

?>
