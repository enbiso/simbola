<?php
$this->page_breadcrumb = array(
    'System' => array('/system'),
    'Transaction' => array('/system/transaction'),
    'Queue');

$this->page_menu = array(     
    array(
        'title' => sterm_get('system.transaction.queue.index.menu.create'),
        'link' => array('/system/transaction/queueCreate'),
        'icon' => 'plus'
    ),   
);

$grid = new application\system\library\simgrid\WidgetSimGrid("system_transactionQueue_list");
$grid->setTitle(sterm_get('system.transaction.queue.index.table.title'));
$grid->setTableCss('table-condensed table-hover');
$grid->setDataSource("system", "transaction", "queue");
$grid->setColumns(array(
    	"id" => "Queue ID",
	"description" => "Description",
    "Actions",
));
$grid->setActions(array(
    shtml_action_link("open", array('system/transaction/queueView[id:%id%]'), array('class' => 'btn btn-default btn-xs')),
));

echo $grid->getDisplayData();
?>