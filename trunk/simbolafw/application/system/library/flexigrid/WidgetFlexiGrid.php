<?php
namespace application\system\library\flexigrid;
/**
 * Description of WidgetFlexiGrid
 * @property string $url
 * @property string $dataType
 * @property array $colModel 
 * @property array $buttons
 * @property array $searchitems 
 * @property string $sortname
 * @property string $sortorder
 * @property bool $usepager
 * @property string $title
 * @property bool $useRp
 * @property integer $rp
 * @property bool $showTableToggleBtn
 * @property integer $width
 * @property integer $height
 * @property string[] $filter
 *  
 * @author Faraj
 */
class WidgetFlexiGrid {
    private $data = array();
    private $name;
    private $module;
    private $lu;
    private $view;
    private $master;       
    private $direct = false;  
    
    public function __construct($name) {
        $this->name = $name;
        $this->data['dataType'] = 'json';        
    }

    public function setMaster($master = null) {
        $this->master = $master;
    }
    
    public function getMaster() {
        return $this->master;    
    }
    
    public function setDirect($direct){
        $this->direct = $direct;
    }
    
    public function getDirect() {
        return $this->direct;                
    }
    
    public function setDataSource($module, $lu, $view, $id, $filter = '_FILTER_') {
        $this->module = $module;
        $this->lu = $lu;
        $this->view = $view;         
        $direct = $this->direct?'true':'false';        
        $page = new \simbola\core\component\url\lib\Page();
        $page->loadFromArray( array('system/flexigrid/data' , 
                'module'=>$module,'lu'=>$lu,'view'=>$view,'id'=>$id,'filter'=>$filter,'direct'=>$direct));
        $this->url = $page->getUrl();
    }
    
    public function __set($name, $value) {
        $this->data[$name] = $value;
    }
    
    public function __get($name) {
        return $this->data[$name];
    }
    
    public function addColModel($display,$name,$width,$sortable,$align) {
        $this->data['colModel'][] = array(
            'display'   => $display,
            'name'      => $name,
            'width'     => $width,
            'sortable'  => $sortable,
            'align'     => $align,
        );
    }
    
    public function addButton($name,$onPress) {
        $this->data['buttons'][] = array(
            'name'      => $name,            
            'onpress'   => $onPress,
        );
    }
    
    public function addSearchItem($display,$name,$isDefault = false){
        $this->data['searchitems'][] = array(
            'display'   => $display,
            'name'      => $name,
            'isdefault' => $isDefault,
        );
    }
    
    public function getData() {
        return $this->data;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getDisplayData() {
        $jsondata = json_encode($this->getData());
        $jsondata = str_ireplace("~\"", "", $jsondata);
        $jsondata = str_ireplace("\"~", "", $jsondata);
        $data  = "<table id='{$this->getName()}'/>";
        $data .= "<script type='text/javascript'>";
        $data .= "$(document).ready(function(){";
        $data .= "$('#{$this->getName()}').flexigrid(" . $jsondata . ");";
        $data .= "});\n";
        $data .= "</script>";
        if ($this->getMaster()) {
            $data .= "<script type='text/javascript'>\n";
            $data .= "$('#{$this->getMaster()}').bind('simbolaformload',function(e,data){\n";
            $data .= "   var newurl = \"{$this->url}\";\n";
            $data .= "   var keyStringArr = Array();\n";
            $data .= "   $.each(data, function(field,value){\n";
            $data .= "      keyStringArr.push(field+'='+value);\n";
            $data .= "   });\n";
            $data .= "   var keystring = keyStringArr.join(' AND ');\n";
            $data .= "   newurl = newurl.replace('_FILTER_',keystring);\n";
            $data .= "   $('#{$this->getName()}').flexOptions({url:newurl});\n";
            $data .= "   $('#{$this->getName()}').flexReload();\n";
            $data .= "});\n";
            $data .= "</script>";
        }
        return $data;
    }
}
?>
