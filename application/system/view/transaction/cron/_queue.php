<?php

$grid = new application\system\library\simgrid\WidgetSimGrid("system_transactionQueue_list");
$grid->setTitle("Queues Attached");
$grid->setTableCss('table-condensed table-hover');
$grid->setDataSource("system", "transaction", "cronQueue");
$grid->setCondition(array("cron_id = ?", $this->object->id));
$grid->setColumns(array(
    "queue_id" => "Queue ID",
    "queue.description" => "Description",
    "Actions",
));
$grid->setActions(array(
    shtml_link("remove", false, array('class' => 'btnCronQueueRemove btn btn-xs btn-default', 'onclick' => '{removeCronQueue("%queue_id%")}')),
));
?>
<hr/>
<div class="row">
    <?= shtmlform_start("create_queue_line") ?>
    <?php $cronQueue = new \application\system\model\transaction\CronQueue(array('cron_id' => $this->object->id)); ?>
    <?= shtmlform_input_hidden_for($cronQueue, 'cron_id') ?>    
    <div class="col-md-3">
        <?= shtmlform_select_for($cronQueue, 'queue_id', application\system\model\transaction\Queue::getSelectData('id', "description")) ?>
    </div>
    <div class="form-actions">
        <input type="submit" value="Add Queue" class="btn btn-default"/>
    </div>
    <?= shtmlform_end() ?>
</div>
<script>
    $('#create_queue_line').bind('submit',function(e){
        e.preventDefault();        
        simbola.call.service('system', 'transaction', 'cronQueueCreate', $(this).serializeObject(), function(data){            
            $('#system_transactionQueue_list').simGrid_Reload();
        });
    });
    function removeCronQueue(queue){
        simbola.call.service('system', 'transaction', 'cronQueueDelete', {
            keys: {
                queue_id: queue,
                cron_id: '<?=$this->object->id?>'
            }
        }, function(data){    
            $('#system_transactionQueue_list').simGrid_Reload();            
        });        
    }
</script>
<hr/>
<?= $grid->getDisplayData(); ?>