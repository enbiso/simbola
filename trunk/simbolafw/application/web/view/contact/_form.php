<div class="row">
    <?= shtmlform_start("contact_form", null, 'POST', array('class' => 'col-md-6')) ?>
    <div class="form-group">                
        <?= shtmlform_input_text_for($this->object, 'title') ?>        
    </div>
    <div class="form-group">                
        <?= shtmlform_textarea_for($this->object, 'message', 'data', array('rows' => '5')) ?>        
    </div>
    <div class="form-group">                
        <?= shtmlform_input_text_for($this->object, 'email') ?>        
    </div>
    <div class="form-group">                
        <img src="data:image/jpeg;base64,<?= $this->object->getCapchaImage(); ?>"/>
        <input type="text" name="data[capcha]" placeholder="Capcha" class="form-control"/>            
    </div>
    <div class="form-actions">
        <input type="submit" value="<?= sterm_get("web.contact.index.btnSendMessage") ?>" class="btn btn-success btn-lg"/>
    </div>
    <?= shtmlform_end(); ?>
</div>        