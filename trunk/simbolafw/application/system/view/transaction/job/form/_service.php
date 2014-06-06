<br/>
<div>
    <?= shtmlform_start("transactionJob_form_service", null, 'POST', array('class' => 'col-md-8')) ?>
    <?= shtmlform_input("hidden", array('name' => 'data[type]', 'value' => 'service')) ?>
    <div class="row">
        <div class="col-md-6">
            <?= shtmlform_group_select_for($this->object, 'priority', \application\system\model\transaction\Job::getPriorities()) ?>
        </div>
        <div class="col-md-6">
            <?= shtmlform_group_select_for($this->object, 'queue_id', application\system\model\transaction\Queue::getSelectData("id", "description")) ?>
        </div>
    </div>
    <hr/>
    <?php $service_data = (object)json_decode($this->object->content); ?>
    <div class="row form-group">
        <div class="col-md-4"><label>Module</label><?= shtmlform_input("text", array('name' => 'service[module]', 'placeholder' => 'Module', 'class' => 'form-control', 'value' => (property_exists($service_data, "module") ? $service_data->module : ""))) ?></div>
        <div class="col-md-4"><label>Service</label><?= shtmlform_input("text", array('name' => 'service[service]', 'placeholder' => 'Service', 'class' => 'form-control', 'value' => (property_exists($service_data, "service") ? $service_data->service : ""))) ?></div>
        <div class="col-md-4"><label>Action</label><?= shtmlform_input("text", array('name' => 'service[action]', 'placeholder' => 'Action', 'class' => 'form-control', 'value' => (property_exists($service_data, "action") ? $service_data->action : ""))) ?></div>
    </div>
    <hr/>
    <div class="row form-group">
        <div class="col-md-12"><label>Parameters</label>
            <?= shtmlform_textarea(array('name' => 'service[params]', 'id' => 'service_job_params'), (property_exists($service_data, "params") ? $service_data->params : "")) ?>
            <script>
                CodeMirror.fromTextArea(document.getElementById("service_job_params"), {
                    lineNumbers: true,
                    matchBrackets: true,
                    mode: "javascript",
                    indentUnit: 4,
                    indentWithTabs: true,
                    enterMode: "keep",
                    tabMode: "shift",
                });
            </script>
        </div>
    </div>    

    <div class="form-actions">
        <input type="submit" value="Save" class="btn btn-default"/>
    </div>
    <?= shtmlform_end(); ?>
</div>