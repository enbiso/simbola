<div class="row">
    <?= shtmlform_start("transactionJob_form", null, 'POST', array('class' => 'col-md-8')) ?>
    <?= shtmlform_group_input_for('text', $this->object, 'id') ?>
    <?= shtmlform_group_input_for('text', $this->object, 'priority') ?>
    <?= shtmlform_group_input_for('text', $this->object, 'type') ?>
    <?= shtmlform_group_input_for('text', $this->object, 'queue_id') ?>
    <?= shtmlform_group_input_for('text', $this->object, 'content') ?>
    <?= shtmlform_group_input_for('text', $this->object, 'output') ?>
    <?= shtmlform_group_input_for('text', $this->object, 'user_id') ?>
        <div class="form-actions">
            <input type="submit" value="Save" class="btn btn-default"/>
        </div>
    <?= shtmlform_end(); ?>
</div>