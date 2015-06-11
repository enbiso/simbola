<?php $url = simbola\Simbola::app()->url; ?>
<script>
    $(function(){        
        <?php $page = new \simbola\core\component\url\lib\Page();
        $page->loadFromArray(array('system/rbam/enduserRoles','user' => '__USER__')); ?>
        var ur_url = "<?php echo $page->getUrl(); ?>";
        $("#user_role").dynatree({
            debugLevel: 2,
            persist: false,
            checkbox: true,
            selectMode: 3,
            initAjax: {
                url: ur_url.replace('__USER__',$('#user_role_select').val())                
            },
            onSelect: function(select, node) {             
                var post_data = {
                    'parent':$('#user_role_select').val(),
                    'grants':getNodeData(node),
                    'auth_type':'<?php echo simbola\core\component\auth\lib\ap\AuthType::ENDUSER_ROLE; ?>'
                };
                $("body").css("cursor", "progress");
                $.post("rbam/grant", post_data, function(data){
                    $("body").css("cursor", "auto");
                    new PNotify(data);
                }, 'json');               
            }
        });
        $('#user_role_select').bind('change',function(){                    
            $('#user_role').dynatree('option','initAjax',{
                url: ur_url.replace('__USER__',$('#user_role_select').val())                
            }).dynatree('getTree').reload();
        });
    });
</script>