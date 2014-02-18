<h3>Create Module</h3>
<?php
echo shtmlform_start_service_proxy(
        "developer_module_create", array(
    'module' => 'developer',
    'service' => 'module',
    'action' => 'create'), "developer/module/index");
?>
<div>
    <?php echo shtmlform_label('TERM:developer.module.create.moduleName'); ?>
    <?php echo shtmlform_input('text', array('name' => 'params[moduleName]')); ?>
</div>
<div>
    <?php echo shtmlform_label('TERM:developer.module.create.modulePurpose'); ?>
    <?php echo shtmlform_textarea(array('name' => 'params[modulePurpose]')); ?>
</div>
<div class="btn-group">    
    <?php echo shtmlform_input('submit', array('class' => 'btn')); ?>
    <?php echo shtmlform_button('back', array('class' => 'btn'))?>
</div>
<?php echo shtmlform_end(); ?>
