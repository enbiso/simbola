<?php
$this->page_breadcrumb = array(
    'System' => array('/system'),
    'Transaction' => array('/system/transaction'),
    'Schedule' => array('/system/transaction/schedule'),    
    sterm_get('system.transaction.schedule.index.title'));

$this->page_menu = array(     
    array(
        'title' => sterm_get('system.transaction.schedule.index.menu.create'),
        'link' => array('/system/transaction/scheduleCreate'),
        'icon' => 'plus'
    ),   
);

$grid = new application\system\library\simgrid\WidgetSimGrid("system_transactionSchedule_list");
$grid->setTitle(sterm_get('system.transaction.schedule.index.table.title'));
$grid->setTableCss('table-condensed table-hover');
$grid->setDataSource("system", "transaction", "schedule");
$grid->setColumns(array(
    	"id" => "ID",
	"user.user_name" => "User",
        "_state" => "Status",
        "_created" => "Created",
	"interval" => "Interval",
        'execute_count' => 'Executions',
	"type" => "Type",
	"queue_id" => "Queue",	
    "Actions",
));
$grid->setActions(array(
    shtml_action_link("open", array('system/transaction/scheduleView[id:%id%]'), array('class' => 'btn btn-default btn-xs')),
));

echo $grid->getDisplayData();
?>