<?php
$this->page_header = "Content";
$this->page_subheader = sterm_get('web.content.index.title');

$this->page_breadcrumb = array(
    'Web' => array('/web'),    
    'Content');

$this->page_menu = array(     
    array(
        'title' => sterm_get('web.content.index.menu.create'),
        'link' => array('/web/content/create'),
        'icon' => 'plus'
    ),
    array(
        'title' => sterm_get('web.content.index.menu.list'),
        'link' => array('/web/content/list'),
        'icon' => 'list'
    ),
);
?>
