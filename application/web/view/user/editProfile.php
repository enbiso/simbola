<?php
$this->page_breadcrumb = array(    
    'My Profile' => array('web/user/myProfile'),    
    'Edit');
?>
<?php if($this->isDataSet('error')): ?>
    <div class="alert alert-warning"><?= $this->error ?></div>
<?php endif; ?>
<div class="row">
    <?= shtmlform_start("person_form", null, 'POST', array('class' => 'col-md-8')) ?>
    <div class="form-group">
        <?= shtmlform_label_for($this->object, 'name') ?>
        <?= shtmlform_input_text_for($this->object, 'name') ?>        
    </div>
    <div class="form-group">
        <?= shtmlform_label_for($this->object, 'email') ?>
        <?= shtmlform_input_text_for($this->object, 'email') ?>        
    </div>
        <div class="form-actions">
            <input type="submit" value="Save" class="btn btn-default"/>
        </div>
    <?= shtmlform_end(); ?>
</div>