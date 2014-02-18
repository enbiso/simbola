<?php
$this->page_header = "Content";
$this->page_subheader = sterm_get('web.content.create.title');

$this->page_breadcrumb = array(
    'Web' => array('/web'),
    'Content' => array('/web/content'),    
    sterm_get('web.content.create.title'));

$this->page_menu = array(
    array(
        'title' => sterm_get('web.content.create.menu.list'),
        'link' => array('/web/content/list'),
        'icon' => 'list'
    ),
);
?>
<?php if($this->isDataSet('error')): ?>
    <div class="alert alert-warning"><?= $this->error ?></div>
<?php endif; ?>
<?php $this->pview('content/_form'); ?>