<div class="row">
    <div class="col-md-4">
        <?= shtmlform_group_input_for("datetime-local", $this->object, 'valid_from'); ?>
    </div>
    <div class="col-md-4">
        <?= shtmlform_group_input_for("datetime-local", $this->object, 'valid_till'); ?>
    </div>
    <div class="col-md-4">
        <?= shtmlform_group_input_for('number', $this->object, 'interval'); ?>
    </div>
</div>    
<hr/>
<?= shtmlform_group_textarea_for($this->object, "description") ?>
<hr/>
<div class="row">
    <div class="col-md-6">
        <?= shtmlform_group_select_for($this->object, 'priority', \application\system\model\transaction\Job::getPriorities()) ?>
    </div>
    <div class="col-md-6">
        <?= shtmlform_group_select_for($this->object, 'queue_id', application\system\model\transaction\Queue::getSelectData("id", "description")) ?>
    </div>
</div>
<hr/>