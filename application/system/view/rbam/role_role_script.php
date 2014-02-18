<?php $url = simbola\Simbola::app()->url; ?>
<script>
    $(function(){
        <?php $page = new \simbola\core\component\url\lib\Page();
        $page->loadFromArray(array('system/rbam/accessRoles','role' => '__ROLE__')); ?>
        var rr_url = "<?php echo $page->getUrl(); ?>";
        var silent = false;
        $("#role_role").dynatree({
            debugLevel: 2,
            persist: false,
            checkbox: true,
            selectMode: 3,
            initAjax: {
                url: rr_url.replace('__ROLE__',$('#role_role_select').val())  
            },
            onSelect: function(select, node) {             
                var post_data = {
                    'parent':$('#role_role_select').val(),
                    'grants':getNodeData(node),
                    'auth_type':'<?php echo simbola\core\component\auth\lib\ap\AuthType::ACCESS_ROLE; ?>'
                };
                $("body").css("cursor", "progress");
                $.post("rbam/grant", post_data, function(data){
                    $("body").css("cursor", "auto");
                    if(data.type != 'success'){
                        silent = true;
                        node.select(!select);
                        $.pnotify(data);
                        return;
                    }
                    if(!silent){
                        $.pnotify(data);
                        silent = false;
                    }
                }, 'json');             
            }
        });
        $('#role_role_select').bind('change',function(){                    
            $('#role_role').dynatree('option','initAjax',{
                url: rr_url.replace('__ROLE__',$('#role_role_select').val())  
            }).dynatree('getTree').reload();
        });
    });
</script>