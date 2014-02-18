<div class="row">
    <?= shtmlform_start("content_form", null, 'POST', array('class' => 'col-md-8')) ?>
    <div class="form-group">
        <?= shtmlform_label_for($this->object, 'id') ?>
        <?= shtmlform_input_text_for($this->object, 'id') ?>        
    </div>
    <div class="form-group">
        <?= shtmlform_label_for($this->object, 'description') ?>
        <?= shtmlform_textarea_for($this->object, 'description') ?>        
    </div>
    <div class="form-group">
        <?= shtmlform_label_for($this->object, 'content') ?>
        <?= shtmlform_textarea_for($this->object, 'content') ?>        
    </div>
        <div class="form-actions">
            <input type="submit" value="Save" class="btn btn-default"/>
        </div>
    <?= shtmlform_end(); ?>
</div>