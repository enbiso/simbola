<?php
$this->page_breadcrumb = array(
    'System' => array('/system'),
    'Transaction' => array('/system/transaction'),
    'Job' => array('/system/transaction/job'),    
    sterm_get('system.transaction.job.notFound.title'));

$this->page_menu = array(    
    array(
        'title' => sterm_get('system.transaction.job.notFound.menu.create'),
        'link' => array('/system/transaction/jobCreate'),
        'icon' => 'plus'
    ),   
    array(
        'title' => sterm_get('system.transaction.job.notFound.menu.list'),
        'link' => array('/system/transaction/jobList'),
        'icon' => 'list'
    ),
);
?>
<div class="panel panel-warning">
    <div class="panel-body">
        <?= sterm_get('system.transaction.job.notFound.description')  ?>
    </div>
</div>