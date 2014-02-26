<script>    
    $(function(){
    var ur_url = '<?= surl_geturl(array("/system/rbam/accessObjects")) ?>';
    $("#access_obj_list").dynatree({
        debugLevel: 2,
        persist: false,
        checkbox: true,
        selectMode: 3,
        initAjax: { url: ur_url },
        onSelect: function(){
            if($("#access_obj_list").dynatree('getTree').getSelectedNodes().length <= 0){
                $("#btn_access_obj_list_remove").addClass('disabled');
            }else{
                $("#btn_access_obj_list_remove").removeClass('disabled');
            }
        }
    });
});
</script>
<script>

function registerModule(){
    var name = $('#select_modules').val();              
    var url = '<?= surl_geturl(array('system/rbam/registerModule', 'module' => '__MDL__')) ?>';
    url = url.replace("__MDL__",name);
    $("body").css("cursor", "progress");
    $.post(url, function(data){
        $("body").css("cursor", "auto");
        $.pnotify(data);        
        $("#access_obj_list").dynatree('getTree').reload();
    },'json');
}

function registerAllModules(){    
    var url = '<?= surl_geturl(array("/system/rbam/registerAllModules")) ?>';    
    $("body").css("cursor", "progress");
    $.post(url, function(data){
        $("body").css("cursor", "auto");
        $.pnotify(data);        
        $("#access_obj_list").dynatree('getTree').reload();
    },'json');
}
    
function removeObjs(){
    var selNodes = $("#access_obj_list").dynatree('getTree').getSelectedNodes();
    if(selNodes.length <= 0){
        return;
    }
    var selKeys = $.map(selNodes, function(node){
        return node.data.key;
    });
    var post_data = {            
        'objs': selKeys            
    };
    $("body").css("cursor", "progress");
    $.post('<?= surl_geturl(array("/system/rbam/unregister")) ?>', post_data, function(data){
        $("body").css("cursor", "auto");
        $.pnotify(data);
        $("#access_obj_list").dynatree('getTree').reload();
    }, 'json');
}
</script>