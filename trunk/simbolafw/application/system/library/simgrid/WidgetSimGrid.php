<?php

namespace application\system\library\simgrid;

/**
 * Description of WidgetSimGrid
 *
 * @author Faraj
 */
class WidgetSimGrid {

    /**
     * Array of table CSS class
     * @var array 
     */
    private $tableClass = array('table simGrid-Table');

    /**
     * Array of columns
     * @var array 
     */
    private $columns = array();

    /**
     * Data source of grid table
     * @var array 
     */
    private $dataSource = array('module' => '', 'lu' => '', 'name' => '');

    /**
     * Grid ID
     * @var string
     */
    private $id;

    /**
     * Dynamic table actions
     * @var array 
     */
    private $actions = array();

    /**
     * Table Actions
     * @var array
     */
    private $tableActions = array();

    /**
     * Grid title
     * @var string
     */
    private $title;

    /**
     * Filter condition
     * @var array 
     */
    private $condition = array();

    /**
     * Page Count
     * @var integer 
     */
    private $pageLength = 20;

    /**
     * Create a grid with the grid ID
     * 
     * @param type $id Grid ID
     */
    public function __construct($id) {
        $this->id = $id;
        $this->tableActions = $this->generateDefaultTableActions();        
    }

    /**
     * Set the defautl seach conditions
     * 
     * @param array $condition PHP AR condition array
     */
    public function setCondition($condition) {
        $this->condition = $condition;
    }

    /**
     * Set grid title
     * 
     * @param string $title Title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * Set dynamic actions that will be parsed by the server
     * 
     * @param array $actions Action array
     */
    public function setActions($actions) {
        $this->actions = array_merge($actions, $this->actions);
    }

    /**
     * Set the static table actions
     * 
     * @param array $actions Action array
     */
    public function setTableActions($actions) {
        $this->tableActions = array_merge($actions, $this->tableActions);
    }

    /**
     * Set table CSS Classes
     * 
     * @param string $class Class names
     */
    public function setTableCss($class) {
        if (is_string($class)) {
            $class = explode(" ", $class);
        }
        $this->tableClass = array_merge($this->tableClass, $class);
    }

    /**
     * Set grid columns
     * 
     * @param array $cols Columns
     */
    public function setColumns($cols) {
        $this->columns = $cols;
    }

    /**
     * Set hidden grid columns
     * 
     * @param array $cols Columns
     */
    public function setHiddenColumns($cols) {
        foreach ($cols as $hiddenCol) {
            $this->columns[$hiddenCol] = NULL;
        }
    }

    /**
     * Set grid data source
     * 
     * @param string $module Module
     * @param string $lu Logical unit
     * @param string $name Model name
     */
    public function setDataSource($module, $lu, $name) {
        $this->dataSource['module'] = $module;
        $this->dataSource['lu'] = $lu;
        $this->dataSource['name'] = $name;
    }

    /**
     * Used to set  the page length
     * 
     * @param integer $count Page length
     */
    public function setPageLength($length) {
        $this->pageLength = $length;
    }

    /**
     * Returns the HTML content
     * 
     * @return string HTML tags
     */
    public function getDisplayData() {
        $content = shtml_tag("div", array('class' => "panel panel-default", 'id' => $this->id));
        $content .= shtml_tag("div", array('class' => "panel-heading"));
        $content .= $this->generateTitle();
        $content .= shtml_untag("div"); //panel heading
        $content .= shtml_tag("div", array('class' => "panel-body"));
        $content .= shtml_tag("table", array(
            'class' => implode(" ", $this->tableClass)));
        $content .= $this->generateTableHeader();
        $content .= shtml_tag("tbody");
        $content .= shtml_untag("tbody");
        $content .= shtml_untag("table");
        $content .= shtml_untag("div"); //panel body
        $content .= shtml_tag("div", array('class' => "panel-footer"));
        $content .= shtml_tag("div", array('class' => 'row'));
        $content .= "<div class='col-md-2'>";
        $content .= $this->generatePager();
        $content .= "</div>";
        $content .= "<div class='col-md-4'>";
        $content .= $this->generateTableActions();
        $content .= "</div>";
        $content .= $this->generateScript();
        $content .= shtml_untag("div");
        $content .= shtml_untag("div"); //panel footer
        $content .= shtml_untag("div"); //panel
        return $content;
    }

    /**
     * Generate Table actions
     * 
     * @return string HTML tags
     */
    private function generateTableActions() {
        $content = '';
        if (count($this->tableActions) > 0) {
            $content .= shtml_tag('div', array('btn-group'));
            foreach ($this->tableActions as $tAction) {
                $content .= $tAction;
            }
            $content .= shtml_untag('div');
        }
        return $content;
    }

    /**
     * Generate dirty JS scripts
     * 
     * @return string HTML JS Script
     */
    private function generateScript() {                        
        ob_start();
        ?>
        <script>
            $('#<?= $this->id ?>').simGrid({
                source         :<?= json_encode($this->dataSource) ?>,
                columns        :<?= json_encode(array_keys($this->columns)) ?>,
                conditions     :<?= json_encode($this->condition) ?>,
                actions        :<?= json_encode($this->actions) ?>,
                hiddenColumns  :<?= json_encode(array_keys($this->columns, NULL)) ?>,
                pageLength     :<?= $this->pageLength ?>
            });    
        </script>
        <?php

        return ob_get_clean();
    }

    /**
     * Generate Title
     * 
     * @return string HTML
     */
    private function generateTitle() {
        $content = $this->title;
        return $content;
    }

    /**
     * Generate default table actions
     * 
     * @return string HTML tag     
     */
    private function generateDefaultTableActions() {
        $actions = array();
        $actions[] = shtml_link("", false, array('class'=>'simGrid-Reload btn btn-default btn-sm'), 'refresh');
        return $actions;
    }
    
    /**
     * Generate pager
     * 
     * @return string HTML
     */
    private function generatePager() {        
        ob_start();
        ?>
        <div class="input-group input-group-sm simGrid-Pager">
            <span class="input-group-btn">
                <button class="simGrid-Pager-Prev btn btn-default btn-sm">Prev</button>
            </span>
            <select class="form-control input-sm simGrid-Pager-Counter">
                
            </select>
            <span class="input-group-btn">
                <button class="simGrid-Pager-Next btn btn-default btn-sm">Next</button>
            </span>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Generate header
     * 
     * @return string HTML
     */
    private function generateTableHeader() {
        $content = shtml_tag("thead");
        $content .= shtml_tag("tr");
        foreach ($this->columns as $ckey => $ctitle) {
            if (!is_null($ctitle)) {
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
