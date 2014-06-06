<div class="row">
    <div class="col-md-2 text-muted"><?= $this->object->eTerm('id') ?></div>
    <div class="col-md-2"><?php echo $this->object->id; ?></div>        

    <div class="col-md-2 text-muted"><?= $this->object->eTerm('priority') ?></div>
    <div class="col-md-2"><?php
        $priorities = \application\system\model\transaction\Job::getPriorities();
        echo $priorities[$this->object->priority];
        ?>
    </div>        

    <div class="col-md-2 text-muted"><?= $this->object->eTerm('type') ?></div>
    <div class="col-md-2"><?php echo $this->object->type; ?></div>        
</div>
<hr/>
<div class="row">
    <div class="col-md-2 text-muted"><?= $this->object->eTerm('queue_id') ?></div>
    <div class="col-md-2"><?php echo $this->object->queue_id; ?></div>        

    <div class="col-md-2 text-muted"><?= $this->object->eTerm('user_id') ?></div>
    <div class="col-md-2"><?php echo $this->object->user->user_name; ?></div>        
    
    <div class="col-md-2 text-muted">Status</div>
    <div class="col-md-2"><?php echo $this->object->state(); ?></div>        
</div>        <hr/>
<div class="row">
    <div class="col-md-2 text-muted"><?= $this->object->eTerm('content') ?></div>
    <div class="col-md-4"><code><?php echo nl2br(shtml_encode($this->object->content)); ?></code></div>        
</div>        <hr/>
<div class="row">
    <div class="col-md-2 text-muted"><?= $this->object->eTerm('output') ?></div>
    <div class="col-md-4"><?php echo $this->object->output; ?></div>        
</div>