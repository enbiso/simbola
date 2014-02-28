<?php

$grid = new application\system\library\flexigrid\WidgetFlexiGrid("sys_log");
$grid->title = "Log";
$grid->usepager = true;

$grid->setDirect(true);
$grid->setDataSource('system', 'logger', 'log', 'date');
$grid->addColModel("Date", "date", "130", true, 'left');
$grid->addColModel("Type", "type", "40", true, 'left');
$grid->addColModel("Trace", "trace", "100", false, 'left');
$grid->addColModel("Message", "message", "1000", true, 'left');

$grid->addSearchItem("Date", 'date', false);
$grid->addSearchItem("Type", 'type', true);
$grid->addSearchItem("Message", 'message', false);

echo $grid->getDisplayData();
?>