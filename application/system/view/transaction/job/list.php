<?php
$this->page_breadcrumb = array(
    'System' => array('/system'),
    'Transaction' => array('/system/transaction'),
    'Job' => array('/system/transaction/job'),    
    sterm_get('system.transaction.job.list.title'));

$this->page_menu = array(     
    array(
        'title' => sterm_get('system.transaction.job.list.menu.create'),
        'link' => array('/system/transaction/jobCreate'),
        'icon' => 'plus'
    ),   
);

$grid = new application\system\library\simgrid\WidgetSimGrid("system_transactionJob_list");
$grid->setTitle(sterm_get('system.transaction.job.list.table.title'));
$grid->setTableCss('table-condensed table-hover');
$grid->setDataSource("system", "transaction", "job");
$grid->setColumns(array(
    	"id" => "id",
	"priority" => "priority",
	"type" => "type",
	"queue_id" => "queue_id",
	"content" => "content",
	"output" => "output",
	"user_id" => "user_id",
    "Actions",
));
$grid->setActions(array(
    shtml_action_link("open", array('system/transaction/jobView[id:%id%]'), array('class' => 'btn btn-default btn-xs')),
));

echo $grid->getDisplayData();
?>