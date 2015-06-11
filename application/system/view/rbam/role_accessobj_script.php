<?php $url = simbola\Simbola::app()->url; ?>
<script>
    $(function(){
        <?php $page = new \simbola\core\component\url\lib\Page();
        $page->loadFromArray(array('system/rbam/accessObjects','role' => '__ROLE__')); ?>
        var ra_url = "<?php echo $page->getUrl(); ?>";
        $("#role_accessobj").dynatree({
            debugLevel: 2,
            persist: false,
            checkbox: true,
            selectMode: 3,
            initAjax: {
                url: ra_url.replace('__ROLE__',$('#role_accessobj_select').val())  
            },
            onSelect: function(select, node) {                       
                var post_data = {
                    'parent':$('#role_accessobj_select').val(),
                    'grants':getNodeData(node),
                    'auth_type':'<?php echo simbola\core\component\auth\lib\ap\AuthType::ACCESS_OBJECT; ?>'
                };
                $("body").css("cursor", "progress");
                $.post("rbam/grant", post_data, function(data){
                    $("body").css("cursor", "auto");
                    new PNotify(data);
                }, 'json');
            }
        });
        $('#role_accessobj_select').bind('change',function(){                    
            $('#role_accessobj').dynatree('option','initAjax',{
                url: ra_url.replace('__ROLE__',$('#role_accessobj_select').val())  
            }).dynatree('getTree').reload();
        });
    });
</script>