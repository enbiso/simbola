<?php
$this->page_breadcrumb = array(
    'System' => array('/system'),
    'Transaction' => array('/system/transaction'),
    'Queue' => array('/system/transaction/queue'),    
    sterm_get('system.transaction.queue.notFound.title'));

$this->page_menu = array(    
    array(
        'title' => sterm_get('system.transaction.queue.notFound.menu.create'),
        'link' => array('/system/transaction/queueCreate'),
        'icon' => 'plus'
    ),   
    array(
        'title' => sterm_get('system.transaction.queue.notFound.menu.list'),
        'link' => array('/system/transaction/queueList'),
        'icon' => 'list'
    ),
);
?>
<div class="panel panel-warning">
    <div class="panel-body">
        <?= sterm_get('system.transaction.queue.notFound.description')  ?>
    </div>
</div>