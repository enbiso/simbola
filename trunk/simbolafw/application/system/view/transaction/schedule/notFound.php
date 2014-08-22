<?php
$this->page_breadcrumb = array(
    'System' => array('/system'),
    'Transaction' => array('/system/transaction'),
    'Schedule' => array('/system/transaction/schedule'),    
    sterm_get('system.transaction.schedule.notFound.title'));

$this->page_menu = array(    
    array(
        'title' => sterm_get('system.transaction.schedule.notFound.menu.create'),
        'link' => array('/system/transaction/scheduleCreate'),
        'icon' => 'plus'
    ),   
    array(
        'title' => sterm_get('system.transaction.schedule.notFound.menu.list'),
        'link' => array('/system/transaction/scheduleList'),
        'icon' => 'list'
    ),
);
?>
<div class="panel panel-warning">
    <div class="panel-body">
        <?= sterm_get('system.transaction.schedule.notFound.description')  ?>
    </div>
</div>