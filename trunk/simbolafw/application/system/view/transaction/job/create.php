<?php
$this->resource_list = array('codemirror');
$this->page_breadcrumb = array(
    'System' => array('/system'),
    'Transaction' => array('/system/transaction'),
    'Job' => array('/system/transaction/job'),    
    sterm_get('system.transaction.job.create.title'));

$this->page_menu = array(
    array(
        'title' => sterm_get('system.transaction.job.create.menu.list'),
        'link' => array('/system/transaction/job'),
        'icon' => 'list'
    ),
);
?>
<?php if($this->isDataSet('error')): ?>
    <div class="alert alert-warning"><?= shtml_ul($this->error) ?></div>
<?php endif; ?>
<ul class="nav nav-tabs">  
  <li class="active"><a href="#type_service" data-toggle="tab">Service</a></li>
  <li><a href="#type_php" data-toggle="tab">PHP Script</a></li>  
</ul>
<!-- Tab panes -->
<div class="tab-content">
  <div class="tab-pane active" id="type_service"><?php $this->pview('transaction/job/form/_service') ?></div>
  <div class="tab-pane" id="type_php"><?php $this->pview('transaction/job/form/_php') ?></div>
</div>