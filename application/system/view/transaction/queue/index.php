<?php
$this->page_breadcrumb = array(
    'System' => array('/system'),    
    'Transaction' => array('/system/transaction'),    
    'Queue');

$this->page_menu = array(     
    array(
        'title' => sterm_get('system.transaction.queue.index.menu.create'),
        'link' => array('/system/transaction/queueCreate'),
        'icon' => 'plus'
    ),
    array(
        'title' => sterm_get('system.transaction.queue.index.menu.list'),
        'link' => array('/system/transaction/queueList'),
        'icon' => 'list'
    ),
);
?>
<div class="jumbotron">
    <h1>Transaction Queue <small><?= sterm_get('system.transaction.queue.index.title') ?></small></h1>
</div>
