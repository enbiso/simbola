<?php
$this->page_breadcrumb = array(
    'System' => array('/system'),
    'Transaction');
?>
<ul>
    <li><?= shtml_action_link("Crons", array('system/transaction/cron')) ?></li>
    <li><?= shtml_action_link("Jobs", array('system/transaction/job')) ?></li>
    <li><?= shtml_action_link("Queues", array('system/transaction/queue')) ?></li>
</ul>