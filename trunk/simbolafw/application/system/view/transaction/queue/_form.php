<div class="row">
    <?= shtmlform_start("transactionQueue_form", null, 'POST', array('class' => 'col-md-8')) ?>
    <?= shtmlform_group_input_for('text', $this->object, 'id') ?>
    <?= shtmlform_group_input_for('text', $this->object, 'description') ?>
        <div class="form-actions">
            <input type="submit" value="Save" class="btn btn-default"/>
        </div>
    <?= shtmlform_end(); ?>
</div>