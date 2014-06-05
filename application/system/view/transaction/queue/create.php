<?php
$this->page_breadcrumb = array(
    'System' => array('/system'),
    'Transaction' => array('/system/transaction'),
    'Queue' => array('/system/transaction/queue'),    
    sterm_get('system.transaction.queue.create.title'));

$this->page_menu = array(
    array(
        'title' => sterm_get('system.transaction.queue.create.menu.list'),
        'link' => array('/system/transaction/queueList'),
        'icon' => 'list'
    ),
);
?>
<?php if($this->isDataSet('error')): ?>
    <div class="alert alert-warning"><?= shtml_ul($this->error) ?></div>
<?php endif; ?>
<?php $this->pview('transaction/queue/_form'); ?>