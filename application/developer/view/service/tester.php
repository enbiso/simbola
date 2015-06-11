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
    </div>
    <div class="col-md-8">                  
        <code>Response</code>        
        <textarea id="service_response"/>
    </div>
</div>