        <div class="row">
            <div class="col-md-2 text-muted"><?= $this->object->eTerm('id') ?></div>
            <div class="col-md-2"><?php echo $this->object->id; ?></div>        

            <div class="col-md-2 text-muted"><?= $this->object->eTerm('user_id') ?></div>
            <div class="col-md-2"><?php echo $this->object->user->user_name; ?></div>        

            <div class="col-md-2 text-muted">Status</div>
            <div class="col-md-2"><?php echo $this->object->state(); ?></div>        
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-2 text-muted"><?= $this->object->eTerm('description') ?></div>
            <div class="col-md-10"><?php echo $this->object->description; ?></div>    
        </div>
    </div> 
</div>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-2 text-muted"><?= $this->object->eTerm('valid_from') ?></div>
            <div class="col-md-2"><?php echo $this->object->valid_from->format('Y-m-d H:i:s'); ?></div>        

            <div class="col-md-2 text-muted"><?= $this->object->eTerm('valid_till') ?></div>
            <div class="col-md-2"><?php echo $this->object->valid_till->format('Y-m-d H:i:s'); ?></div>        

            <div class="col-md-2 text-muted"><?= $this->object->eTerm('interval') ?></div>
            <div class="col-md-2"><?php echo $this->object->interval; ?></div>     

        </div>
        <hr/>
        <div class="row">
            <div class="col-md-2 text-muted"><?= $this->object->eTerm('next_execute') ?></div>
            <div class="col-md-2"><?php echo shtml_encode($this->object->next_execute, 'Y-m-d H:i:s'); ?></div>        

            <div class="col-md-2 text-muted"><?= $this->object->eTerm('last_execute') ?></div>
            <div class="col-md-2"><?php echo shtml_encode($this->object->last_execute, 'Y-m-d H:i:s'); ?></div>        

            <div class="col-md-2 text-muted"><?= $this->object->eTerm('execute_count') ?></div>
            <div class="col-md-2"><?php echo $this->object->execute_count; ?></div>     

        </div>
    </div> 
</div>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-2 text-muted"><?= $this->object->eTerm('priority') ?></div>
            <div class="col-md-2"><?php
                $priorities = \application\system\model\transaction\Job::getPriorities();
                echo $priorities[$this->object->priority];
                ?>
            </div>        

            <div class="col-md-2 text-muted"><?= $this->object->eTerm('type') ?></div>
            <div class="col-md-2"><?php echo $this->object->type; ?></div>        

            <div class="col-md-2 text-muted"><?= $this->object->eTerm('queue_id') ?></div>
            <div class="col-md-2"><?php echo $this->object->queue_id; ?></div>        
        </div>
        <hr/>