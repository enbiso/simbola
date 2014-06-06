<?php
$this->page_breadcrumb = array(
    'System' => array('/system'),
    'Transaction' => array('/system/transaction'),
    'Queue' => array('/system/transaction/queue'),    
    sterm_get('system.transaction.queue.update.title'));

$object = $this->object;
$this->page_menu = array(    
    array(
        'title' => sterm_get('system.transaction.queue.update.menu.list'),
        'link' => array('/system/transaction/queue'),
        'icon' => 'list'
    ),
    array(
        'title' => sterm_get('system.transaction.queue.update.menu.view'),
        'link' => array('/system/transaction/queueView',"id" => $object->id),
        'icon' => 'file'
    ),
    array(
        'title' => sterm_get('system.transaction.queue.update.menu.delete'),
        'link' => array('/system/transaction/queueDelete',"id" => $object->id),
        'icon' => 'remove'
    ),    
);
?>
<?php if($this->isDataSet('error')): ?>
    <div class="alert alert-warning"><?= shtml_ul($this->error) ?></div>
<?php endif; ?>
<?php $this->pview('transaction/queue/_form'); ?>