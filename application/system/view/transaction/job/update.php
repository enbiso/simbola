<?php
$this->page_breadcrumb = array(
    'System' => array('/system'),
    'Transaction' => array('/system/transaction'),
    'Job' => array('/system/transaction/job'),    
    sterm_get('system.transaction.job.update.title'));

$object = $this->object;
$this->page_menu = array(    
    array(
        'title' => sterm_get('system.transaction.job.update.menu.list'),
        'link' => array('/system/transaction/jobList'),
        'icon' => 'list'
    ),
    array(
        'title' => sterm_get('system.transaction.job.update.menu.view'),
        'link' => array('/system/transaction/jobView',"id" => $object->id),
        'icon' => 'file'
    ),
    array(
        'title' => sterm_get('system.transaction.job.update.menu.delete'),
        'link' => array('/system/transaction/jobDelete',"id" => $object->id),
        'icon' => 'remove'
    ),    
);
?>
<?php if($this->isDataSet('error')): ?>
    <div class="alert alert-warning"><?= shtml_ul($this->error) ?></div>
<?php endif; ?>
<?php $this->pview('transaction/job/_form'); ?>