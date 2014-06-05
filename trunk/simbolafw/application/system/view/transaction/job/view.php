<?php
$this->page_breadcrumb = array(
    'System' => array('/system'),
    'Transaction' => array('/system/transaction'),
    'Job' => array('/system/transaction/job'),       
    sterm_get('system.transaction.job.view.title'));

$object = $this->object;
$this->page_menu = array(    
    array(
        'title' => sterm_get('system.transaction.job.view.menu.create'),
        'link' => array('/system/transaction/jobCreate'),
        'icon' => 'plus'
    ),   
    array(
        'title' => sterm_get('system.transaction.job.view.menu.update'),
        'link' => array('/system/transaction/jobUpdate',"id" => $object->id),
        'icon' => 'edit'
    ),
    array(
        'title' => sterm_get('system.transaction.job.view.menu.delete'),
        'link' => array('/system/transaction/jobDelete',"id" => $object->id),
        'icon' => 'remove'
    ),    
);
?>
<div class="panel panel-default">
    <div class="panel-heading"><?= sterm_get('system.transaction.job.view.panel.heading') ?></div>
    <div class="panel-body">
        <?php $this->pview('transaction/job/_display'); ?>
    </div>
</div>