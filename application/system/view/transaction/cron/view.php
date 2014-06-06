<?php
$this->page_breadcrumb = array(
    'System' => array('/system'),
    'Transaction' => array('/system/transaction'),
    'Cron' => array('/system/transaction/cron'),       
    sterm_get('system.transaction.cron.view.title'));

$object = $this->object;
$this->page_menu = array(    
    array(
        'title' => sterm_get('system.transaction.cron.view.menu.delete'),
        'link' => array('/system/transaction/cronDelete',"id" => $object->id),
        'icon' => 'remove'
    ),    
);
?>
<div class="panel panel-default">
    <div class="panel-heading"><?= sterm_get('system.transaction.cron.view.panel.heading') ?></div>
    <div class="panel-body">
        <?php $this->pview('transaction/cron/_display'); ?>
    </div>
</div>
<?php $this->pview('transaction/cron/_queue') ?>