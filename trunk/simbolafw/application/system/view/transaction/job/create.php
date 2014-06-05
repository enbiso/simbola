<?php
$this->page_breadcrumb = array(
    'System' => array('/system'),
    'Transaction' => array('/system/transaction'),
    'Job' => array('/system/transaction/job'),    
    sterm_get('system.transaction.job.create.title'));

$this->page_menu = array(
    array(
        'title' => sterm_get('system.transaction.job.create.menu.list'),
        'link' => array('/system/transaction/jobList'),
        'icon' => 'list'
    ),
);
?>
<?php if($this->isDataSet('error')): ?>
    <div class="alert alert-warning"><?= shtml_ul($this->error) ?></div>
<?php endif; ?>
<?php $this->pview('transaction/job/_form'); ?>