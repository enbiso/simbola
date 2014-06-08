<div class="row">
    <div class="col-md-1 text-muted"><?= $this->object->eTerm('id') ?></div>
    <div class="col-md-2"><?php echo $this->object->id; ?></div>        

    <div class="col-md-2 text-muted"><?= $this->object->eTerm('execute_count') ?></div>
    <div class="col-md-2"><?php echo $this->object->execute_count; ?></div>                        
    
    <div class="col-md-1 text-muted"><?= $this->object->eTerm('interval') ?></div>
    <div class="col-md-2"><?php echo $this->object->interval; ?></div>        

    <div class="col-md-1 text-muted"><?= $this->object->eTerm('job_count') ?></div>
    <div class="col-md-1"><?php echo $this->object->job_count; ?></div>   
</div>        <hr/>
<div class="row">
    <div class="col-md-1 text-muted"><?= $this->object->eTerm('state') ?></div>
    <div class="col-md-2">
        <span><?php echo $this->object->state(); ?></span>
        <span class="glyphicon glyphicon-<?= $this->object->state() == 'ready'?'ok':'question' ?>-sign"></span>
    </div>   
    
    <div class="col-md-2 text-muted"><?= $this->object->eTerm('last_execute') ?></div>
    <div class="col-md-4"><?php echo $this->object->last_execute; ?></div>        
</div>        