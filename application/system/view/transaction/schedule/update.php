<?php
$this->resource_list = array('codemirror');
$this->page_breadcrumb = array(
    'System' => array('/system'),
    'Transaction' => array('/system/transaction'),
    'Schedule' => array('/system/transaction/schedule'),    
    sterm_get('system.transaction.schedule.update.title'));

$object = $this->object;
$this->page_menu = array(    
    array(
        'title' => sterm_get('system.transaction.schedule.update.menu.list'),
        'link' => array('/system/transaction/scheduleList'),
        'icon' => 'list'
    ),
    array(
        'title' => sterm_get('system.transaction.schedule.update.menu.view'),
        'link' => array('/system/transaction/scheduleView',"id" => $object->id),
        'icon' => 'file'
    ),
    array(
        'title' => sterm_get('system.transaction.schedule.update.menu.delete'),
        'link' => array('/system/transaction/scheduleDelete',"id" => $object->id),
        'icon' => 'remove'
    ),    
);
?>
<?php if($this->isDataSet('error')): ?>
    <div class="alert alert-warning"><?= shtml_ul($this->error) ?></div>
<?php endif; ?>
<?php
if ($this->object->isValidType()) {
    $this->pview('transaction/schedule/form/_' . $this->object->type);
} else {
    ?><div class="alert alert-warning">Invalid job type - <?= $this->object->type ?></div><?php
}
?>