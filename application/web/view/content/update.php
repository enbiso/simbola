<?php
$this->page_header = "Content";
$this->page_subheader = sterm_get('web.content.update.title');

$this->page_breadcrumb = array(
    'Web' => array('/web'),
    'Content' => array('/web/content'),    
    sterm_get('web.content.update.title'));

$object = $this->object;
$this->page_menu = array(    
    array(
        'title' => sterm_get('web.content.update.menu.list'),
        'link' => array('/web/content/list'),
        'icon' => 'list'
    ),
    array(
        'title' => sterm_get('web.content.update.menu.view'),
        'link' => array('/web/content/view',"id" => $object->id),
        'icon' => 'file'
    ),
    array(
        'title' => sterm_get('web.content.update.menu.delete'),
        'link' => array('/web/content/delete',"id" => $object->id),
        'icon' => 'remove'
    ),    
);
?>
<?php if($this->isDataSet('error')): ?>
    <div class="alert alert-warning"><?= $this->error ?></div>
<?php endif; ?>
<?php $this->pview('content/_form'); ?>