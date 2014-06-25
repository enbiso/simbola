<?php
    $this->page_breadcrumb = array(
        'Developer' => array('/developer'),    
        'Service Manager' => array('/developer/service'),
        'Tester');
?>
<code>
    <span class="text-primary"><?= $this->module ?> . <strong><?= $this->service ?></strong> . </span>
    <span class="text-danger"><strong><?= $this->action ?></strong></span>
    <span class="text-primary">(</span>
    <span class="text-success"><?= implode(", ", $this->schema['req']['params']) ?></span>
    <span class="text-primary">)</span>
</code>
<hr/>
<div class="row">
    <div class="col-md-4">
        <code>Request</code>        
        <textarea id="service_params">{<?php
            $params = $this->schema['req']['params'];
            echo "\n";
            for ($index = 0; $index < count($params); $index++) {
                echo "\t\"$params[$index]\" : \"\"" . (((count($params) - 1) == $index) ? "\n" : ",\n");
            }
            ?>}</textarea>        
        <button class="btn btn-default btn-sm" id='service_execute'>Execute Service</button>
    </div>
    <div class="col-md-8">                  
        <code>Response</code>        
        <div id="service_response"></div>
    </div>
</div>

<script>
    var editor = CodeMirror.fromTextArea(document.getElementById("service_params"), {
        mode: "javascript",
        lineNumbers: true,
        matchBrackets: true,
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift",        
    });

    var reponse = CodeMirror.fromTextArea(document.getElementById("service_response"), {
        mode: "javascript",
        lineNumbers: true,
        matchBrackets: true,
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift",        
    });

    $('#service_execute').bind('click', function(e) {
        var rawParams = editor.getValue();
        var args = JSON.parse(rawParams);
        simbola.call.service('<?= $this->module ?>', '<?= $this->service ?>', '<?= $this->action ?>', args, function(data) {
            reponse.setValue(JSON.stringify(data, undefined, 2));
        }, false);
    });
</script>

<style>
    .CodeMirror {
        border: 1px solid #eee;
    }
    .CodeMirror-scroll {
        overflow-y: hidden;
        overflow-x: auto;
    }
</style>