<?php
$this->page_header = "Contact";
$this->page_subheader = sterm_get('web.contact.list.title');;

$this->page_breadcrumb = array(
    'Web' => array('/web'),
    'Contact' => array('/web/contact'),    
    sterm_get('web.contact.list.title'));

$this->page_menu = array(     
  
);

$grid = new application\system\library\simgrid\WidgetSimGrid("web_contact_list");
$grid->setTitle(sterm_get('web.contact.list.table.title'));
$grid->setTableCss('table-condensed table-hover');
$grid->setDataSource("web", "contact", "message");
$grid->setColumns(array(
    	"id" => "id",
	"title" => "title",
	"message" => "message",
	"email" => "email",
	"is_read" => "is_read",
    "Actions",
));
$grid->setActions(array(
    shtml_action_link("open", array('web/contact/view[id:%id%]'), array('class' => 'btn btn-default btn-xs')),
));

echo $grid->getDisplayData();
?>