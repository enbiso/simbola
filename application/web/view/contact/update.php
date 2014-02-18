<?php
$this->page_header = "Contact";
$this->page_subheader = sterm_get('web.contact.update.title');

$this->page_breadcrumb = array(
    'Web' => array('/web'),
    'Contact' => array('/web/contact'),    
    sterm_get('web.contact.update.title'));

$object = $this->object;
$this->page_menu = array(    
    array(
        'title' => sterm_get('web.contact.update.menu.list'),
        'link' => array('/web/contact/list'),
        'icon' => 'list'
    ),
    array(
        'title' => sterm_get('web.contact.update.menu.view'),
        'link' => array('/web/contact/view',"id" => $object->id),
        'icon' => 'file'
    ),
    array(
        'title' => sterm_get('web.contact.update.menu.delete'),
        'link' => array('/web/contact/delete',"id" => $object->id),
        'icon' => 'remove'
    ),    
);
?>
<?php if($this->isDataSet('error')): ?>
    <div class="alert alert-warning"><?= $this->error ?></div>
<?php endif; ?>
<?php $this->pview('contact/_form'); ?>