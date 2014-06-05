<?php
$this->page_breadcrumb = array(
    'System' => array('/system'),
    'Transaction' => array('/system/transaction'),
    'Cron');

$grid = new application\system\library\simgrid\WidgetSimGrid("system_transaction_cron_list");
$grid->setTitle("Cron Jobs");
$grid->setTableCss('table-condensed table-hover');
$grid->setDataSource("system", "transaction", "cron");
$grid->setColumns(array(
    	"id" => "Cron ID",
        "_state" => "Status",
	"last_execute" => "Last Executed",
        "interval" => "Interval (sec)",
	"execute_count" => "Execute Count",
	"job_count" => "Job Count",    
        "Actions"
));
$grid->setActions(array(
    shtml_action_link("open", array('system/transaction/cronView[id:%id%]'), array('class' => 'btn btn-default btn-xs')),
));
echo $grid->getDisplayData();
?>