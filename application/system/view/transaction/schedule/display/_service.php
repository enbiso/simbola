<?php $this->pview('transaction/schedule/display/_common') ?>
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