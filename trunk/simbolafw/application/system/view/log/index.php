<?php
$this->page_breadcrumb = array(
    'System' => array('/system'),
    'Log');

$grid = new application\system\library\simgrid\WidgetSimGrid("system_log_list");
$grid->setTitle("logs");
$grid->setTableCss('table-condensed table-hover');
$grid->setDataSource("system", "logger", "log");
$grid->setOrderBy("_created desc");
$grid->setColumns(array(
    	"_created" => "Date",
	"type" => "Type",
	"trace" => "Trace",
	"message" => "Message",    
));
echo $grid->getDisplayData();
?>