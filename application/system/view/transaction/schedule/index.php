<?php
$this->page_breadcrumb = array(
    'System' => array('/system'),   
    'Transaction' => array('/system/transaction'),
    'Schedule');

$this->page_menu = array(     
    array(
        'title' => sterm_get('system.transaction.schedule.index.menu.create'),
        'link' => array('/system/transaction/scheduleCreate'),
        'icon' => 'plus'
    ),
    array(
        'title' => sterm_get('system.transaction.schedule.index.menu.list'),
        'link' => array('/system/transaction/scheduleList'),
        'icon' => 'list'
    ),
);
?>
<div class="jumbotron">
    <h1>Transaction Schedule <small><?= sterm_get('system.transaction.schedule.index.title') ?></small></h1>
</div>
