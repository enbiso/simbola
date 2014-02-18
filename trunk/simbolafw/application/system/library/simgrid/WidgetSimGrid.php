<?php
namespace application\system\library\simgrid;
/**
 * Description of WidgetSimGrid
 *
 * @author Faraj
 */
class WidgetSimGrid {
    
    private $tableClass = array('table');
    private $columns = array();
    private $dataSource = array('module' => '', 'lu' => '', 'name' => '');
    private $id;
    private $actions = array();
    private $tableActions = array();
    private $title;
    private $condition = array();
    
    public function __construct($id) {
        $this->id = $id;
    }
    
    public function setCondition($condition) {
        $this->condition = $condition;
    }
    
    public function setTitle($title) {
        $this->title = $title;
    }
    
    public function setActions($actions) {
        $this->actions = $actions;
    }
    
    public function setTableActions($actions) {
        $this->tableActions = $actions;
    }
    
    public function setTableCss($class) {
        if(is_string($class)){
            $class = explode(" ", $class);
        }
        $this->tableClass = array_merge($this->tableClass,$class);
    }
    
    public function setColumns($cols) {
        $this->columns = $cols;
    }
    
    public function setHiddenColumns($cols) {
        foreach ($cols as $hiddenCol) {
            $this->columns[$hiddenCol] = NULL;
        }
    }
    
    public function setDataSource($module, $lu, $name) {
        $this->dataSource['module'] = $module;
        $this->dataSource['lu'] = $lu;
        $this->dataSource['name'] = $name;
    }
    
    public function getDisplayData() {
        $content = shtml_tag("div", array('class'=>"panel panel-default"));        
        $content .= shtml_tag("div", array('class'=>"panel-heading"));
        $content .= $this->generateTitle();
        $content .= shtml_untag("div");//panel heading
        $content .= shtml_tag("div", array('class'=>"panel-body"));   
        $content .= shtml_tag("table", array(
            'id'    => $this->id,
            'class' => implode(" ", $this->tableClass)));
        $content .= $this->generateTableHeader();
        $content .= shtml_tag("tbody");
        $content .= shtml_untag("tbody");
        $content .= shtml_untag("table");
        $content .= shtml_untag("div");//panel body
        $content .= shtml_tag("div", array('class'=>"panel-footer"));
        $content .= shtml_tag("div", array('class'=>'row'));
            $content .= "<div class='col-md-2'>";
            $content .= $this->generatePager();
            $content .= "</div>";
            $content .= "<div class='col-md-4'>";
            $content .= $this->generateTableActions();
            $content .= "</div>";
        $content .= shtml_untag("div");
        $content .= shtml_untag("div");//panel footer
        $content .= shtml_untag("div");//panel
        $content .= $this->generateScript();
        return $content;
    }
    
    
    private function generateTableActions() {        
        $content = '';
        if(count($this->tableActions) > 0){
            $content .= shtml_tag('div', array('btn-group'));
            foreach ($this->tableActions as $tAction) {
                $content .= $tAction;
            }
            $content .= shtml_untag('div');
        }                
        return $content;
    }
    
    private function generateScript() {
        $script = shtml_tag('script');
        $colString = json_encode(array_keys($this->columns));
        $actionString = json_encode($this->actions);
        $condString = json_encode($this->condition);
        $hiddenColString = json_encode(array_keys($this->columns,NULL));
        $script .= "$('#{$this->id}').simGrid('{$this->dataSource['module']}',
                                             '{$this->dataSource['lu']}',
                                             '{$this->dataSource['name']}',
                                             {$colString},{$hiddenColString},{$actionString},{$condString});";    
        $script .= shtml_untag("script");
        return $script;
    }
    
    private function generateTitle() {        
        $content = $this->title;        
        return $content;
    }
    
    private function generatePager() {
        $pagerid = $this->id."_pager";
        $content = shtml_tag("div", array('class'=>'input-group input-group-sm','id'=>$pagerid));
        
        $content .= shtml_tag("span", array('class'=>'input-group-btn'));        
        $content .= shtmlform_button("Prev", array('class'=>'btn btn-default btn-sm'));        
        $content .= shtml_untag("span");
        
        $content .= shtml_tag("select", array('class'=>'form-control input-sm'));    
        $content .= shtml_untag("select");
        
        $content .= shtml_tag("span", array('class'=>'input-group-btn'));
        $content .= shtmlform_button("Next", array('class'=>'btn btn-default btn-sm'));        
        $content .= shtml_untag("span");
        
        $content .= shtml_untag("div");
        return $content;
    }
    
    private function generateTableHeader() {
        $content = shtml_tag("thead");
        $content .= shtml_tag("tr");
        foreach ($this->columns as $ckey => $ctitle) {
            if(!is_null($ctitle)){
                $content .= shtml_tag("th");
                $content .= shtml_tag("strong");
                $content .= $ctitle;
                $content .= shtml_untag("strong");
                $content .= shtml_untag("th");
            }
        }
        $content .= shtml_untag("tr");
        $content .= shtml_untag("thead");
        return $content;
    }
}

?>
