<?php
$this->page_breadcrumb = array(
    'System' => array('/system'),    
    'Transaction' => array('/system/transaction'),    
    'Job');

$this->page_menu = array(     
    array(
        'title' => sterm_get('system.transaction.job.index.menu.create'),
        'link' => array('/system/transaction/jobCreate'),
        'icon' => 'plus'
    ),
    array(
        'title' => sterm_get('system.transaction.job.index.menu.list'),
        'link' => array('/system/transaction/jobList'),
        'icon' => 'list'
    ),
);
?>
<div class="jumbotron">
    <h1>Transaction Job <small><?= sterm_get('system.transaction.job.index.title') ?></small></h1>
</div>
