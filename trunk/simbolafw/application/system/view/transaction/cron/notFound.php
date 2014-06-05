<?php
$this->page_breadcrumb = array(
    'System' => array('/system'),
    'Transaction' => array('/system/transaction'),
    'Cron' => array('/system/transaction/cron'),    
    sterm_get('system.transaction.cron.notFound.title'));

$this->page_menu = array(    
    array(
        'title' => sterm_get('system.transaction.cron.notFound.menu.create'),
        'link' => array('/system/transaction.cron/create'),
        'icon' => 'plus'
    ),   
    array(
        'title' => sterm_get('system.transaction.cron.notFound.menu.list'),
        'link' => array('/system/transaction.cron/list'),
        'icon' => 'list'
    ),
);
?>
<div class="panel panel-warning">
    <div class="panel-body">
        <?= sterm_get('system.transaction.cron.notFound.description')  ?>
    </div>
</div>