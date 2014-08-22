<?php
$this->page_breadcrumb = array(
    'System' => array('/system'),
    'Transaction');
?>
<ul class="list-group col-md-4">
    <li class="list-group-item"><?= shtml_action_link("Crons", array('system/transaction/cron')) ?></li>
    <li class="list-group-item"><?= shtml_action_link("Jobs", array('system/transaction/job')) ?></li>
    <li class="list-group-item"><?= shtml_action_link("Queues", array('system/transaction/queue')) ?></li>    
    <li class="list-group-item"><?= shtml_action_link("Schedules", array('system/transaction/schedule')) ?></li>    
</ul>