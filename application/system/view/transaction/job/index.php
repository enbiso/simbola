<?php
$this->page_breadcrumb = array(
    'System' => array('/system'),
    'Transaction' => array('/system/transaction'),
    'Job');    

$this->page_menu = array(     
    array(
        'title' => sterm_get('system.transaction.job.index.menu.create'),
        'link' => array('/system/transaction/jobCreate'),
        'icon' => 'plus'
    ),   
);

$grid = new application\system\library\simgrid\WidgetSimGrid("system_transactionJob_list");
$grid->setTitle(sterm_get('system.transaction.job.index.table.title'));
$grid->setTableCss('table-condensed table-hover');
$grid->setDataSource("system", "transaction", "job");
$grid->setOrderBy("id desc");
$grid->setColumns(array(
    	"id" => "ID",
        "_state" => "Status",
	"priority" => "Priority",
	"type" => "Type",
	"queue_id" => "Queue",		
	"user.user_name" => "User",
    "Actions",
));
$grid->setActions(array(
    shtml_action_link("open", array('system/transaction/jobView[id:%id%]'), array('class' => 'btn btn-default btn-xs')),
));

echo $grid->getDisplayData();
?>