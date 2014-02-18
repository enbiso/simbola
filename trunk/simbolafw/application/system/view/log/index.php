<?php

$grid = new application\system\library\flexigrid\WidgetFlexiGrid("sys_log");
$grid->title = "Log";
$grid->usepager = true;

$grid->setDirect(true);
$grid->setDataSource('system', 'logger', 'log', '_date');
$grid->addColModel("Date", "_date", "130", true, 'left');
$grid->addColModel("Type", "_type", "100", true, 'left');
$grid->addColModel("Message", "_message", "300", true, 'left');

$grid->addSearchItem("Date", '_date', false);
$grid->addSearchItem("Type", '_type', true);
$grid->addSearchItem("Message", '_message', false);

echo $grid->getDisplayData();
?>