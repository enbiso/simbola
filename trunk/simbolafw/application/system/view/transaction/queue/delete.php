<?php
$this->page_breadcrumb = array(
    'System' => array('/system'),
    'Transaction' => array('/system/transaction'),
    'Queue' => array('/system/transaction/queue'),    
    sterm_get('system.transaction.queue.delete.title'));

$object = $this->object;
$this->page_menu = array(
    array(
        'title' => sterm_get('system.transaction.queue.delete.menu.update'),
        'link' => array('/system/transaction/queueUpdate',"id" => $object->id),
        'icon' => 'edit'
    ),
    array(
        'title' => sterm_get('system.transaction.queue.delete.menu.view'),
        'link' => array('/system/transaction/queueView',"id" => $object->id),
        'icon' => 'file'
    ),
    array(
        'title' => sterm_get('system.transaction.queue.delete.menu.list'),
        'link' => array('/system/transaction/queueList'),
        'icon' => 'list'
    ),
);
?>
<?php if($this->isDataSet('error')): ?>
    <div class="alert alert-warning"><?= shtml_ul($this->error) ?></div>
<?php endif; ?>
<div class="panel panel-default">
    <div class="panel-heading"><?= sterm_get('system.transaction.queue.delete.panel.heading') ?></div>
    <div class="panel-body">
        <?php $this->pview('transaction/queue/_display'); ?>
    </div>
    <div class="panel-footer">
        <form method="POST"><?= shtmlform_input_hidden_for($this->object, 'id', 'keys') ?><input type="submit" class="btn-warning btn" value="<?= sterm_get('system.transaction.queue.delete.panel.btn_ok')?>"/></form>
    </div>
</div>