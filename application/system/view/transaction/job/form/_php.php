<br/>
<div>
    <?= shtmlform_start("transactionJob_form_php", null, 'POST', array('class' => 'col-md-8')) ?>
    <?= shtmlform_input("hidden", array('name' => 'data[type]', 'value' => 'php'))?>
    <div class="row">
        <div class="col-md-6">
            <?= shtmlform_group_select_for($this->object, 'priority', \application\system\model\transaction\Job::getPriorities()) ?>
        </div>
        <div class="col-md-6">
            <?= shtmlform_group_select_for($this->object, 'queue_id', application\system\model\transaction\Queue::getSelectData("id", "description")) ?>
        </div>
    </div>
    <hr/>
    <?= shtmlform_group_textarea_for($this->object, 'content', 'data', array('id' => 'job_php_editor')) ?>
    <script>
         CodeMirror.fromTextArea(document.getElementById("job_php_editor"), {
            lineNumbers: true,
            matchBrackets: true,
            mode: "text/x-php",
            indentUnit: 4,
            indentWithTabs: true,
            enterMode: "keep",
            tabMode: "shift",
            extraKeys: {"Ctrl-Space": "autocomplete"}
        });
    </script>
    <hr/>
    <div class="form-actions">
        <input type="submit" value="Save" class="btn btn-default"/>
    </div>
    <?= shtmlform_end(); ?>
</div>