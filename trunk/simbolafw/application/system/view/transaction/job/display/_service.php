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
</div>
<hr/>
<?php $service_data = (object) json_decode($this->object->content); ?>
<div class="row">
    <div class="col-md-2 text-muted">Service</div>
    <div class="col-md-4">
        <?= property_exists($service_data, "module") ? $service_data->module : "" ?> .
        <?= property_exists($service_data, "service") ? $service_data->service : "" ?> .
        <?= property_exists($service_data, "action") ? $service_data->action : "" ?>
    </div>        
</div>
<hr/>
<div class="row">
    <div class="col-md-2 text-muted">Parameters</div>
    <div class="col-md-8"> <code><?= property_exists($service_data, "params") ? $service_data->params : "" ?></code></div>
</div>
<hr/>
<div class="row">
    <div class="col-md-2 text-muted"><?= $this->object->eTerm('output') ?></div>
    <div class="col-md-4"><?php echo $this->object->output; ?></div>        
</div>