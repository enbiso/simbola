<?php
$this->resource_list = array('codemirror');
$this->page_breadcrumb = array(
    'System' => array('/system'),
    'Transaction' => array('/system/transaction'),
    'Schedule' => array('/system/transaction/schedule'),    
    sterm_get('system.transaction.schedule.view.title'));

$object = $this->object;
$this->page_menu = array(    
    array(
        'title' => sterm_get('system.transaction.schedule.view.menu.create'),
        'link' => array('/system/transaction/scheduleCreate'),
        'icon' => 'plus'
    ),   
    array(
        'title' => sterm_get('system.transaction.schedule.view.menu.update'),
        'link' => array('/system/transaction/scheduleUpdate',"id" => $object->id),
        'icon' => 'edit'
    ),
    array(
        'title' => sterm_get('system.transaction.schedule.view.menu.delete'),
        'link' => array('/system/transaction/scheduleDelete',"id" => $object->id),
        'icon' => 'remove'
    ),    
);
$this->state_menu = $object->getStateChangeInfo(true, array('execute', 'error'));
?>
<div class="panel panel-default">
    <div class="panel-heading"><?= sterm_get('system.transaction.schedule.view.panel.heading') ?></div>
    <div class="panel-body">
        <?php $this->pview('transaction/schedule/display/_' . $this->object->type); ?>
    </div>
</div>