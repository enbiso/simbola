<?php
    $this->page_breadcrumb = array(
        'Developer' => array('/developer'),    
        'Service Manager');
?>
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
    <div class="col-md-9" id="service_tester">
        
    </div>
</div>
    
<script type="text/javascript">
    $(function() {
        $("#service_tree").dynatree({
            onActivate: function(node) {
                if(node.data.isFolder){
                    return;
                }
                $.post(simbola.url.action('developer/service/tester',{partial:true,service:node.data.key}), function(output){
                    $('#service_tester').html(output);
                });                
            }
        });
        $.post(simbola.url.action('developer/service/tester',{partial:true}), function(output){
            $('#service_tester').html(output);
        });
    });
</script>