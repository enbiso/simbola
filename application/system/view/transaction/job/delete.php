<?php
$this->page_breadcrumb = array(
    'System' => array('/system'),
    'Transaction' => array('/system/transaction'),
    'Job' => array('/system/transaction/job'),    
    sterm_get('system.transaction.job.delete.title'));

$object = $this->object;
$this->page_menu = array(
    array(
        'title' => sterm_get('system.transaction.job.delete.menu.update'),
        'link' => array('/system/transaction/jobUpdate',"id" => $object->id),
        'icon' => 'edit'
    ),
    array(
        'title' => sterm_get('system.transaction.job.delete.menu.view'),
        'link' => array('/system/transaction/jobView',"id" => $object->id),
        'icon' => 'file'
    ),
    array(
        'title' => sterm_get('system.transaction.job.delete.menu.list'),
        'link' => array('/system/transaction/job'),
        'icon' => 'list'
    ),
);
?>
<?php if($this->isDataSet('error')): ?>
    <div class="alert alert-warning"><?= shtml_ul($this->error) ?></div>
<?php endif; ?>
<div class="panel panel-default">
    <div class="panel-heading"><?= sterm_get('system.transaction.job.delete.panel.heading') ?></div>
    <div class="panel-body">
        <?php $this->pview('transaction/job/display/_' . $this->object->type); ?>
    </div>
    <div class="panel-footer">
        <form method="POST"><?= shtmlform_input_hidden_for($this->object, 'id', 'keys') ?><input type="submit" class="btn-warning btn" value="<?= sterm_get('system.transaction.job.delete.panel.btn_ok')?>"/></form>
    </div>
</div>