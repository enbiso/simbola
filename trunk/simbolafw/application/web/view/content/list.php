<?php
$this->page_header = "Content";
$this->page_subheader = sterm_get('web.content.list.title');;

$this->page_breadcrumb = array(
    'Web' => array('/web'),
    'Content' => array('/web/content'),    
    sterm_get('web.content.list.title'));

$this->page_menu = array(     
    array(
        'title' => sterm_get('web.content.list.menu.create'),
        'link' => array('/web/content/create'),
        'icon' => 'plus'
    ),   
);

$grid = new application\system\library\simgrid\WidgetSimGrid("web_content_list");
$grid->setTitle(sterm_get('web.content.list.table.title'));
$grid->setTableCss('table-condensed table-hover');
$grid->setDataSource("web", "core", "content");
$grid->setColumns(array(
    	"id" => "id",
	"description" => "description",
	"content" => "content",
    "Actions",
));
$grid->setActions(array(
    shtml_action_link("open", array('web/content/view[id:%id%]'), array('class' => 'btn btn-default btn-xs')),
));

echo $grid->getDisplayData();
?>