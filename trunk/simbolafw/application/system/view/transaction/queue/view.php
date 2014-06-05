<?php
$this->page_breadcrumb = array(
    'System' => array('/system'),
    'Transaction' => array('/system/transaction'),
    'Queue' => array('/system/transaction/queue'),    
    sterm_get('system.transaction.queue.view.title'));

$object = $this->object;
$this->page_menu = array(    
    array(
        'title' => sterm_get('system.transaction.queue.view.menu.create'),
        'link' => array('/system/transaction/queueCreate'),
        'icon' => 'plus'
    ),   
    array(
        'title' => sterm_get('system.transaction.queue.view.menu.update'),
        'link' => array('/system/transaction/queueUpdate',"id" => $object->id),
        'icon' => 'edit'
    ),
    array(
        'title' => sterm_get('system.transaction.queue.view.menu.delete'),
        'link' => array('/system/transaction/queueDelete',"id" => $object->id),
        'icon' => 'remove'
    ),    
);
?>
<div class="panel panel-default">
    <div class="panel-heading"><?= sterm_get('system.transaction.queue.view.panel.heading') ?></div>
    <div class="panel-body">
        <?php $this->pview('transaction/queue/_display'); ?>
    </div>
</div>