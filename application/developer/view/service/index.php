<?php
    $this->page_breadcrumb = array(
        'Developer' => array('/developer'),    
        'Service Manager');
?>
<style>
    .CodeMirror {
        border: 1px solid #eee;
    }
    .CodeMirror-scroll {
        overflow-y: hidden;
        overflow-x: auto;
    }
</style>
<div class="row">
    <div id="service_tree" class="col-md-3">
        <ul id="treeData" style="display: none;">
            <?php foreach ($this->modules as $moduleName => $services): ?>
                <?php $resItem = new \simbola\core\component\resource\lib\ResItem('image', "developer", 'icons/folder_application_MODULE.png') ?>
                <li data="isFolder: true, icon: '<?= $resItem->getUrl() ?>'"><?= $moduleName ?>
                    <ul>
                        <?php foreach ($services as $serviceName => $actions): ?>
                            <?php $resItem = new \simbola\core\component\resource\lib\ResItem('image', "developer", 'icons/folder_application_MODULE_service.png') ?>
                            <li data="isFolder: true, icon: '<?= $resItem->getUrl() ?>'"><?= $serviceName ?>
                                <ul>
                                    <?php foreach ($actions as $actionName => $schema): ?>
                                        <?php $resItem = new \simbola\core\component\resource\lib\ResItem('image', "developer", 'icons/folder_application_MODULE_service_action.png') ?>
                                        <li data="isFolder: false, icon: '<?= $resItem->getUrl() ?>', key:'<?= "$moduleName.$serviceName.$actionName" ?>'"><?= $actionName ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="col-md-9">
        <button class="btn btn-success btn-sm" id='service_execute'>Execute Service</button>
        <hr/>
        <div id="service_tester"></div>
    </div>
</div>
    
<script type="text/javascript">
    var currService = {};
    var editor;
    var response;
    $(function() {
        $("#service_tree").dynatree({
            onActivate: function(node) {
                if(node.data.isFolder){
                    return;
                }
                $.post(simbola.url.action('developer/service/tester',{partial:true,service:node.data.key}), function(output){
                    $('#service_tester').html(output);
                    currService = node.data.key.split('.');
                    editor = CodeMirror.fromTextArea(document.getElementById("service_params"), {
                        mode: "javascript",
                        lineNumbers: true,
                        matchBrackets: true,
                        indentUnit: 4,
                        indentWithTabs: true,
                        enterMode: "keep",
                        tabMode: "shift"        
                    });

                    response = CodeMirror.fromTextArea(document.getElementById("service_response"), {
                        mode: "javascript",
                        lineNumbers: true,
                        matchBrackets: true,
                        indentUnit: 4,
                        indentWithTabs: true,
                        enterMode: "keep",
                        tabMode: "shift"        
                    });
                });                
            }
        });
        $.post(simbola.url.action('developer/service/tester',{partial:true}), function(output){
            $('#service_tester').html(output);
        });
        $('#service_execute').bind('click', function(e) {
            var rawParams = editor.getValue();
            var args = JSON.parse(rawParams);
            simbola.call.service(currService[0], currService[1], currService[2], args, function(data) {
                response.setValue(JSON.stringify(data, undefined, 2));
            }, false);
        });
    });
</script>